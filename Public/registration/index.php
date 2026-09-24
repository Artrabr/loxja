<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro | Loxja Cafe</title>
	<link rel="stylesheet" href="register.css">
</head>
<body>
	<main class="auth-page">
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
