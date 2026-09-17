<?php

require_once __DIR__ . "/Product.php";

class ProductDB
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getProductByID(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT pdt_name,pdt_price,pdt_category,pdt_id FROM Product WHERE pdt_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Product($row['pdt_id'], $row['pdt_name'], $row['pdt_price'], $row['pdt_category']);
        }

        return null;
    }

    public function createProduct(string $name, float $price, string $description, int $amountAvailable, string $category)
    {
        $stmt = $this->pdo->prepare("INSERT INTO Product (pdt_name,pdt_price,pdt_description,pdt_amount,pdt_category) VALUES (:name,:price,:description,:amountAvailable,:category)");
        $stmt->execute([
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'amountAvailable' => $amountAvailable,
            'category' => $category]);
        $id = (int) $this->pdo->lastInsertId();

        return new Product($id, $name, $price, $description, $amountAvailable, $category);
    }
}
