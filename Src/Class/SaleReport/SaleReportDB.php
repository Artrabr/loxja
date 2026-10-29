<?php

require_once __DIR__ . "/SaleReport.php";
require_once __DIR__ . "/../Client/ClientDB.php";
require_once __DIR__ . "/../Product/ProductDB.php";

class SaleReportDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getReportsByClientId(int $clientId): array
    //retorna uma array com um objeto pra cada pedido (salesreport)
    {
        $sql = "
            SELECT 
                saleReport_id,
                clt_id,
                pdt_id,
                saleReport_amount,
                saleReport_method,
                saleReport_price,
                saleReport_created_at
            FROM saleReport
            WHERE clt_id = :clientId
        ";
        $params = [':clientId' => $clientId];
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $clientDB = new ClientDB($this->pdo);
        $productDB = new ProductDB($this->pdo);
        $reports = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $clientId = (int) $row['clt_id'];
            $productId = (int) $row['pdt_id'];
            $client = $clientDB->getClientByID($clientId);
            $product = $productDB->getProductByID($productId);

            if ($client === null || $product === null) {
                throw new RuntimeException(
                    "Sale report {$row['saleReport_id']} references a missing client or product" //pega o ingles do pai
                );
            }

            $reports[] = new SaleReport(
                (int) $row['saleReport_id'],
                $clientId,
                $productId,
                $client,
                $product,
                $row['saleReport_method'],
                (float) $row['saleReport_price'],
                $row['saleReport_created_at'],
                (int) $row['saleReport_amount']
            );
        }

        return $reports;
    }

    public function addSaleReport(
        int $clientId,
        int $productId,
        int $amount,
        string $method,
        float $price                                    
        /*
        O preço do produto muda com o tempo. 
        Se você só tivesse pdt_id e calculasse 
        o total usando o preço atual da tabela
        product, um pedido de 6 meses atrás passaria
        a mostrar o valor de hoje
        */
    ): bool
    //adiciona no banco e
    //retorna um booleano true se der tudo certo
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO saleReport
                (clt_id, pdt_id, saleReport_amount, saleReport_method, saleReport_price)
            VALUES (?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([ //se der retorna true se n retorna false
            $clientId,
            $productId,
            $amount,
            $method,
            $price
        ]);
    }

    public function getOrders(int $clientId): array
    //retorne uma array com objetos pra todos os pedidos
    //pode apagar esse codigo bizzaro q ta ai dentro da função
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE_FORMAT(sr.saleReport_created_at, '%Y-%m-%d %H:%i') as order_group,
                MAX(sr.saleReport_created_at) as full_date,
                sr.saleReport_method,
                sr.saleReport_status,
                SUM(sr.saleReport_price * sr.saleReport_amount) as order_total,
                COUNT(sr.saleReport_id) as item_count
            FROM saleReport sr
            WHERE sr.clt_id = ?
            GROUP BY order_group, sr.saleReport_method, sr.saleReport_status
            ORDER BY full_date DESC
        ");
        $stmt->execute([$clientId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

//=============---------vibecodado, responsavel verificar e apresentar relatorio-------------------------==================//
    public function getAdminSummary(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                COUNT(*) AS sale_count,
                COALESCE(SUM(saleReport_amount), 0) AS items_sold,
                COALESCE(SUM(saleReport_price * saleReport_amount), 0) AS revenue,
                COUNT(DISTINCT clt_id) AS customer_count,
                COALESCE(SUM(
                    CASE
                        WHEN saleReport_created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 DAY)
                        THEN saleReport_price * saleReport_amount
                        ELSE 0
                    END
                ), 0) AS revenue_last_30_days,
                COALESCE(SUM(
                    CASE
                        WHEN saleReport_created_at >= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 30 DAY)
                        THEN 1
                        ELSE 0
                    END
                ), 0) AS sales_last_30_days
            FROM saleReport
        ");

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAdminPaymentMethodStats(): array
    {
        $stmt = $this->pdo->query("
            SELECT
                saleReport_method AS method,
                COUNT(*) AS sale_count,
                COALESCE(SUM(saleReport_amount), 0) AS items_sold,
                COALESCE(SUM(saleReport_price * saleReport_amount), 0) AS revenue
            FROM saleReport
            GROUP BY saleReport_method
            ORDER BY sale_count DESC, revenue DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminTopProducts(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                p.pdt_name AS product_name,
                COALESCE(SUM(sr.saleReport_amount), 0) AS items_sold,
                COALESCE(SUM(sr.saleReport_price * sr.saleReport_amount), 0) AS revenue
            FROM saleReport sr
            INNER JOIN Product p ON p.pdt_id = sr.pdt_id
            GROUP BY sr.pdt_id, p.pdt_name
            ORDER BY items_sold DESC, revenue DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', max(1, min($limit, 20)), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAdminRecentSales(int $limit = 10): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                sr.saleReport_id AS sale_id,
                c.clt_name AS customer_name,
                p.pdt_name AS product_name,
                sr.saleReport_amount AS amount,
                sr.saleReport_method AS method,
                sr.saleReport_price AS unit_price,
                sr.saleReport_price * sr.saleReport_amount AS total,
                sr.saleReport_created_at AS created_at
            FROM saleReport sr
            INNER JOIN Client c ON c.clt_id = sr.clt_id
            INNER JOIN Product p ON p.pdt_id = sr.pdt_id
            ORDER BY sr.saleReport_created_at DESC, sr.saleReport_id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', max(1, min($limit, 50)), PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
//=============------------------------------------feito pelo igor---------------------------------==================//

    public function formatDate(string $data): string
    //se nao tem nada setado retorna '';
    {
        $dataFormat = '';
        if(!isset($data)){
            return $dataFormat;
        }

        if(!is_string($data)){
            try{
                $data = (string)$data;
            }catch(Exception $e){
                echo "Ocorreu um erro: " . $e->getMessage();
            }
        }

        try{
            $date = new DateTime($data);
            $dataFormat = $date->format('d/m/Y');
        } catch (Exception $e) {
            throw new InvalidArgumentException("Data inválida: " . $data, 0, $e);
        }
        
        return $dataFormat;
    }

    public function getOrdersInThisRange(string $start, string $end, ?int $clientId = null): array
    //retorna os valores do periodo inicial até o periodo final. caso o id nao for informado retornar todos os pedidos
    //porem se o id do cliente for informado retornar os pedidos que possuam o id
    {
        if(!isset($start)){
            $start = new DateTime();
        }

        if(!isset($end)){
            $end = new DateTime();
        }

        $endDate = formatDate($end);
        $startDate = formatDate($start);

        if ($startDate === false || $endDate === false) {
            throw new InvalidArgumentException('Não foi possível converter as datas informadas.');
        }

        if ($startDate > $endDate) {
            throw new InvalidArgumentException('A data inicial não pode ser posterior à data final.');
        }

        $sql = "
            SELECT
                DATE_FORMAT(sr.saleReport_created_at, '%Y-%m-%d %H:%i') AS order_group,
                MAX(sr.saleReport_created_at) AS full_date,
                sr.saleReport_method,
                NULL AS saleReport_status,
                SUM(sr.saleReport_price * sr.saleReport_amount) AS order_total,
                COUNT(sr.saleReport_id) AS item_count
            FROM saleReport sr
            WHERE sr.saleReport_created_at >= :startDate
                AND sr.saleReport_created_at < :endDate
        ";
        $params = [
            ':startDate' => $startDate, //ja formatado na linha 248
            ':endDate' => $endDate
        ];

        if ($clientId !== null) { //evitar erro de digitacao
            $sql .= " AND sr.clt_id = :clientId"; //junta com o antigo
            $params[':clientId'] = $clientId;  
        }

        $sql .= "
            GROUP BY order_group, sr.saleReport_method
            ORDER BY full_date DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}