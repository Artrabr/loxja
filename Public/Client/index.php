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

$tema  = $_COOKIE['tema'] ?? 'light';
$error = $_GET['error'] ?? null;


    require_once __DIR__ . "/../../Src/Class/Client/Client.php";
    require_once __DIR__ . "/../../bootstrap.php";
    require_once __DIR__ . "/../theme-toggle.php";
    $tema = currentTheme();

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
<<<<<<< HEAD
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema) ?>">
=======
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
>>>>>>> 2b7e666df98d8bf05cabedbc0ad0678266aed534
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do cliente | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
    <link href="client.css" rel="stylesheet">
</head>
<body class="client-page">
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegação principal">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span>
            </a>
<<<<<<< HEAD
            <a href="../profile/index.php">Perfil</a>
            <a href="../Client/index.php">Informações Técnicas</a>
            <a href="../salesReport/index.php">Relatório de vendas</a>
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
=======
            <ul class="nav-links">
                <li><a href="../profile/index.php">Perfil</a></li>
                <li><a href="../Client/index.php" aria-current="page">Informações técnicas</a></li>
                <li><a href="../salesReport/index.php">Relatório de vendas</a></li>
            </ul>
            <?php renderThemeToggle($tema); ?>
>>>>>>> 2b7e666df98d8bf05cabedbc0ad0678266aed534
        </nav>
    </header>
    <main>
         
        <section>
            <h1>Bem-vindo à área do cliente</h1>
            <p>Esta é a área exclusiva para clientes cadastrados</p>
        </section>

        
        <section>
            <h2>Informações de envio</h2>
            <p>Aqui você pode visualizar e atualizar suas informações de envio.</p>

            <?php if (isset($_GET['CAPsuccess']) && $_GET['CAPsuccess'] === 'true'): ?>
                <p class="msg-success">Dados atualizados com sucesso!</p>
            <?php endif; ?>

            <?php if (isset($_GET['CAPerror'])): ?>
                <p class="msg-error">Não foi possível atualizar os dados. Verifique os campos e tente novamente.</p>
            <?php endif; ?>

            <!-- NÃO MECHE NESSA MERDA PELO AMOR DE DEUS Formulário para atualizar informações de envio -->
            <?php
                $pdo = Connection::conectar();
                $id = $_SESSION['client_object']->getId();
                $clientAddressDB = new ClientAddressDB($pdo);
                $client = $clientAddressDB->getAddressByClientID($id)
                    ?? new ClientAddress($id, 0, '', '', '', '', '', 0);
                $pdo = null; 
            ?>
            <div>
                
                <form action="../../Src/clientAddressProcess.php" method="POST">
                    <label for="cep">CEP</label>
                    <input type="text" id="cep" name="cep" 
                    <?php if($client->getCep()): ?>
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
            <span>Loxja <small>café</small></span>
        </a>
        <p>&copy; 2026 Loxja Café. Todos os direitos reservados.</p>
        <a href="#inicio" class="back-top" aria-label="Voltar ao inicio">&uarr;</a>
    </footer>
</body>
</html>