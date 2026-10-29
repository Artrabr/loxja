<?php
require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../theme-toggle.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/SaleReport/PaymentMethod.php';

$tema = currentTheme();
requireAdminAccess();

$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$startDate = is_string($startDate) ? trim($startDate) : '';
$endDate = is_string($endDate) ? trim($endDate) : '';
$dateError = '';
$sales = [];

$isValidDate = static function (string $date): bool {
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/D', $date)) {
        return false;
    }

    [$year, $month, $day] = array_map('intval', explode('-', $date));
    return checkdate($month, $day, $year);
};

if (($startDate !== '' && !$isValidDate($startDate))
    || ($endDate !== '' && !$isValidDate($endDate))) {
    $dateError = 'Informe datas válidas para filtrar as vendas.';
} elseif ($startDate !== '' && $endDate !== '' && $startDate > $endDate) {
    $dateError = 'A data inicial não pode ser posterior à data final.';
} else {
    $conditions = [];
    $parameters = [];

    if ($startDate !== '') {
        $conditions[] = 'sr.saleReport_created_at >= :start_date';
        $parameters[':start_date'] = $startDate;
    }
    if ($endDate !== '') {
        $conditions[] = 'sr.saleReport_created_at < DATE_ADD(:end_date, INTERVAL 1 DAY)';
        $parameters[':end_date'] = $endDate;
    }

    $sql = "
        SELECT
            sr.saleReport_id AS sale_id,
            c.clt_name AS customer_name,
            p.pdt_name AS product_name,
            sr.saleReport_amount AS amount,
            sr.saleReport_method AS method,
            sr.saleReport_price AS unit_price,
            sr.saleReport_price * sr.saleReport_amount AS total,
            sr.saleReport_created_at AS created_at
        FROM saleReport sr
        INNER JOIN Client c ON c.clt_id = sr.clt_id
        INNER JOIN Product p ON p.pdt_id = sr.pdt_id
    ";

    if ($conditions !== []) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $sql .= ' ORDER BY sr.saleReport_created_at DESC, sr.saleReport_id DESC';

    $pdo = Connection::conectar();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($parameters);
    $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pdo = null;
}

$formatCurrency = static function ($amount): string {
    return 'R$ ' . number_format((float) $amount, 2, ',', '.');
};
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relatório de vendas | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../theme.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css">
</head>
<body class="admin-page">
    <?php renderAdminLayoutStart($tema, 'sales-report'); ?>
        <p class="admin-eyebrow">Administração</p>
        <h1>Relatório de vendas</h1>

        <section class="admin-recent-sales" aria-labelledby="all-sales-title">
            <div class="admin-section-heading">
                <h2 id="all-sales-title">Todas as vendas (<?= number_format(count($sales), 0, ',', '.') ?>)</h2>
            </div>

            <form class="admin-form admin-sales-filter" method="get" action="sales-report.php" aria-label="Filtrar vendas por data">
                <div class="admin-sales-filter-field">
                    <label for="start-date">Data inicial</label>
                    <input id="start-date" name="start_date" type="date"
                           value="<?= htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="admin-sales-filter-field">
                    <label for="end-date">Data final</label>
                    <input id="end-date" name="end_date" type="date"
                           value="<?= htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8') ?>">
                </div>
                <div class="admin-sales-filter-actions">
                    <button type="submit">Filtrar</button>
                    <a class="admin-clear-filter" href="sales-report.php">
                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                        <span>Limpar filtros</span>
                    </a>
                </div>
            </form>

            <?php if ($dateError !== ''): ?>
                <p class="admin-empty-state" role="alert"><?= htmlspecialchars($dateError, ENT_QUOTES, 'UTF-8') ?></p>
            <?php elseif ($sales === []): ?>
                <p class="admin-empty-state">Nenhuma venda encontrada para o período selecionado.</p>
            <?php else: ?>
                <div class="admin-table-wrap">
                    <table class="admin-sales-table">
                        <thead>
                            <tr>
                                <th scope="col">Venda</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Produto</th>
                                <th scope="col">Quantidade</th>
                                <th scope="col">Pagamento</th>
                                <th scope="col">Valor unitário</th>
                                <th scope="col">Total</th>
                                <th scope="col">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td><?= htmlspecialchars((string) $sale['sale_id'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($sale['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($sale['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= number_format((int) $sale['amount'], 0, ',', '.') ?></td>
                                    <td><?= htmlspecialchars(PaymentMethod::labelFor($sale['method']), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($formatCurrency($sale['unit_price']), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($formatCurrency($sale['total']), ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($sale['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    <?php renderAdminLayoutEnd(); ?>
</body>
</html>
