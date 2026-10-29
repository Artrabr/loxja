<?php
require_once __DIR__ . "/../../Src/Class/Client/Client.php";
require_once __DIR__ . "/../../bootstrap.php";

if (!isset($_SESSION['client_object'])) {
    header("Location: ../Login/index.php?error=not_logged_in");
    exit();
}

require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../Src/Class/ShoppingCart/ShoppingCart.php";
require_once __DIR__ . "/../../Src/Class/ShoppingCart/ShoppingCartDB.php";
require_once __DIR__ . "/../../Src/Class/ClientAddress/ClientAddressDB.php";
require_once __DIR__ . "/../../Src/Class/SaleReport/SaleReportDB.php";
require_once __DIR__ . "/../../Src/Class/Product/ProductDB.php";

$client   = $_SESSION['client_object'];
$clientId = $client->getId();

$pdo = Connection::conectar();

$cartDB    = new ShoppingCartDB($pdo);
$addressDB = new ClientAddressDB($pdo);

$cart    = $cartDB->getShoppingCartByClientId($clientId);
$address = $addressDB->getAddressByClientID($clientId);

$paymentMethods = [
    'credit_card' => 'Cartão de Crédito',
    'debit_card'  => 'Cartão de Débito',
    'pix'         => 'Pix',
    'cash'        => 'Dinheiro',
    'boleto'      => 'Boleto',
];

$errors      = [];
$orderPlaced = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedMethod = trim((string) ($_POST['method'] ?? ''));

    if ($cart === null || empty($cart->getProducts())) {
        $errors[] = "Seu carrinho está vazio.";
    }

    if ($address === null) {
        $errors[] = "Você precisa registrar um endereço antes de confirmar a compra.";
    }

    if (!array_key_exists($selectedMethod, $paymentMethods)) {
        $errors[] = "Por favor escolha um método válido de pagamento.";
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            $reportDB  = new SaleReportDB($pdo);
            $productDB = new ProductDB($pdo);
            $now       = date('Y-m-d H:i:s');

            foreach ($cart->getProducts() as $item) {
                $product = $item->getProduct();
                $amount  = $item->getAmount();

                if ($amount <= 0) {
                    throw new RuntimeException("Invalid quantity for \"{$product->getName()}\".");
                }

                if ($product->getAmountAvailable() < $amount) {
                    throw new RuntimeException(sprintf(
                        'Not enough stock for "%s". Available: %d, requested: %d.',
                        $product->getName(),
                        $product->getAmountAvailable(),
                        $amount
                    ));
                }

                $reportDB->addSaleReport(
                    $clientId,
                    $product->getId(),
                    $selectedMethod,
                    $product->getPrice(),
                    $amount,
                    'completed',
                    $now
                );

                $productDB->decreaseStock($product->getId(), $amount);
            }

            $cartDB->setShoppingCartByClientId($clientId, new ShoppingCart());

            $pdo->commit();

            $orderPlaced = true;
            $cart        = null;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            error_log($e->getMessage());
            $errors[] = "Não conseguimos terminar sua compra: " . $e->getMessage();
        }
    }
}

$cartItems = $cart !== null ? $cart->getProducts() : [];
$total     = 0.0;
foreach ($cartItems as $item) {
    $total += $item->getProduct()->getPrice() * $item->getAmount();
}

$canSubmit = !$orderPlaced && !empty($cartItems) && $address !== null;

$pdo = null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar compra | Loxja Café</title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">
    <link href="checkout.css" rel="stylesheet">
</head>
<body class="checkout-page">
    <header class="site-header" id="inicio">
        <nav class="topbar" aria-label="Navegação principal">
            <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
                <img src="../logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span>
            </a>
            <ul class="nav-links">
                <li><a href="../index.php#produtos">Produtos</a></li>
                <li><a href="../index.php#sobre">Sobre</a></li>
                <li><a href="../index.php#contato">Contato</a></li>
            </ul>
            <?php
                if(isset($_SESSION['client_object'])){
            ?> 
                <a href="../profile/index.php">
                    <img src="<?= htmlspecialchars($_SESSION['client_object']->getImage(), ENT_QUOTES, 'UTF-8') ?>"
                         alt="Foto de perfil" class="profile-avatar">
                </a>
            <?php
                } else {
            ?>
                <a class="login-button" href="../Login/index.php">Entrar</a>
                <?php
            }
            ?>
        </nav>
    </header>

    <main>
        <h1>Finalização da compra</h1>
        <p class="checkout-subtitle">
            Revise seus itens, confirme o endereço, e escolha um método de pagamento.
        </p>

        <?php if ($orderPlaced): ?>
            <section class="checkout-success" role="status">
                <h2>Pedido feito com sucesso!</h2>
                <p>Obrigado por comprar na Loxja Café!</p>
                <p><a href="../index.php#inicio">Continuar comprando</a></p>
            </section>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="checkout-errors" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="checkout-layout">
                <section class="checkout-card checkout-cart">
                    <h2>Carrinho</h2>

                    <?php if (empty($cartItems)): ?>
                        <p class="checkout-empty">
                            Seu carrinho está vazio
                            <a href="../index.php#inicio">Voltar pra loja</a> e adicionar produtos.
                        </p>
                    <?php else: ?>
                        <ul class="checkout-items">
                            <?php foreach ($cartItems as $item): ?>
                                <?php
                                    $product  = $item->getProduct();
                                    $amount   = $item->getAmount();
                                    $subtotal = $product->getPrice() * $amount;
                                ?>
                                <li class="checkout-item">
                                    <span class="checkout-item-name"><?= htmlspecialchars($product->getName()) ?></span>
                                    <span class="checkout-item-qty">&times; <?= (int) $amount ?></span>
                                    <span class="checkout-item-subtotal">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="checkout-total-row">
                            <span>Total</span>
                            <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                </section>

                <div class="checkout-side">
                    <section class="checkout-card checkout-address">
                        <h2>Endereço de compra</h2>
                        <?php if ($address === null): ?>
                            <p class="checkout-empty">
                                Você não adicionou um endereço ainda...
                                <a href="../Client/index.php">Adicione um endereço</a> antes de confirmar.
                            </p>
                        <?php else: ?>
                            <p><?= htmlspecialchars($address->getFull()) ?></p>
                            <p><strong>CEP:</strong> <?= htmlspecialchars((string) $address->getCEP()) ?></p>
                            <p><a href="../Client/index.php">Editar endereço</a></p>
                        <?php endif; ?>
                    </section>

                    <section class="checkout-card checkout-payment">
                        <h2>Método de pagamento</h2>
                        <form method="POST" action="">
                            <?php foreach ($paymentMethods as $value => $label): ?>
                                <label class="checkout-method">
                                    <input type="radio" name="method" value="<?= htmlspecialchars($value) ?>" required>
                                    <span><?= htmlspecialchars($label) ?></span>
                                </label>
                            <?php endforeach; ?>

                            <button type="submit" class="checkout-submit" <?= $canSubmit ? '' : 'disabled' ?>>
                                Terminar compra
                            </button>
                        </form>
                    </section>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <a class="brand footer-brand" href="../index.php#inicio">
            <span class="brand-mark">
                <img src="../logo.png" alt="" class="brand-mark">
            </span>
            <span>Loxja <small>café</small></span>
        </a>
            <p>&copy; 2026 Loxja Café. Todos os direitos reservados.</p>
            <a href="#inicio" class="back-top" aria-label="Voltar ao início">&uarr;</a>
    </footer>
</body>
</html>