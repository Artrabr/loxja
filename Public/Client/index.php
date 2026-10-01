<?php
    require_once __DIR__ . "/../../Src/Class/Client/Client.php";
    session_start();

    if (!isset($_SESSION['client_object'])) {
        header("Location: ../Login/index.php?error=not_logged_in");
        exit();
    }
    require_once __DIR__ . "/../../Src/Connection.php";
    require_once __DIR__ . "/../../Src/Class/ClientAddress/ClientAddressDB.php";

    $cliente = $_SESSION["client_object"];
    $id = $cliente->getId();
    $pdo = Connection::conectar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area do cliente</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
    <link href="client.css" rel="stylesheet">
</head>
<body class="client-page">
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegacao principal">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Cafe, inicio">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>cafe</small></span>
            </a>
            <ul class="nav-links">
                <li><a href="../index.php#inicio">Inicio</a></li>
                <li><a href="../index.php#produtos">Produtos</a></li>
                <li><a href="../index.php#sobre">Sobre</a></li>
                <li><a href="../index.php#contato">Contato</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h1>Bem-vindo à área do cliente!</h1>
            <p>Esta é a área exclusiva para clientes cadastrados</p>
        </section>
        <section>
            <h2>Informações de envio</h2>
            <p>Aqui você pode visualizar e atualizar suas informações de envio.</p>
            <!-- NÃO MECHE NESSA MERDA PELO AMOR DE DEUS Formulário para atualizar informações de envio -->
            <?php
                $id = $_SESSION['client_object']->getId();
            ?>
            <div>
                <form action="../../Src/ClientAddressProcess.php" method="POST">
                    <input type="hidden" id="id" name="id" value="<?=$id?>" required>
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" required>
                    <label for="road">Rua/Avenida</label>
                    <input type="text" id="road" name="road" required>
                    <label for="number">Número</label>
                    <input type="text" id="number" name="number" required>
                    <label for="neighborhood">Bairro</label>
                    <input type="text" id="neighborhood" name="neighborhood" required>
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" required>
                    <label for="state">Estado</label>
                    <input type="text" id="state" name="state" required>
                    <label for="country">País</label>
                    <input type="text" id="country" name="country" required>
                    <button type="submit">Atualizar informações</button>
                </form>
            </div>
        <!----------------------------------  NAO OUSE TOCAR NO CODIGO A CIMA -------------------------->
        </section>
    </main>
    <footer>
        <a class="brand footer-brand" href="../index.php#inicio">
            <span class="brand-mark">
                <img src="../logo.png" alt="" class="brand-mark">
            </span>
            <span>Loxja <small>cafe</small></span>
        </a>
        <p>&copy; 2026 Loxja Cafe. Todos os direitos reservados.</p>
        <a href="#inicio" class="back-top" aria-label="Voltar ao inicio">&uarr;</a>
    </footer>
</body>
</html>