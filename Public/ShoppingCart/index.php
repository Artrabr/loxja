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

////

require_once __DIR__ . "/../../Src/Class/Client/Client.php";
require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../bootstrap.php";
require_once __DIR__ . "/../theme-toggle.php";
$tema = currentTheme();
require_once __DIR__ . "/../../Src/Class/ShoppingCart/ShoppingCartDB.php";
require_once __DIR__ . "/../../Src/Class/Product/ProductDB.php";
require_once __DIR__ . "/../../Src/Class/Product/Product.php";


if (isset($_SESSION['client_object']) && !is_null($_SESSION['client_object'])) {
    $id = $_SESSION['client_object']->getId();
} else {
    header("Location: ../Login/index.php");
    exit;
}


$is_safe_post = true;
while ($is_safe_post) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['quantities']) || !isset($_POST['item_ids'])) {
            $is_safe_post = false;
            break;
        }

        if (!is_array($_POST['quantities']) || !is_array($_POST['item_ids'])) {
            $is_safe_post = false;
            break;
        }

        if (count($_POST['quantities']) != count($_POST['item_ids'])) {
            $is_safe_post = false;
            break;
        }
        break;
    }
    break;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_safe_post) {
    $pdo = Connection::conectar();
    $ShoppingCartDB = new ShoppingCartDB($pdo);

    $shoppingCart = new ShoppingCart();

    foreach ($_POST['quantities'] as $index => $quantities) {
        $item_id = $_POST['item_ids'][$index];

        if ($quantities > 0) {
            $shoppingCart->addProductById($item_id, $quantities);
        }
    }

    $ShoppingCartDB->setShoppingCartByClientId($id, $shoppingCart);
}

////

require_once __DIR__ . "/../../Src/Class/ShoppingCart/ShoppingCartDB.php";

$pdo = Connection::conectar();
$ShoppingCartDB = new ShoppingCartDB($pdo);

$shoppingCart = $ShoppingCartDB->getShoppingCartByClientId($id);

$hasShoppingCart = false;
if (!is_null($shoppingCart)) {
    $products = $shoppingCart->getProducts();
    $hasShoppingCart = true;
} else {
    $hasShoppingCart = false;
}

?>

<!DOCTYPE html>
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema, ENT_QUOTES, 'UTF-8') ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Carrinho | Loxja Café</title>
        <link href="../MVP.css" rel="stylesheet">
        <link href="../theme.css" rel="stylesheet">
        <link href="../Finalization/checkout.css" rel="stylesheet">
        <link href="ShoppingCart.css" rel="stylesheet">
        
    </head>
        <body class="checkout-page cart-page">
            <header class="site-header" id="inicio">
                <nav class="topbar" aria-label="Navegação principal">
                    <a class="brand" href="../index.php#inicio" aria-label="Loxja Café, início">
                        <img src="../logo.png" alt="" class="brand-mark">
                        <span>Loxja <small>café</small></span>
                    </a>
                    <ul class="nav-links">
                        <li><a href="../index.php#produtos">Produtos</a></li>
                        <li><a href="../profile/index.php">Perfil</a></li>
                        <li><a href="index.php" aria-current="page">Carrinho</a></li>
                    </ul>
                    <?php renderThemeToggle($tema); ?>
                </nav>
            </header>

            <?php if ($hasShoppingCart) : ?>
                <main class="cart-page-main">
                    <h1>Seu carrinho</h1>
                    <p class="checkout-subtitle">Revise os itens e as quantidades antes de finalizar sua compra.</p>

                    <form class="shopping_cart checkout-card" id="shopping_cart" method="post" action="../Finalization">
                        <h2>Itens do carrinho</h2>
                        <?php foreach ($products as $product) :
                            {
                            $item = $product->getProduct();
                            } ?>
                            <div class="cart_item_wrapper">

                                <!-- todo: colocar imagem aqui -->
                                <div class="cart_item_info">
                                <img src="<?= $item->getImage() ?>" alt="<?= htmlspecialchars($item->getName())?>">
                                <p><?= htmlspecialchars($item->getName()); ?></p>
                                <p class="price"><b>R$ <span class="price_full"><?= htmlspecialchars(number_format($item->getPrice() * $product->getAmount(), 2, '.', ''))?></span></b></p>
                                </div>

                                <div class="cart_item_actions">

                                    <div class="increase_decrease">

                                        <button type="button" class="increase" estoque="<?= htmlspecialchars($item->getAmountAvailable())?>" price="<?= number_format($item->getPrice(), 2) ?>" aria-label="aumenta a quantidade de <?= $item->getName()?> no seu carrinho">
                                            <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up preview-icon"><path d="m18 15-6-6-6 6"/></svg>
                                        </button>

                                    <input type="number" name="quantities[]" class="amount_of_products" value="<?= htmlspecialchars($product->getAmount()) ?>" min="0" aria-label="quantidade do produto <?= $item->getName()?> no seu carrinho">
                                    <input type="text" name="item_ids[]" class="id_of_products" value="<?= htmlspecialchars($item->getId())?>" hidden>

                                        <button type="button" class="decrease" aria-label="diminui a quantidade de <?= $item->getName()?> no seu carrinho">
                                            <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down preview-icon"><path d="m6 9 6 6 6-6"/></svg>
                                        </button>

                                    </div>

                                    <button type="button" class="remove" aria-label="remove o produto <?= $item->getName()?> do seu carrinho">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash preview-icon"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>

                                </div>

                            </div>
                        <?php endforeach?>

                    </form>

                    <!-- DONE: change this so it goes beneath instead of in the side -->
                    <!-- TODO: Adicionar link de href na imagem que te leva para a pagina do produto -->
                    <!-- DONE: adicionar animações de hover -->
                    <!-- DONE: limitar o numero de compra para o numero de estoque -->
                    <!-- TODO: Adicionar o preço do produto -->
                    <aside class="resume checkout-card">
                        <div class="resume_text_group">
                            <h2>Resumo do pedido</h2>
                            <div class="checkout-total-row">
                                <span>Total</span>
                                <span>R$ <span id="preco_final">0.00</span></span>
                            </div>
                            <button type="submit" class="checkout-submit" form="shopping_cart">Finalizar compra</button>
                        </div>
                    </aside>

                </main>

                <script src="script.js"></script>
            <?php else : ?>
                <main class="cart-page-main">
                    <h1>Seu carrinho</h1>
                    <p class="checkout-subtitle">Revise os itens e as quantidades antes de finalizar sua compra.</p>

                    <section class="checkout-card cart-empty">
                        <h2>Seu carrinho está vazio</h2>
                        <p class="checkout-empty">Escolha seus cafés e doces favoritos para começar.</p>
                        <a class="checkout-submit cart-continue" href="../index.php#produtos">Ver produtos</a>
                    </section>

                </main>

            <?php endif; ?>
            <footer>
                <a class="brand footer-brand" href="../index.php#inicio">
                    <span class="brand-mark"><img src="../logo.png" alt="" class="brand-mark"></span>
                    <span>Loxja <small>café</small></span>
                </a>
                <p>&copy; 2026 Loxja Café. Todos os direitos reservados.</p>
                <a href="#inicio" class="back-top" aria-label="Voltar ao início">&uarr;</a>
            </footer>
        </body>
</html>
