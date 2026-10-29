<?php
require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../theme-toggle.php';
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/SaleReport/SaleReportDB.php';
require_once __DIR__ . '/../../Src/Class/SaleReport/PaymentMethod.php';

$tema = currentTheme();
requireAdminAccess();

$pdo = Connection::conectar();
$saleReportDB = new SaleReportDB($pdo);
$summary = $saleReportDB->getAdminSummary();
$paymentStats = $saleReportDB->getAdminPaymentMethodStats();
$topProducts = $saleReportDB->getAdminTopProducts();
$recentSales = $saleReportDB->getAdminRecentSales();
$pdo = null;

$formatCurrency = static function ($amount): string {
    return 'R$ ' . number_format((float) $amount, 2, ',', '.');
};
$maxPaymentSales = 0;
foreach ($paymentStats as $paymentStat) {
    $maxPaymentSales = max($maxPaymentSales, (int) $paymentStat['sale_count']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel de administração | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../theme.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
  </head>
  <body class="admin-page">

    <?php renderAdminLayoutStart($tema, 'dashboard'); ?>
    <p class="admin-eyebrow">Visão geral</p>
    <h1>Relatórios de vendas</h1>

    <section class="admin-metrics" aria-label="Resumo das vendas">
      <article class="admin-metric-card">
        <p>Renda total</p>
        <strong><?= htmlspecialchars($formatCurrency($summary['revenue']), ENT_QUOTES, 'UTF-8') ?></strong>
        <small><?= htmlspecialchars($formatCurrency($summary['revenue_last_30_days']), ENT_QUOTES, 'UTF-8') ?> nos últimos 30 dias</small>
      </article>
      <article class="admin-metric-card">
        <p>Vendas registradas</p>
        <strong><?= number_format((int) $summary['sale_count'], 0, ',', '.') ?></strong>
        <small><?= number_format((int) $summary['sales_last_30_days'], 0, ',', '.') ?> nos últimos 30 dias</small>
      </article>
      <article class="admin-metric-card">
        <p>Itens vendidos</p>
        <strong><?= number_format((int) $summary['items_sold'], 0, ',', '.') ?></strong>
        <small>Em todas as vendas registradas</small>
      </article>
      <article class="admin-metric-card">
        <p>Clientes compradores</p>
        <strong><?= number_format((int) $summary['customer_count'], 0, ',', '.') ?></strong>
        <small>Clientes únicos</small>
      </article>
    </section>

    <section class="admin-report-grid" aria-label="Detalhes das vendas">
      <article class="admin-report-card">
        <h2>Métodos de pagamento</h2>
        <?php if ($paymentStats === []): ?>
          <p class="admin-empty-state">Ainda não há vendas registradas.</p>
        <?php else: ?>
          <ul class="admin-stat-list">
            <?php foreach ($paymentStats as $paymentStat):
                $paymentCount = (int) $paymentStat['sale_count'];
                $paymentPercent = $maxPaymentSales > 0 ? (int) round($paymentCount / $maxPaymentSales * 100) : 0;
            ?>
              <li>
                <div class="admin-stat-heading">
                  <strong><?= htmlspecialchars(PaymentMethod::labelFor($paymentStat['method']), ENT_QUOTES, 'UTF-8') ?></strong>
                  <span><?= number_format($paymentCount, 0, ',', '.') ?> vendas</span>
                </div>
                <div class="admin-stat-track" aria-hidden="true">
                  <span style="width: <?= $paymentPercent ?>%"></span>
                </div>
                <small><?= htmlspecialchars($formatCurrency($paymentStat['revenue']), ENT_QUOTES, 'UTF-8') ?> em vendas</small>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </article>

      <article class="admin-report-card">
        <h2>Produtos mais vendidos</h2>
        <?php if ($topProducts === []): ?>
          <p class="admin-empty-state">Ainda não há produtos vendidos.</p>
        <?php else: ?>
          <ol class="admin-product-ranking">
            <?php foreach ($topProducts as $product): ?>
              <li>
                <span>
                  <strong><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                  <small><?= number_format((int) $product['items_sold'], 0, ',', '.') ?> itens vendidos</small>
                </span>
                <strong><?= htmlspecialchars($formatCurrency($product['revenue']), ENT_QUOTES, 'UTF-8') ?></strong>
              </li>
            <?php endforeach; ?>
          </ol>
        <?php endif; ?>
      </article>
    </section>

    <section class="admin-recent-sales" aria-labelledby="admin-recent-sales-title">
      <div class="admin-section-heading">
        <div>
          <p class="admin-eyebrow">Atividade</p>
          <h2 id="admin-recent-sales-title">Vendas mais recentes</h2>
        </div>
      </div>
      <?php if ($recentSales === []): ?>
        <p class="admin-empty-state">Ainda não há vendas registradas.</p>
      <?php else: ?>
        <div class="admin-table-wrap">
          <table class="admin-sales-table">
            <thead>
              <tr>
                <th scope="col">Cliente</th>
                <th scope="col">Produto</th>
                <th scope="col">Quantidade</th>
                <th scope="col">Pagamento</th>
                <th scope="col">Total</th>
                <th scope="col">Data</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recentSales as $sale): ?>
                <tr>
                  <td><?= htmlspecialchars($sale['customer_name'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($sale['product_name'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= number_format((int) $sale['amount'], 0, ',', '.') ?></td>
                  <td><?= htmlspecialchars(PaymentMethod::labelFor($sale['method']), ENT_QUOTES, 'UTF-8') ?></td>
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
