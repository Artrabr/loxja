<?php
    require_once __DIR__ . "/../../Src/Class/Client/Client.php";
    require_once __DIR__ . "/../../bootstrap.php";

    if (!isset($_SESSION['client_object'])) {
        header("Location: ../Login/index.php?error=not_logged_in");
        exit();
    }

    require_once __DIR__ . "/../../Src/Connection.php";
    require_once __DIR__ . "/../../Src/Class/ClientAddress/ClientAddressDB.php";

    $cliente_obj = $_SESSION["client_object"];
    $id = $cliente_obj->getId();
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
                $pdo = Connection::conectar();
                $id = $_SESSION['client_object']->getId();
                $clientAddressDB = new ClientAddressDB($pdo);
                $client = $clientAddressDB->getAddressByClientID($id)
                    ?? new ClientAddress($id, 0, '', '', '', '', '', 0);
                $pdo = null; // Fechar a conexão com o banco de dados
            ?>
            <div>
                <form action="../../Src/ClientAddressProcess.php" method="POST">
                    <input type="hidden" id="id" name="id" value="<?=$id?>" required>
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" 
                    <?php if($client->getCep()): ?>-
                        value="<?= htmlspecialchars($client->getCep()) ?>"
                    <?php endif; ?>
                     required>
                    <label for="road">Rua/Avenida</label>
                    <input type="text" id="road" name="road" 
                    <?php if($client->getRoad()): ?>
                        value="<?= htmlspecialchars($client->getRoad()) ?>"
                    <?php endif; ?>
                     required>
                    <label for="number">Número</label>
                    <input type="text" id="number" name="number" 
                    <?php if($client->getNumber()): ?>
                        value="<?= htmlspecialchars($client->getNumber()) ?>"
                    <?php endif; ?>
                     required>
                    <label for="neighborhood">Bairro</label>
                    <input type="text" id="neighborhood" name="neighborhood" 
                    <?php if($client->getNeighborhood()): ?>
                        value="<?= htmlspecialchars($client->getNeighborhood()) ?>"
                    <?php endif; ?>
                     required>
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" 
                    <?php if($client->getCity()): ?>
                        value="<?= htmlspecialchars($client->getCity()) ?>"
                    <?php endif; ?>
                     required>
                    <label for="state">Estado</label>
                    <input type="text" id="state" name="state" 
                    <?php if($client->getState()): ?>
                        value="<?= htmlspecialchars($client->getState()) ?>"
                    <?php endif; ?>
                     required>
                    <label for="country">País</label>
                    <input type="text" id="country" name="country" 
                    <?php if($client->getCountry()): ?>
                        value="<?= htmlspecialchars($client->getCountry()) ?>"
                    <?php endif; ?>
                     required>
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