<?php
require_once __DIR__ . "/../../bootstrap.php";
require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../Src/Class/Product/ProductDB.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos</title>
</head>
<body>
    <div>
        <h1>Lista de Produtos</h1>
        <?php
            $pdo = Connection::conectar();
            $db = new ProductDB($pdo);
            $products = $db->getAllProducts();
            foreach ($products as $product):

        ?>
            <div>
                <form action="../../Src/editProduct.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->getId()); ?>">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product->getName()); ?>" required>
                    <input type="number" name="price" value="<?php echo htmlspecialchars($product->getPrice()); ?>" step="0.01">
                    <input type="text" name="description" value="<?php echo htmlspecialchars($product->getDescription()); ?>">
                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($product->getAmountAvailable()); ?>" min="0" required>
                    <input type="text" name="category" value="<?php echo htmlspecialchars($product->getCategory()); ?>" required>
                    <button type="submit">Editar</button>
                </form>
            </div>
        <?php
            endforeach;
        ?>
    </div>
</body>
</html>