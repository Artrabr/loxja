<?php
require_once __DIR__ . "/../../bootstrap.php";
require_once __DIR__ . "/../theme-toggle.php";
$tema = currentTheme();

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
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../theme.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
</head>
<body class="edit-products admin-page">
    <header class="site-header">
        <nav class="topbar" aria-label="Navegação administrativa">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span>
            </a>
            <ul class="nav-links">
                <li><a href="../index.php">Loja</a></li>
                <li><a href="index.php">Administração</a></li>
                <li><a href="add-product.php">Adicionar produto</a></li>
            </ul>
            <?php renderThemeToggle($tema); ?>
        </nav>
    </header>
    <main>
        <p class="eyebrow">Área administrativa</p>
        <h1>Editar produtos</h1>
        <?php
            $pdo = Connection::conectar();
            $db = new ProductDB($pdo);
            $products = $db->getAllProducts();
        ?>
        <table>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Imagem</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Preço</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <?php $editFormId = "edit-product-" . htmlspecialchars((string) $product->getId(), ENT_QUOTES, 'UTF-8'); ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string) $product->getId()); ?></td>
                        <td><img class="edit-product-image" src="<?php echo htmlspecialchars($product->getImage(), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>"></td>
                        <td><input form="<?php echo $editFormId; ?>" aria-label="Nome" type="text" name="name" value="<?php echo htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8'); ?>" required></td>
                        <td><input form="<?php echo $editFormId; ?>" aria-label="Preço" type="number" name="price" value="<?php echo htmlspecialchars((string) $product->getPrice(), ENT_QUOTES, 'UTF-8'); ?>" step="0.01"></td>
                        <td><input form="<?php echo $editFormId; ?>" aria-label="Descrição" type="text" name="description" value="<?php echo htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8'); ?>"></td>
                        <td><input form="<?php echo $editFormId; ?>" aria-label="Quantidade" type="number" name="quantity" value="<?php echo htmlspecialchars((string) $product->getAmountAvailable(), ENT_QUOTES, 'UTF-8'); ?>" min="0" required></td>
                        <td><input form="<?php echo $editFormId; ?>" aria-label="Categoria" type="text" name="category" value="<?php echo htmlspecialchars($product->getCategory(), ENT_QUOTES, 'UTF-8'); ?>" required></td>
                        <td>
                            <form id="<?php echo $editFormId; ?>" action="../../Src/editProduct.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $product->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit">Editar</button>
                            </form>
                            <form action="../../Src/deleteProduct.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $product->getId(), ENT_QUOTES, 'UTF-8'); ?>">
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="index.php">Voltar à administração</a></p>
    </main>
</body>
</html>