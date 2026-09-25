<?php

require_once __DIR__ . "/../../Class/ShoppingCart/ShoppingCartDB.php";
require_once __DIR__ . "/../ConnectionTests.php";

$pdo = ConnectionTests::connect();

$testResults = [
    "getShoppingCartByClientId" => false,
    "setShoppingCartByClientId" => false,
];

$shoppingCartDB = new ShoppingCartDB($pdo);
$shoppingCart = new ShoppingCart();

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

$shoppingCart->addProduct($productTest, 5);

$clientDB = new ClientDB($pdo);
$client = $clientDB->createClient("teste", "email@teste.com", 1234);

try {
    $shoppingCartDB->setShoppingCartByClientId($client->getId(), $shoppingCart);
} catch (\Throwable $th) {
    $testResults['setShoppingCartByClientId'] = true;
}
$newShoppingCart = null;
try {
    $newShoppingCart = $shoppingCartDB->getShoppingCartByClientId($client->getId());
} catch (\throwable $th) {
    $testResults['getShoppingCartByClientId'] = true;
}

if ($shoppingCart != $newShoppingCart) {
    $testResults['getShoppingCartByClientId'] = true;
}

return $testResults;



// $shoppingCartDB->setShoppingCartByClientId()
