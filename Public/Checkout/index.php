<?php
    require_once __DIR__ . "/../Src/Class/Client/Client.php";
    require_once __DIR__ . "/../Src/Connection.php";
    require_once __DIR__ . "/../bootstrap.php";
    require_once __DIR__ . "/../Src/Class/Product/Product.php";
    require_once __DIR__ . "/../Src/Class/Product/ProductDB.php";
    require_once __DIR__ . "/../Src/Class/ShoppingCart/ShoppingCart.php";
    require_once __DIR__ . "/../Src/Class/ShoppingCart/ShoppingCartDB.php";

    $pdo = Connection::conectar();
    $db  = new ProductDB($pdo);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        
    </body>
</html>