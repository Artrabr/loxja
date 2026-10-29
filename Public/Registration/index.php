<?php
require_once __DIR__ . '/../theme-toggle.php';
$tema = currentTheme();
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema) ?>">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro | Loxja Café</title>
	<link rel="stylesheet" href="../theme.css">
	<link rel="stylesheet" href="register.css">
</head>
<body>
	<main class="auth-page">
		<?php renderThemeToggle($tema); ?>
		<a class="brand" href="../index.php">
			<img src="../logo.png" alt="" class="brand-mark"><span>Loxja <small>café</small></span></a>
		<section class="auth-card" aria-labelledby="signup-title">
			<p class="eyebrow">Seu café, do seu jeito</p>
			<h1 id="signup-title">Crie sua conta</h1>
			<p class="intro">Faça parte da nossa mesa e receba novidades fresquinhas.</p>
			<form action="../../Src/registerProcess.php" method="POST">
				<label for="nome">Nome</label>
				<input id="nome" name="nome" type="text" autocomplete="name" required>
				<label for="email">E-mail</label>
				<input id="email" name="email" type="email" autocomplete="email" required>
				<label for="senha">Senha</label>
				<input id="senha" name="senha" type="password" autocomplete="new-password" required>
				<button type="submit">Criar conta</button>
			</form>
			<p class="switch-page">Já tem uma conta? <a href="../Login/index.php">Faça login</a></p>
			<a class="back-link" href="../index.php">&larr; Voltar para a loja</a>
		</section>
	</main>
</body>
</html>
