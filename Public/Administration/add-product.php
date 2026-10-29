<?php
    require_once __DIR__ . "/../../Src/Class/Client/Client.php";
    require_once __DIR__ . "/../../bootstrap.php";
    require_once __DIR__ . "/../theme-toggle.php";
    require_once __DIR__ . '/layout.php';
    $tema = currentTheme();
    requireAdminAccess();
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar produto | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../theme.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
</head>
<body class="admin-page">
    <?php renderAdminLayoutStart($tema, 'add-product'); ?>
        <p class="admin-eyebrow">Catálogo</p>
        <h1>Adicionar produto</h1>
        <form class="admin-form" action="../../Src/addProduct.php" method="POST" enctype="multipart/form-data">
        <label for="name">Nome</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Preço</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="description">Descrição</label>
        <textarea id="description" name="description" required></textarea>

        <label for="quantity">Quantidade Disponível</label>
        <input type="number" id="quantity" name="quantity" min="0" required>

        <label for="image">Imagem</label>
        <input type="file" id="image" name="image">

        <label for="category">Categoria</label>
        <input type="text" id="category" name="category" required>

        <button type="submit">Adicionar Produto</button>
        </form>
    <?php renderAdminLayoutEnd(); ?>
</body>
</html>