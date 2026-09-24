<?php

require_once __DIR__ . "/ShoppingCart.php";

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
        $this->pdo->beginTransaction();
        try {
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

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
