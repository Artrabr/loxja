<?php

require_once __DIR__ . "/../../Class/ShoppingCart/ShoppingCartDB.php";
require_once __DIR__ . "/../../ConnectionTests.php";

$pdo = ConnectionTests::connect();

$testResults = [
    "getShoppingCartByClientId" => false,
    "setShoppingCartByClientId" => false,
];

$shoppingCartDB = new ShoppingCartDB($pdo);
$shoppingCart = new ShoppingCart();
