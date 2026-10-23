<?php

require_once __DIR__ . "/ShoppingCart.php";
require_once __DIR__ . "/../Product/Product.php";

class ShoppingCartDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getShoppingCartByClientId(int $id): ?ShoppingCart
    {
        $stmt = $this->pdo->prepare("
            SELECT p.pdt_id, p.pdt_name, p.pdt_price,p.pdt_description,p.pdt_amount,p.pdt_category, ic.ic_amount
            FROM ItensCart ic
            JOIN Product p ON p.pdt_id = ic.pdt_id
            WHERE ic.clt_id = ?
            ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($items)) {
            return null;
        }

        $shoppingCart = new ShoppingCart();
        foreach ($items as $item) {
            $product = new Product($item['pdt_id'], $item['pdt_name'], $item['pdt_price'], $item['pdt_description'], $item['pdt_amount'], $item['pdt_category']);
            $shoppingCart->addProduct($product, (int) $item['ic_amount']);
        }
        return $shoppingCart;
    }
    public function setShoppingCartByClientId(int $clientId, ShoppingCart $cart): void
    {
        // 1. Delete ALL items for this client
        $del = $this->pdo->prepare("DELETE FROM ItensCart WHERE clt_id = ?");
        $del->execute([$clientId]);

        // 2. Insert each item from the object
        $ins = $this->pdo->prepare("
            INSERT INTO ItensCart (clt_id, pdt_id, ic_amount)
            VALUES (?, ?, ?)
        ");
        foreach ($cart->getProducts() as $item) {
            $ins->execute([
                $clientId,
                $item->getProduct()->getId(),
                $item->getAmount(),
            ]);
        }
    }
    public function addItemToShoppingCartByClientId(int $clientId, int $productId, int $productAmount = 1)
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO ItensCart (clt_id, pdt_id, ic_amount)
        SELECT
            :clientId                              AS clt_id,
            p.pdt_id                               AS pdt_id,
            LEAST(:productAmount, p.pdt_amount)    AS ic_amount
        FROM Product p
        WHERE p.pdt_id = :productId
          AND p.pdt_amount > 0
        ON DUPLICATE KEY UPDATE
            ic_amount = LEAST(
                ItensCart.ic_amount + VALUES(ic_amount),
                (SELECT pdt_amount FROM Product WHERE pdt_id = ItensCart.pdt_id)
            )
        ");

        $stmt->execute([
            'clientId'      => $clientId,
            'productId'     => $productId,
            'productAmount' => $productAmount,
        ]);
    }
}
