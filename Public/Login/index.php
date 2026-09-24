<?php
$error = $_GET['error'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Loxja Cafe</title>
    <link rel="stylesheet" href="../login/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>

<body>

<main class="auth-page">

    <a class="brand" href="../index.php">
        <span class="brand-mark">L</span>
        <span>Loxja <small>cafe</small></span>
    </a>
    <?php if ($error === 'not_logged_in'): ?>
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
    <?php else:?>
        <section class="auth-card" aria-labelledby="login-title">
            <p class="eyebrow">Que bom ter voce por aqui</p>
            <h1 id="login-title">Entre na sua conta</h1>
            <p class="intro">
                Acompanhe seus pedidos e torne sua proxima pausa ainda mais gostosa.
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
                Ainda nao tem conta?
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