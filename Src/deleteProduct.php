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

function disconnectFromDatabase($pdo)
{
    $pdo = null;
}

//=========================codigo================================

if (!isset($_POST['id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /loxja/Public/Administration/edit-product.php');
    exit;
}

$id = (int)$_POST['id'];

$pdo = connectToDatabase();
$db = new ProductDB($pdo);
$db->deleteProductByID($id);
disconnectFromDatabase($pdo);

header('Location: /loxja/Public/Administration/edit-product.php');
exit;
