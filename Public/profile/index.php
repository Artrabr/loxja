<?php

require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/Client/ClientDB.php';
require_once __DIR__ . '/../../Src/Validation.php';

function getPassword(Client $client)
{
   
    $id = $client->getId();
    global $pdo;
    $smt = $pdo->prepare("SELECT clt_password FROM Client WHERE clt_id = :id");
    $smt->execute(['id' => $id]);
    return $smt->fetchColumn();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['client_object'])) {
    header('Location: ../Login/index.php?error=not_logged');
    exit;
}

$pdo = Connection::conectar();
$clientDB = new ClientDB($pdo);
$client = $clientDB->getClientByID((int) $_SESSION['client_object']->getId());

if (!$client) {
    session_destroy();
    header('Location: ../Login/index.php?error=nonexistent_user');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Loxja Café</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
    <link href="profilestyle.css" rel="stylesheet">
    
</head>
<body class="client-page">
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegacao principal">
            <a class="brand" href="../index.php" aria-label="Loxja Cafe, inicio">
                <img src="../logo.png" alt="Loxja Cafe" class="brand-mark">
                <span>Loxja <small>cafe</small></span>
            </a>
            <a href="../profile/index.php">Perfil</a>
            <a href="../Client/index.php">Informações Técnicas</a>
        </nav>
    </header>

    <main>
        <section>
            <img src="<?= htmlspecialchars($client->getImage(), ENT_QUOTES, 'UTF-8') ?>" alt="Foto de perfil" class="profile-pic-hero">
            <h1>Bem-vindo, <?= htmlspecialchars($client->getName()) ?>!</h1>
            <p>Esta é a sua área exclusiva para gerenciar seus dados cadastrais.</p>
            <a href="deleteSession.php" class="btn-logout-link">Sair da conta</a>
        </section>
        
        <section>
            <h2>Informações do Usuário</h2>
            <p>Aqui você pode visualizar e atualizar suas informações pessoais.</p>

            <?php if (isset($_SESSION['msg'])): ?>
                <p class="msg-success"><?= htmlspecialchars($_SESSION['msg']) ?></p>
                <?php unset($_SESSION['msg']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['erro'])): ?>
                <p class="msg-error"><?= htmlspecialchars($_SESSION['erro']) ?></p>
                <?php unset($_SESSION['erro']); ?>
            <?php endif; ?>

            <form method="POST" action="editar.php" enctype="multipart/form-data">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($client->getName()) ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($client->getEmail()) ?>" required>

                <label for="imagem">Foto de perfil (JPG, PNG ou WebP; até 5 MB)</label>
                <input type="file" id="imagem" name="imagem" accept="image/jpeg,image/png,image/webp">

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit">Salvar Alterações</button>
            </form>
        </section>
    </main>
</body>
</html>