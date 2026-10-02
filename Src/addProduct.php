<?php
require_once __DIR__ . "/../bootstrap.php";
require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "/Class/Product/ProductDB.php";

function checkIfAllPostDataCame(array $requiredFields) : bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
    }

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field]) || trim((string) $_POST[$field]) === '') {
            return false;
        }
    }

    return true;
}

function connectToDatabase()
{
    $pdo = Connection::conectar();
    return $pdo;
}

function disconnectFromDatabase(&$pdo)
{
    $pdo = null;
}

//=========================codigo================================
    $requiredFields = ['name', 'price', 'description', 'category'];
    $quantity = $_POST['quantity'] ?? $_POST['amountAvailable'] ?? null;

    if (!checkIfAllPostDataCame($requiredFields) || $quantity === null || trim((string) $quantity) === '') {
        header('Location: /loxja/Public/Administration/add-product.php?allDataCamed=false');
        exit;
    }

    $pdo = connectToDatabase();
    $db = new ProductDB($pdo);

    $productName = trim((string) $_POST['name']);
    $productDescription = trim((string) $_POST['description']);
    $productCategory = trim((string) $_POST['category']);

    $existingProduct = $db->getProductByName($productName);

    if ($existingProduct !== null) { //adiciona quantidade ao produto existente
        $newAmount = $existingProduct->getAmountAvailable() + (int) $quantity;
        
        $db->updateProductByName(
            $productName,
            (float) $_POST['price'],
            $productDescription,
            $newAmount,
            $productCategory
        );

        header('Location: /loxja/Public/Administration/add-product.php?productAlreadyExists=true');
        exit;
    }

    $db->createProduct(
        $productName,
        (float) $_POST['price'],
        $productDescription,
        (int) $quantity,
        $productCategory
    );

    header('Location: /loxja/Public/Administration/add-product.php?addNewProduct=true');
    exit;