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
                    "Sale report {$row['saleReport_id']} references a missing client or product."
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

    public function getOrdersByClientId(int $clientId): array
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
}