<?php
// 1. Carrega as classes ANTES de qualquer coisa de sessão
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../Src/Class/Client/ClientDB.php';

// 2. Inicia a sessão (só se ainda não estiver ativa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 3. Verifica se está logado
if (!isset($_SESSION['client_id'])) {
    header('Location: ../Login/index.php?error=not_logged');
    exit;
}

// 4. Busca o cliente no banco
$pdo = Connection::conectar();
$clientDB = new ClientDB($pdo);
$client = $clientDB->getClientByID((int) $_SESSION['client_id']);

if (!$client) {
    session_destroy();
    header('Location: ../Login/index.php?error=nonexistent_user');
    exit;
}

// 5. Foto padrão (enquanto não tem upload)
$foto = '../../perfilsemfoto.png';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil</title>
    <link rel="stylesheet" href="profilestyle.css">
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de perfil" class="profile-pic">
        <h1><?= htmlspecialchars($client->getName()) ?></h1>
        <p><?= htmlspecialchars($client->getEmail()) ?></p>
    </div>

    <div class="profile-info">
        <h2>Informações do Usuário</h2>
        <ul>
            <li><strong>ID:</strong> <?= htmlspecialchars($client->getId()) ?></li>
            <li><strong>Nome:</strong> <?= htmlspecialchars($client->getName()) ?></li>
            <li><strong>E-mail:</strong> <?= htmlspecialchars($client->getEmail()) ?></li>
        </ul>
    </div>

    <div class="profile-actions">
        <a href="../../Src/logoutProcess.php" class="btn-logout">Sair</a>
    </div>
</div>

</body>
</html>