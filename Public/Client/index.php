<?php
    session_start();
    if (!isset($_SESSION['client_object'])) {
        header("Location: login.php?error=not_logged_in");
        exit();
    }
    require_once __DIR__ . "/../../Src/Connection.php";

    $cliente = $_SESSION["client_object"];
    $id = $cliente->getId();
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

                

            //===================================
                if ($shipping_info):
            ?>
            <div>
                <form action="update_shipping.php" method="post">
                    <label for="address">Endereço</label>
                    <input type="text" id="address" name="address" placeholder=<?php?>"
                    
                    Digite seu endereço
                    
                    " required>
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" placeholder="
                    
                    Digite sua cidade
                    
                    " required>
                    <label for="state">Estado</label>
                    <input type="text" id="state" name="state" placeholder="
                    
                    Digite seu estado
                    
                    "  required>
                    <label for="zip">CEP</label>
                    <input type="text" id="zip" name="zip" placeholder="
                    
                    Digite seu CEP
                    
                    " required>
                    <button type="submit">Atualizar informações</button>
                </form>
            </div>
            <?php
                endif;
            ?>
        </section>
    </main>
</body>
</html>