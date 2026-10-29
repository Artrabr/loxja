<?php
require_once __DIR__ . "/../../Src/Class/Client/Client.php";
require_once __DIR__ . "/../../bootstrap.php";

if (!isset($_SESSION['client_object'])) {
    header("Location: ../Login/index.php?error=not_logged_in");
    exit();
}

require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../Src/Class/SaleReport/SaleReport.php";
require_once __DIR__ . "/../../Src/Class/SaleReport/SaleReportDB.php";

$client = $_SESSION['client_object'];
$pdo = Connection::conectar();
$saleReportDB = new SaleReportDB($pdo);
$reports = $saleReportDB->getReportsByClientId($client->getId());
$pdo = null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de vendas | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
    <link href="salesReport.css" rel="stylesheet">
</head>
<body>
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegação da área do cliente">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span>
            </a>
            <ul class="nav-links">
                <li><a href="../profile/index.php">Perfil</a></li>
                <li><a href="../Client/index.php">Informações técnicas</a></li>
                <li><a href="index.php" aria-current="page">Relatório de vendas</a></li>
            </ul>
        </nav>
    </header>
    <main class="sales-report-page">
        <h1>Relatório de vendas</h1>
        <p>Confira o histórico das suas compras.</p>

        <?php if ($reports === []): ?>
            <p>Você ainda não tem compras registradas.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th scope="col">Venda</th>
                        <th scope="col">Produto</th>
                        <th scope="col">Quantidade</th>
                        <th scope="col">Forma de pagamento</th>
                        <th scope="col">Valor registrado</th>
                        <th scope="col">Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $report): ?>
                        <tr>
                            <td><?= htmlspecialchars((string) $report->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($report->getProductName() ?? (string) $report->getProductId(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string) $report->getAmount(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($report->getMethod(), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>R$ <?= htmlspecialchars(number_format($report->getPrice(), 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($report->getDate())), ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
                        
        <p><a href="../index.php">Voltar para a loja</a></p>
    </main>
</body>
</html>
