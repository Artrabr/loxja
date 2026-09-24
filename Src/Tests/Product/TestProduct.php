<?php

require_once __DIR__ . "/../../Class/Product/Product.php";
require_once __DIR__ . "/../ConnectionTests.php";

$pdo = ConnectionTests::connect();

$testResults = [
    "getId" => false,
    "getName" => false,
    "setName" => false,
    "getPrice" => false,
    "setPrice" => false,
    "getDescription" => false,
    "setDescription" => false,
    "getAmountAvailable" => false,
    "setAmountAvailable" => false,
    "getCategory" => false,
    "setCategory" => false,
];

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

// --- Getters ---

try {
    $id = $productTest->getId();
    if ($id != $values['id']) {
        $testResults['getId'] = true;
    }
} catch (\Throwable $th) {
    $testResults['getId'] = true;
}

try {
    $name = $productTest->getName();
    if ($name != $values['name']) {
        $testResults['getName'] = true;
    }
} catch (\Throwable $th) {
    $testResults['getName'] = true;
}

try {
    $price = $productTest->getPrice();
    if ($price != $values['price']) {
        $testResults['getPrice'] = true;
    }
} catch (\Throwable $th) {
    $testResults['getPrice'] = true;
}

try {
    $desc = $productTest->getDescription();
    if ($desc != $values['description']) {
        $testResults['getDescription'] = true;
    }
} catch (\Throwable $th) {
    $testResults['getDescription'] = true;
}

try {
    $amt = $productTest->getAmountAvailable();
    if ($amt != $values['amountAvailable']) {
        $testResults['getAmountAvailable'] = true;
    }
} catch (\Throwable $th) {
    $testResults['getAmountAvailable'] = true;
}

try {
    $cat = $productTest->getCategory();
    if ($cat != $values['category']) {
        $testResults['getCategory'] = true;
    }
} catch (\Throwable $th) {
    $testResults['getCategory'] = true;
}

// --- Setters ---

try {
    $productTest->setName("New Name");
    if ($productTest->getName() != "New Name") {
        $testResults['setName'] = true;
    }
} catch (\Throwable $th) {
    $testResults['setName'] = true;
}

try {
    $productTest->setPrice(9.99);
    if ($productTest->getPrice() != 9.99) {
        $testResults['setPrice'] = true;
    }
} catch (\Throwable $th) {
    $testResults['setPrice'] = true;
}

try {
    $productTest->setDescription("New desc");
    if ($productTest->getDescription() != "New desc") {
        $testResults['setDescription'] = true;
    }
} catch (\Throwable $th) {
    $testResults['setDescription'] = true;
}

try {
    $productTest->setAmountAvailable(10);
    if ($productTest->getAmountAvailable() != 10) {
        $testResults['setAmountAvailable'] = true;
    }
} catch (\Throwable $th) {
    $testResults['setAmountAvailable'] = true;
}

try {
    $productTest->setCategory("new category");
    if ($productTest->getCategory() != "new category") {
        $testResults['setCategory'] = true;
    }
} catch (\Throwable $th) {
    $testResults['setCategory'] = true;
}

return $testResults;
