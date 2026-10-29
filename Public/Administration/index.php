<?php
session_start();
require_once __DIR__ . '/../theme-toggle.php';
$tema = currentTheme();
// TODO: verificar se o usuário tem permissão de administrador.

require_once __DIR__ . '/../../Src/Class/ClientAddress/ClientAddressDB.php';
require_once __DIR__ . '/../../Src/Connection.php';
?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de administração | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../theme.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
  </head>
  <body>

    <header class="site-header">
      <nav class="topbar" aria-label="Navegação administrativa">
        <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
          <img src="../logo.png" alt="" class="brand-mark">
          <span>Loxja <small>café</small></span>
        </a>
        <ul class="nav-links">
          <li><a href="../index.php">Loja</a></li>
          <li><a href="index.php" aria-current="page">Administração</a></li>
          <li><a href="add-product.php">Adicionar produto</a></li>
          <li><a href="edit-product.php">Editar produtos</a></li>
        </ul>
        <?php renderThemeToggle($tema); ?>
      </nav>
    </header>

    <main class="admin-dashboard">
      <section class="admin-dashboard-section">
        <header class="admin-page-heading">
          <p class="eyebrow">Área administrativa</p>
          <h1>Gerenciar produtos</h1>
        </header>
        <nav class="full-width-nav">
          <ul>
            <li><a href="add-product.php"><b>Adicionar produto</b></a></li>
            <li><a href="edit-product.php"><b>Editar produtos</b></a></li>
            <li><a href="../index.php"><b>Voltar à loja</b></a></li>
          </ul>
        </nav>
      </section>
    </main>

    <footer>
      <hr>
      <p>
        <small>&copy; 2026 Loxja Café.</small>
      </p>
    </footer>


  
  </body>
</html>
