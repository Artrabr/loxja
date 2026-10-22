<?php

require_once __DIR__ . "/SaleReport.php";

class SaleReportDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getReportsByClientId(
        int $clientId, 
        ?string $startDate = null, 
        ?string $endDate = null, 
        ?string $status = null
    ): array {
        $sql = "
            SELECT 
                sr.saleReport_id, sr.clt_id, sr.pdt_id, 
                p.pdt_name, c.clt_name,
                sr.saleReport_method, sr.saleReport_price, 
                sr.saleReport_created_at, sr.saleReport_amount, sr.saleReport_status
            FROM saleReport sr
            INNER JOIN Product p ON p.pdt_id = sr.pdt_id
            INNER JOIN client c ON c.clt_id = sr.clt_id
            WHERE sr.clt_id = :clientId
        ";

        $params = [':clientId' => $clientId];

        if ($startDate !== null) {
            $sql .= " AND sr.saleReport_created_at >= :startDate";
            $params[':startDate'] = $startDate . ' 00:00:00';
        }
        if ($endDate !== null) {
            $sql .= " AND sr.saleReport_created_at <= :endDate";
            $params[':endDate'] = $endDate . ' 23:59:59';
        }
        if ($status !== null) {
            $sql .= " AND sr.saleReport_status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY sr.saleReport_created_at DESC, sr.saleReport_id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

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
                $row['pdt_name'],
                $row['saleReport_status'] ?? 'completed',
                $row['clt_name']
            );
        }

        return $reports;
    }

    public function addSaleReport(
        int $clientId,
        int $productId,
        string $method,
        float $price,
        int $amount,
        string $status = 'completed',
        ?string $createdAt = null
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO saleReport
                (clt_id, pdt_id, saleReport_method, saleReport_price, saleReport_amount, saleReport_status, saleReport_created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $clientId,
            $productId,
            $method,
            $price,
            $amount,
            $status,
            $createdAt ?? date('Y-m-d H:i:s'),
        ]);

        return (int) $this->pdo->lastInsertId();
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