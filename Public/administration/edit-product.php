<?php
require_once __DIR__ . "/../../bootstrap.php";

if(!isset($_SESSION['client_object'])) {
    header("Location: ../Login/index.php?error=not_logged_in");
    exit();
}
/*if(!isset($_SESSION['adm'] == true)) {                              ADICIONAR APÓS O SISTEMA DE LOGIN DE ADM FICAR PRONTO!!!!!!!!!!!!
    header("Location: ../Login/index.php?error=notadm");
    exit();
}*/

require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../Src/Class/Product/ProductDB.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
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
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">Preço</th>
                                <th scope="col">Descrição</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Categoria</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($product->getId()); ?></td>
                                <td><input aria-label="Nome" type="text" name="name" value="<?php echo htmlspecialchars($product->getName()); ?>" required></td>
                                <td><input aria-label="Preço" type="number" name="price" value="<?php echo htmlspecialchars($product->getPrice()); ?>" step="0.01"></td>
                                <td><input aria-label="Descrição" type="text" name="description" value="<?php echo htmlspecialchars($product->getDescription()); ?>"></td>
                                <td><input aria-label="Quantidade" type="number" name="quantity" value="<?php echo htmlspecialchars($product->getAmountAvailable()); ?>" min="0" required></td>
                                <td><input aria-label="Categoria" type="text" name="category" value="<?php echo htmlspecialchars($product->getCategory()); ?>" required></td>
                                <td><button type="submit">Editar</button></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <form action="../../Src/deleteProduct.php" method="POST">
                    <input type="hidden" name="id" value="<?=$product->getId();?>">
                    <input type="submit" value="Delete">
                </form>
            </div>
        <?php
            endforeach;
        ?>
    </div>
</body>
</html>