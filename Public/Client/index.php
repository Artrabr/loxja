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
                /*
                $clientAddressDB = new ClientAddressDB($pdo);
                $ClientLocateData = $clientAddressDB->getAddressByClientID($id);

                $CEP          = $ClientLocateData?->getCEP() ?? '';
                $road         = $ClientLocateData?->getRoad() ?? '';
                $number       = $ClientLocateData?->getNumber() ?? '';
                $neighborhood = $ClientLocateData?->getNeighborhood() ?? '';
                $city         = $ClientLocateData?->getCity() ?? '';
                $state        = $ClientLocateData?->getState() ?? '';
                $country      = $ClientLocateData?->getCountry() ?? '';
                $fullAddress  = $ClientLocateData?->getFull() ?? '';*/
                $id = $_SESSION['client_object']->getId();
            ?>
            <div>
                <form action="../../Src/ClientAddressProcess.php" method="POST">
                    <input type="hidden" id="id" name="id" value="<?=$id?>">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep">
                    <label for="road">Rua/Avenida</label>
                    <input type="text" id="road" name="road">
                    <label for="number">Número</label>
                    <input type="text" id="number" name="number">
                    <label for="neighborhood">Bairro</label>
                    <input type="text" id="neighborhood" name="neighborhood">
                    <label for="state">Estado</label>
                    <input type="text" id="state" name="state">
                    <label for="country">País</label>
                    <input type="text" id="country" name="country">
                    <button type="submit">Atualizar informações</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>