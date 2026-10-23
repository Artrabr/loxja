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

$tema = $_COOKIE['tema'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro | Loxja Cafe</title>
	<link rel="stylesheet" href="../theme.css">
	<link rel="stylesheet" href="register.css">
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
			<img src="../logo.png" alt="Loxja Cafe" class="brand-mark"><span>Loxja <small>cafe</small></span></a>
		<section class="auth-card" aria-labelledby="signup-title">
			<p class="eyebrow">Seu cafe, do seu jeito</p>
			<h1 id="signup-title">Crie sua conta</h1>
			<p class="intro">Faca parte da nossa mesa e receba novidades fresquinhas.</p>
			<form action="../../Src/registerProcess.php" method="POST">
				<label for="nome">Nome</label>
				<input id="nome" name="nome" type="text" autocomplete="name" required>
				<label for="email">E-mail</label>
				<input id="email" name="email" type="email" autocomplete="email" required>
				<label for="senha">Senha</label>
				<input id="senha" name="senha" type="password" autocomplete="new-password" required>
				<button type="submit">Criar conta</button>
			</form>
			<p class="switch-page">Ja tem uma conta? <a href="../Login/index.php">Faca login</a></p>
			<a class="back-link" href="../index.php">&larr; Voltar para a loja</a>
		</section>
	</main>
</body>
</html>
