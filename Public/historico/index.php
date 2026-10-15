<?php
    require_once __DIR__ . 'bootstrap.php'
    if ($error === 'not_logged_in') :
?>
    <section class="auth-card" aria-labelledby="login-title">
        <div class="popup">
            <div class="popup-box">
                <a href="index.php" class="fechar"><i class="fa-regular fa-circle-xmark"></i></a>
                <h2>NÃO LOGADO</h2>
                <p>
                    Você precisa estar logado para acessar essa página
                </p>
            </div>
        </div>
    </section>
    <?php else :?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historico</title>
</head>
<body>
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegacao principal">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Cafe, inicio">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>cafe</small></span>
            </a>
            <a href="../profile/index.php">Perfil</a>
            <a href="../Client/index.php">Informações Técnicas</a>
        </nav>
    </header>
    <?php
        require_once __DIR__ . '/Class/Connetion.php'
        foreach
    ?>
    <div>
        <h1>Historico</h1>
        <table>
            <th>
                <td>ID venda</td>
                <td>Usuario</td>
                <td>Produtos</td>
                <td>Forma de pagamento</td>
                <td>Valor total</td>
            </th>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</body>
</html>