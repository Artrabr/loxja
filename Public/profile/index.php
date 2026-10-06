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
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Loxja Café</title>

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link href="../style_index.css" rel="stylesheet">

    <link rel="stylesheet" href="profilestyle.css">
</head>
<body>
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegacao principal">
            <a class="brand" href="../index.php" aria-label="Loxja Cafe, inicio">
                <img src="../logo.png" alt="Loxja Cafe" class="brand-mark">
                <span>Loxja <small>cafe</small></span>
            </a>


            
        </nav>
    </header>

    <main class="main-content">

        
        <div class="profile-container">
            <div class="profile-header">
                <div class="header-bg"></div>
                <div class="header-content">
                    <img src="../perfilsemfoto.png" alt="Foto de perfil" class="profile-pic">

                   
                    <h1><?= htmlspecialchars($client->getName()) ?></h1>
                    
                    <p><?= htmlspecialchars($client->getEmail()) ?></p>
                </div>
            </div>

            <div class="profile-info">
                <h2>Informações do Usuário</h2>
                <ul>
                    
                    <li>
                        <strong>Nome:</strong>
                         <input type="hidden" id="id" name="id" value="<?=$id?>" required>
                    <label for="cep"></label>
                    <input type="text" id="cep" name="cep" >
                        <span><?= htmlspecialchars($client->getName()) ?></span>
                        
                    </li>
                    <li>
                        <strong>E-mail:</strong>
                        <input type="hidden" id="id" name="id" value="<?=$id?>" required>
                    <label for="cep"></label>
                    <input type="text" id="cep" name="cep" >
                        <span><?= htmlspecialchars($client->getEmail()) ?></span>
                    </li>
                </ul>
            </div>

            <div class="profile-actions">
                <a href="deleteSession.php" class="btn-logout">Sair</a>
            </div>
        </div>

    </main>
    

</body>
</html>