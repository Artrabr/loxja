<?php
    session_start();
    if (!isset($_SESSION['client_object'])) {
        header("Location: login.php?error=not_logged_in");
        exit();
    }
    require_once __DIR__ . "/../../Src/Connection.php";
    require_once __DIR__ . "/../../Src/Class/ClientAddress/ClientAddressDB.php";

    $cliente = $_SESSION["client_object"];
    $id = $cliente->getId();
    $pdo = Connection::conectar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area do cliente</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
</head>
<body>
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegacao principal">
            <a class="brand" href="#inicio" aria-label="Loxja Cafe, inicio"><span class="brand-mark">L</span><span>Loxja <small>cafe</small></span></a>
            <ul class="nav-links">
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#produtos">Produtos</a></li>
                <li><a href="#sobre">Sobre</a></li>
               <li><a href="#contato">Contato</a></li>
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
            <?php

                $clientAddressDB = new ClientAddressDB($pdo);
                $ClientLocateData = $clientAddressDB->getAddressByClientID($id);

                $CEP          = $ClientLocateData?->getCEP() ?? '';
                $road         = $ClientLocateData?->getRoad() ?? '';
                $number       = $ClientLocateData?->getNumber() ?? '';
                $neighborhood = $ClientLocateData?->getNeighborhood() ?? '';
                $city         = $ClientLocateData?->getCity() ?? '';
                $state        = $ClientLocateData?->getState() ?? '';
                $contry       = $ClientLocateData?->getCountry() ?? '';
                $fullAddress  = $ClientLocateData?->getFull() ?? '';
            ?>
            <div>
                <form action="<?//enviar os dados à ClientAddress?>" method="post">
                    <label for="contry">Pais</label>
                    <input type="text" id="contry" name="contry" value="<?=htmlspecialchars($contry)?>" required>
                    <label for="state">Estado</label>
                    <input type="text" id="state" name="state" value="<?=htmlspecialchars($state)?>" required>
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" value="<?=htmlspecialchars($city)?>" required>
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" value="<?=htmlspecialchars($CEP)?>" required>
                    <label for="address">Endereço</label>
                    <input type="text" id="address" name="address" value="<?=htmlspecialchars($fullAddress)?>" required>
                    <button type="submit">Atualizar informações</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>