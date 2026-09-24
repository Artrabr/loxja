<?php

require_once __DIR__ . "/../Product/ProductDB.php";
require_once __DIR__ . "/../../Connection.php";

// TODO: implement this
class AmountBiggerThanStock extends Exception
{
}

class AmountBellowOne extends Exception
{
}

class ItemNotFound extends Exception
{
}

class ShoppingCartItem
{
    protected Product $product;
    protected int $amount;

    public function __construct(product $product, int $amount)
    {
        $this->product = $product;
        $this->amount = $amount;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setAmount(int $amount)
    {
        $this->amount = $amount;
        if ($amount <= 1) {
            throw new AmountBellowOne("");
        }
        $productDB = new ProductDB(Connection::conectar());
        $currentStateOfProduct = $productDB->getProductByID($this->product->getId());
        if (is_null($currentStateOfProduct)) {
            throw new ItemNotFound("");
        }
        if ($amount > $currentStateOfProduct->getAmountAvailable()) {
            throw new AmountBiggerThanStock("");
        }
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
