<?php
////

require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../bootstrap.php";
require_once __DIR__ . "/../../Src/Class/ShoppingCart/ShoppingCartDB.php";


if (isset($_SESSION['cliente_objeto'])) {
    $id = $_SESSION['cliente_objeto']->getId();
} else {
    header("Location: ../Login/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['quantities']) || !isset($_POST['item_ids'])) {
        exit;
    }

    if (!is_array($_POST['quantities']) || !is_array($_POST['item_ids'])) {
        exit;
    }

    if (count($_POST['quantities']) != count($_POST['item_ids'])) {
        exit;
    }



    $pdo = Connection::conectar();
    $ShoppingCartDB = new ShoppingCartDB($pdo);

    $shoppingCart = new ShoppingCart();

    foreach ($_POST['quantities'] as $index => $quantities) {
        $item_id = $_POST['item_ids'][$index];

        $shoppingCart->addProductById($item_id, $quantities);
    }

    $ShoppingCartDB->setShoppingCartByClientId($id, $shoppingCart);
}

////

require_once __DIR__ . "/../../Src/Connection.php";
require_once __DIR__ . "/../../Src/Class/ShoppingCart/ShoppingCartDB.php";

$pdo = Connection::conectar();
$ShoppingCartDB = new ShoppingCartDB($pdo);

$shoppingCart = $ShoppingCartDB->getShoppingCartByClientId($id);

$products = $shoppingCart->getProducts();

foreach ($products as $product) {
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Carrinho</title>
        <link href="../MVP.css" rel="stylesheet">
        <link href="ShoppingCart.css" rel="stylesheet">
    </head>
    <body class="scroll">

        <section>

            <form class="shopping_cart" id="shopping_cart" method="post" action="">

                <?php foreach ($products as $product) :
                    {
                    $item = $product->getProduct();
                    } ?>
                    <div class="cart_item_wrapper">

                        <!-- todo: colocar imagem aqui -->
                        <div class="cart_item_info">
                            <img src="./no_image_found.png" alt="imagem do produto não encontrada">
                        <p><?= htmlspecialchars($item->getName()); ?></p>
                        <p class="price"><b><span class="price_full"><?= htmlspecialchars(number_format($item->getPrice() * $product->getAmount(), 2))?></span> R$</b></p>
                        </div>

                        <div class="cart_item_actions">

                            <div class="increase_decrease">

                            <button type="button" class="increase" estoque="<?= htmlspecialchars($item->getAmountAvailable())?>" price="<?= number_format($item->getPrice(), 2) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-up preview-icon"><path d="m18 15-6-6-6 6"/></svg>
                                </button>

                            <input type="number" name="quantities[]" class="amount_of_products" value="<?= htmlspecialchars($product->getAmount()) ?>" min="0"></input>
                            <input type="text" name="item_ids[]" class="id_of_products" value="<?= htmlspecialchars($item->getId())?>" hidden></input>

                                <button type="button" class="decrease">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down preview-icon"><path d="m6 9 6 6 6-6"/></svg>
                                </button>

                            </div>

                            <button type="button" class="remove">
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
            <aside class="resume">
                <div class="resume_text_group">
                    <h1>Loxja</h1>
                    <h2>Preço final: <span id="preco_final"></span> R$</h2>
                    <button type="submit" form="shopping_cart">Comprar</button>
                </div>
            </aside>

        </section>

        <script src="script.js"></script>
    </body>
</html>
