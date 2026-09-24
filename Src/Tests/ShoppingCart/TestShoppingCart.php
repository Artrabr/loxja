<?php

require_once __DIR__ . "/../../Class/ShoppingCart/ShoppingCartDB.php";
require_once __DIR__ . "/../../ConnectionTests.php";

$pdo = ConnectionTests::connect();

$testResults = [
    "__construct" => false,
    "getProducts" => false,
    "getProductByID" => false,
    "setProducts" => false,
    "addProduct" => false,
    "removeProduct" => false,
    "addProductById" => false,
    "setProductAmount" => false,
    "getProductAmount" => false,
];

$shoppingCart = new ShoppingCart();
