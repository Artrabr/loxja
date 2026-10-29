<?php
require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/Client/ClientDB.php';
require_once __DIR__ . '/../../Src/Validation.php';
require_once __DIR__ . '/../theme-toggle.php';

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
$tema = currentTheme();

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
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Loxja Café</title>

    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
    <link href="profilestyle.css" rel="stylesheet">
    
</head>
<body class="client-page">
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegação principal">
            <a class="brand" href="../index.php" aria-label="Loxja Café, início">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span>
            </a>
            <ul class="nav-links">
                <li><a href="../profile/index.php" aria-current="page">Perfil</a></li>
                <li><a href="../Client/index.php">Informações técnicas</a></li>
                <li><a href="../salesReport/index.php">Relatório de vendas</a></li>
            </ul>
            <?php renderThemeToggle($tema); ?>
        </nav>
    </header>

    <main>
        <section>
            <button type="button" class="profile-photo-trigger" id="open-profile-photo"
                    aria-label="Alterar foto de perfil" aria-haspopup="dialog">
                <img src="<?= htmlspecialchars($client->getImage(), ENT_QUOTES, 'UTF-8') ?>" alt="" class="profile-pic-hero">
                <span class="profile-photo-trigger-label">Alterar foto</span>
            </button>
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

                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit">Salvar Alterações</button>
            </form>
        </section>
    </main>
    <dialog class="profile-photo-dialog" id="profile-photo-dialog" aria-labelledby="profile-photo-title">
        <form method="post" action="editar.php" enctype="multipart/form-data" class="profile-photo-form">
            <button type="button" class="profile-photo-close" id="close-profile-photo"
                    aria-label="Fechar janela">×</button>
            <h2 id="profile-photo-title">Alterar foto de perfil</h2>
            <p>Envie uma imagem nítida nos formatos JPG, PNG ou WebP, com no máximo 5 MB.</p>
            <label for="profile-photo-file">Escolha uma foto</label>
            <input id="profile-photo-file" type="file" name="imagem"
                   accept="image/jpeg,image/png,image/webp" required>
            <input type="hidden" name="action" value="upload_image">
            <button type="submit" class="profile-photo-submit">Enviar foto</button>
        </form>
    </dialog>
    <script>
        const photoDialog = document.getElementById('profile-photo-dialog');
        document.getElementById('open-profile-photo').addEventListener('click', () => photoDialog.showModal());
        document.getElementById('close-profile-photo').addEventListener('click', () => photoDialog.close());
        photoDialog.addEventListener('click', (event) => {
            if (event.target === photoDialog) photoDialog.close();
        });
    </script>
</body>
</html>