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

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        header('Location: /loxja/Public/Administration/add-product.php?imageerror=true');
        exit;
    }

    $imageName = $_FILES['image']['name'];
    $tempFile = $_FILES['image']['tmp_name'];
    $extension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowedMimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
    ];
    $mimeType = (new finfo(FILEINFO_MIME_TYPE))->file($tempFile);

    if (!isset($allowedMimeTypes[$extension]) || $mimeType !== $allowedMimeTypes[$extension]) {
        header('Location: /loxja/Public/Administration/add-product.php?notPermitedForm=true'); 
        exit;
    }

    $newName = bin2hex(random_bytes(16)) . '.' . $extension;
    $uploadDirectory = __DIR__ . '/../Public/Product/images';
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0775, true) && !is_dir($uploadDirectory)) {
        header('Location: /loxja/Public/Administration/add-product.php?moveNotMade=true'); 
        exit;
    }
    $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $newName;

    if (!move_uploaded_file($tempFile, $destination)) {
        header('Location: /loxja/Public/Administration/add-product.php?moveNotMade=true'); 
        exit;
    }
    // Store a browser URL in the database; the upload itself uses the filesystem path above.
    $imageUrl = '/loxja/Public/Product/images/' . $newName;

    $existingProduct = $db->getProductByName($productName);

    if ($existingProduct !== null) { //adiciona quantidade ao produto existente
        $newAmount = $existingProduct->getAmountAvailable() + (int) $quantity;
        $db->updateProductByName(
            $productName,
            (float) $_POST['price'],
            $productDescription,
            $newAmount,
            $productCategory,
            $imageUrl
        );

        header('Location: /loxja/Public/Administration/add-product.php?productAlreadyExists=true');
        exit;
    }

    $db->createProduct(
        $productName,
        (float) $_POST['price'],
        $productDescription,
        (int) $quantity,
        $productCategory,
        $imageUrl
    );

    header('Location: /loxja/Public/Administration/add-product.php?addNewProduct=true');
    exit;