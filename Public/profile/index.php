<?php

require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/Client/ClientDB.php';
require_once __DIR__ . '/../../Src/Validation.php';

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
            <li><strong>Nome:</strong> <?= htmlspecialchars($client->getName()) ?></li>
            <li><strong>E-mail:</strong> <?= htmlspecialchars($client->getEmail()) ?></li>
        </ul>
    </div>

    <div class="profile-actions">
            <a href="deleteSession.php" class="btn-logout">Sair</a>
    </div>
</div>

</body>

</html>