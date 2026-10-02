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
/*
<h2><?php echo htmlspecialchars($product->getName()); ?></h2>
<p>Preço: <?php echo htmlspecialchars($product->getPrice()); ?></p>
<p>Descrição: <?php echo htmlspecialchars($product->getDescription()); ?></p>
<p>Quantidade Disponível: <?php echo htmlspecialchars($product->getAmountAvailable()); ?></p>
<p>Categoria: <?php echo htmlspecialchars($product->getCategory()); ?></p>
<a href="edit-product-form.php?id=<?php echo $product->getId(); ?>">Editar</a>
*/
        ?>
            <div>
                <form action="edit-product-form.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->getId()); ?>">
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product->getName()); ?>">
                    <input type="number" name="price" value="<?php echo htmlspecialchars($product->getPrice()); ?>">
                    <input type="text" name="description" value="<?php echo htmlspecialchars($product->getDescription()); ?>">
                    <input type="number" name="amount_available" value="<?php echo htmlspecialchars($product->getAmountAvailable()); ?>">
                    <input type="text" name="category" value="<?php echo htmlspecialchars($product->getCategory()); ?>">
                    <button type="submit">Editar</button>
                </form>
            </div>
        <?php
            endforeach;
        ?>
    </div>
</body>
</html>