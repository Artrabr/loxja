<?php

require_once __DIR__ . "/../Product/ProductDB.php";
require_once __DIR__ . "/../../Connection.php";

class AmountBellowOne extends Exception
{
}

// class ItemNotFound extends Exception
// {
// }
// class AmountBiggerThanStock extends Exception
// {
// }


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
        // TODO: maybe implement this? or it can be done when saving the whole shopping cart to the db; maybe the user should be allowed to have a shopping cart with items that aren't available; regardless, one should check for that SOMEWHERE in the code.
        // $productDB = new ProductDB(Connection::conectar());
        // $currentStateOfProduct = $productDB->getProductByID($this->product->getId());
        // if (is_null($currentStateOfProduct)) {
        //     throw new ItemNotFound("");
        // }
        // if ($amount > $currentStateOfProduct->getAmountAvailable()) {
        //     throw new AmountBiggerThanStock((string)$currentStateOfProduct->getAmountAvailable());
        // }
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
