<?php

require_once __DIR__ . "/ShoppingCart.php";

class ShoppingCartDB
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}
