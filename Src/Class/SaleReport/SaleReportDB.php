<?php

class SaleReportDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addReportProduct(int $id_cliente, int $id_produto, int $amount, string $method, float $price){
        $stmt = $this->pdo->prepare("
                INSERT INTO ItensCart (clt_id, pdt_id, saleReport_amount, saleReport_method, saleReport_price)
                VALUES (?, ?, ?, ?, ?)
            ");
        $data = [$id_cliente,$id_produto,$amount,$method,$price];
        $stmt->execute($data);
    }
    
}