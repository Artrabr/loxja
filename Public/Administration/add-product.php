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
    <header class="site-header">
        <nav class="topbar" aria-label="Navegação administrativa">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span>
            </a>
            <ul class="nav-links">
                <li><a href="../index.php">Loja</a></li>
                <li><a href="index.php">Administração</a></li>
                <li><a href="edit-product.php">Editar produtos</a></li>
            </ul>
            <?php renderThemeToggle($tema); ?>
        </nav>
    </header>
    <main class="admin-form-page">
        <p class="eyebrow">Área administrativa</p>
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
        <a class="admin-back-link" href="index.php">Voltar à administração</a>
    </main>
</body>
</html>