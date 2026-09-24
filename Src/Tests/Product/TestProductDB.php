<?php

require_once __DIR__ . "/../../Class/Product/ProductDB.php";
require_once __DIR__ . "/../ConnectionTests.php";

$pdo = ConnectionTests::connect();

$testResults = [
    "getProductByID" => false,
    "createProduct" => false,
];

$values = [
    "name"            => "Café fortissimo",
    "price"           => 29.99,
    "description"     => "O café mais forte do mundo!",
    "amountAvailable" => 42,
    "category"        => "high caffeine density",
];

$productDB = new ProductDB($pdo);
try {
    $createdProduct = $productDB->createProduct(
        $values["name"],
        $values["price"],
        $values["description"],
        $values["amountAvailable"],
        $values["category"]
    );
} catch (\Throwable $th) {
    $testResults["createProduct"] = true;
}

try {
    $databaseProduct = $productDB->getProductByID($createdProduct->getId());
} catch (\Throwable $th) {
    $testResults["getProductByID"] = true;
}

if ($createdProduct != $databaseProduct) {
    $testResults["getProductByID"] = true;
    $testResults["createProduct"] = true;
}

return $testResults;
