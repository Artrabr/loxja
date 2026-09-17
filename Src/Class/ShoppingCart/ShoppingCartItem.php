<?php

require_once __DIR__ . "/../Product/ProductDB.php";

class AmountBiggerThanStock extends Exception
{
}

class AmountBellowOne extends Exception
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

    public function setAmount(int $amount, bool $deleteIfNone = false)
    {
        $this->amount = $amount;
        if ($amount <= 1) {
            throw new AmountBellowOne("");
        }
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
