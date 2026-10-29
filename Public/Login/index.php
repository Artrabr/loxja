<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_tema'])) {
    $temaAtual = $_COOKIE['tema'] ?? 'light';
    $temaNovo  = ($temaAtual === 'dark') ? 'light' : 'dark';

    setcookie('tema', $temaNovo, [
        'expires'  => time() + 60 * 60 * 24 * 365,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    $returnQuery = (string)($_POST['return_query'] ?? '');
    $path        = strtok($_SERVER['REQUEST_URI'], '?');
    $location    = $path . ($returnQuery !== '' ? '?' . $returnQuery : '');

    header('Location: ' . $location);
    exit;
}

$tema  = $_COOKIE['tema'] ?? 'light';
$error = $_GET['error'] ?? null;

?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Loxja Café</title>
    <link rel="stylesheet" href="../theme.css">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>

<body>

<main class="auth-page">
    <form method="post"
          action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) ?>"
          class="theme-toggle-form">
        <input type="hidden" name="return_query"
               value="<?= htmlspecialchars($_SERVER['QUERY_STRING'] ?? '', ENT_QUOTES) ?>">
        <button type="submit" name="toggle_tema" class="theme-toggle"
                aria-label="<?= $tema === 'dark' ? 'Ativar modo claro' : 'Ativar modo escuro' ?>"
                title="<?= $tema === 'dark' ? 'Modo claro' : 'Modo escuro' ?>">
            <?= $tema === 'dark' ? '☀️' : '🌙' ?>
        </button>
    </form>

    <a class="brand" href="../index.php">
        <img src="../logo.png" alt="" class="brand-mark">
        <span>Loxja <small>café</small></span>
    </a>
    <?php if ($error === 'not_logged_in') : ?>
        <section class="auth-card" aria-labelledby="login-title">
            <div class="popup">
                <div class="popup-box">
                    <a href="index.php" class="fechar"><i class="fa-regular fa-circle-xmark"></i></a>
                    <h2>NÃO LOGADO</h2>
                    <p>
                        Você precisa estar logado para acessar essa página.
                    </p>
                </div>
            </div>
        </section>
    <?php else :?>
        <section class="auth-card" aria-labelledby="login-title">
            <p class="eyebrow">Que bom ter você por aqui</p>
            <h1 id="login-title">Entre na sua conta</h1>
            <p class="intro">
                Acompanhe seus pedidos e torne sua próxima pausa ainda mais gostosa.
            </p>
            <form action="../../Src/LoginProcess.php" method="post">
                <label for="email">E-mail</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                >
                <label for="senha">Senha</label>
                <input
                    id="senha"
                    name="senha"
                    type="password"
                    autocomplete="current-password"
                    required
                >
                <button type="submit">Entrar</button>
            </form>
            <p class="switch-page">
                Ainda não tem conta?
                <a href="../Registration/index.php">Cadastre-se</a>
            </p>
            <a class="back-link" href="../index.php">
                &larr; Voltar para a loja
            </a>
        </section>
    <?php endif;?>
</main>
</body>
</html>
