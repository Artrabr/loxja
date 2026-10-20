<?php

class SaleReportDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function getReportsByClientId(int $clientId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT sr.saleReport_id, sr.clt_id, sr.pdt_id, p.pdt_name,
                   sr.saleReport_method, sr.saleReport_price,
                   sr.saleReport_created_at, sr.saleReport_amount
            FROM saleReport sr
            INNER JOIN Product p ON p.pdt_id = sr.pdt_id
            WHERE sr.clt_id = ?
            ORDER BY sr.saleReport_created_at DESC, sr.saleReport_id DESC
        ");
        $stmt->execute([$clientId]);

        $reports = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reports[] = new SaleReport(
                (int) $row['saleReport_id'],
                (int) $row['clt_id'],
                (int) $row['pdt_id'],
                $row['saleReport_method'],
                (float) $row['saleReport_price'],
                $row['saleReport_created_at'],
                (int) $row['saleReport_amount'],
                $row['pdt_name']
            );
        }

        return $reports;
    }

    public function addReportProduct(int $id_cliente, int $id_produto, int $amount, string $method, float $price){
        $stmt = $this->pdo->prepare("
                INSERT INTO ItensCart (clt_id, pdt_id, saleReport_amount, saleReport_method, saleReport_price)
                VALUES (?, ?, ?, ?, ?)
            ");
        $data = [$id_cliente,$id_produto,$amount,$method,$price];
        $stmt->execute($data);
    }

    public function addSaleReport(
        int $clientId,
        int $productId,
        string $method,
        float $price,
        int $amount,
        ?string $createdAt = null
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO saleReport
                (clt_id, pdt_id, saleReport_method, saleReport_price, saleReport_amount, saleReport_created_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $clientId,
            $productId,
            $method,
            $price,
            $amount,
            $createdAt ?? date('Y-m-d H:i:s'),
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}