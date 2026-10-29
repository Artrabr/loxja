<?php
//atenção esse codigo foi vibe codado pq é so teste msm KSKSKSSKKSK
require_once __DIR__ . "/../../Connection.php";
require_once __DIR__ . "/../../Class/SaleReport/SaleReportDB.php";

$message = '';
$reports = [];
$orders = [];
$queryErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientId = filter_var($_POST['clientId'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $productId = filter_var($_POST['productId'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $amount = filter_var($_POST['amount'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $method = trim((string) ($_POST['method'] ?? ''));
    $priceInput = trim((string) ($_POST['price'] ?? ''));

    if ($clientId === false || $productId === false || $amount === false || $method === '' || strlen($method) > 20 || !is_numeric($priceInput) || (float) $priceInput < 0) {
        $message = 'Preencha os campos corretamente. IDs e quantidade devem ser inteiros positivos; método deve ter até 20 caracteres e preço não pode ser negativo.';
    } else {
        try {
            $db = new SaleReportDB(Connection::conectar());
            $added = $db->addSaleReport($clientId, $productId, $amount, $method, (float) $priceInput);

            if ($added) {
                $message = 'Venda adicionada com sucesso.';

                try {
                    $reports = $db->getReportsByClientId($clientId);
                } catch (PDOException $exception) {
                    $queryErrors[] = 'Erro em getReportsByClientId: ' . $exception->getMessage();
                }

                try {
                    $orders = $db->getOrders($clientId);
                } catch (PDOException $exception) {
                    $queryErrors[] = 'Erro em getOrders: ' . $exception->getMessage();
                }
            } else {
                $message = 'Não foi possível adicionar a venda.';
            }
        } catch (PDOException $exception) {
            $message = 'Erro ao inserir a venda: ' . $exception->getMessage();
        }
    }
}

?>

<?php if ($message !== ''): ?>
    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php foreach ($queryErrors as $queryError): ?>
    <p><?= htmlspecialchars($queryError, ENT_QUOTES, 'UTF-8') ?></p>
<?php endforeach; ?>

<form action="" method="post">
    cliente id
    <input type="number" name="clientId" min="1" required>
    <br>
    produto id
    <input type="number" name="productId" min="1" required>
    <br>
    quantidade
    <input type="number" name="amount" min="1" required>
    <br>
    metodo
    <input type="text" name="method" maxlength="20" required>
    <br>
    preco
    <input type="number" name="price" min="0" step="0.01" required>
    <br>
    <input type="submit" value="enviar">
</form>

<?php if ($reports !== []): ?>
    <h2>Relatórios do cliente (getReportsByClientId)</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Método</th>
            <th>Preço unitário</th>
            <th>Total</th>
            <th>Data</th>
        </tr>
        <?php foreach ($reports as $report): ?>
            <tr>
                <td><?= htmlspecialchars((string) $report->getId(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $report->getProductId(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $report->getAmount(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($report->getMethod(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $report->getPrice(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $report->getTotal(), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($report->getDate(), ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php if ($orders !== []): ?>
    <h2>Pedidos agrupados do cliente (getOrders)</h2>
    <table border="1">
        <tr>
            <th>Data</th>
            <th>Método</th>
            <th>Status</th>
            <th>Total</th>
            <th>Itens</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= htmlspecialchars((string) $order['full_date'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $order['saleReport_method'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $order['saleReport_status'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $order['order_total'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) $order['item_count'], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>