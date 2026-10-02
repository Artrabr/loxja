<?php

require_once __DIR__ . "/../bootstrap.php";
require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "/Class/Product/ProductDB.php";

function checkIfAllPostDataCame(array $requiredFields) : bool
{
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        return false;
    }

    foreach ($requiredFields as $field) {
        if (!isset($_POST[$field])) {
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
    $requiredFields = ['name', 'description', 'category','quantity'];

    if (!checkIfAllPostDataCame($requiredFields)) {
        header('Location: /loxja/Public/Administration/edit-product.php?allDataCamed=false');
        exit;
    }

    if($_POST['price'] == null)
        {$price = 0.00;}
    else
        {$price = (float)$_POST['price'];}

    $quantity = (int)$_POST['quantity'];

    if ((int)$_POST['quantity'] < 0) { //pedir se quer apagar o produto, caso a quantidade seja negativa
        header('Location: /loxja/Public/Administration/edit-product.php?askIfHeWantsToContinue=true');
        exit;
    }

    $pdo = connectToDatabase();
    $db = new ProductDB($pdo);

    $productName = trim((string) $_POST['name']);
    $productDescription = trim((string) $_POST['description']);
    $productCategory = trim((string) $_POST['category']);

    $existingProduct = $db->getProductByID((int)$_POST['id']);

    if ($existingProduct !== null) { //adiciona quantidade ao produto existente
        $db->updateProductByID(
            (int)$_POST['id'],
            $productName,
            $price,
            $productDescription,
            (int)$quantity,
            $productCategory
        );

        header('Location: /loxja/Public/Administration/edit-product.php?productUpdated=true');
        exit;
    }