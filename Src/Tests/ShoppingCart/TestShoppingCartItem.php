<?php

require_once __DIR__ . "/../../Class/ShoppingCart/ShoppingCartDB.php";
require_once __DIR__ . "/../ConnectionTests.php";

$pdo = ConnectionTests::connect();

$testResults = [
    "getProduct" => false,
    "SetAmount" => false,
    "GetAmount" => false,
];

$amount = 10;

$values = [
    "id"              => 1,
    "name"            => "Café fortissimo",
    "price"           => 29.99,
    "description"     => "O café mais forte do mundo!",
    "amountAvailable" => 42,
    "category"        => "high caffeine density",
];

$productTest = new Product(
    $values['id'],
    $values['name'],
    $values['price'],
    $values['description'],
    $values['amountAvailable'],
    $values['category']
);

$shoppingCartItem = new ShoppingCartItem($productTest, $amount);

if ($productTest != $shoppingCartItem->getProduct()) {
    $testResults["getProduct"] = true;
}

if ($amount != $shoppingCartItem->getAmount()) {
    $testResults["GetAmount"] = true;
}

$newAmount = 20;
$shoppingCartItem->setAmount($newAmount);

if ($newAmount != $shoppingCartItem->getAmount()) {
    $testResults['SetAmount'] = true;
}

return $testResults;
