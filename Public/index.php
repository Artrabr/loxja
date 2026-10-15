<!-- index.php (excerpt) -->
<?php
    require_once __DIR__ . "/../bootstrap.php";
    require_once __DIR__ . "/../Src/Connection.php";
    require_once __DIR__ . "/../Src/Class/Product/Product.php";
    require_once __DIR__ . "/../Src/Class/Product/ProductDB.php";
    require_once __DIR__ . "/../Src/Class/ShoppingCart/ShoppingCart.php";

    if (!isset($_SESSION['shopping_cart']) || !($_SESSION['shopping_cart'] instanceof ShoppingCart)) {
        $_SESSION['shopping_cart'] = new ShoppingCart();
    }

    $cart = $_SESSION['shopping_cart'];


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart_id'])) {
        $productId   = (int)$_POST['add_to_cart_id'];
        $returnQuery = (string)($_POST['return_query'] ?? '');
        $quantity    = max(1, (int)($_POST['add_to_cart_quantity'] ?? 1));

        try {
            $cart->addProductById($productId, $quantity);
            $_SESSION['shopping_cart'] = $cart;
            $_SESSION['cart_flash']    = 'Produto adicionado ao carrinho!';
        } catch (Throwable $e) {
            $_SESSION['cart_flash'] = 'Não foi possível adicionar o produto.';
        }

        $path     = strtok($_SERVER['REQUEST_URI'], '?');
        $location = $path . ($returnQuery !== '' ? '?' . $returnQuery : '') . '#detalhe-' . $productId;

        header('Location: ' . $location);
        exit;
    }

    $cartFlash = $_SESSION['cart_flash'] ?? null;
    unset($_SESSION['cart_flash']);

    $cartItemCount = 0;
    foreach ($cart->getProducts() as $item) {
        $cartItemCount += $item->getAmount();
    }

    $pdo = Connection::conectar();
    $db = new ProductDB($pdo);

    require_once __DIR__ . "/../Src/filtrationSetup.php";

    $search   = $search   ?? '';
    $category = $category ?? '';
    $minPrice = $minPrice ?? '';
    $maxPrice = $maxPrice ?? '';

    $perPage = 6;
    $page = (int)($_GET['page'] ?? 1);
    $totalProducts = count($filteredProducts);
    $totalPages = max(1, ceil($totalProducts / $perPage));

    $page = max(1, min($page, $totalPages));

    $offset = ($page - 1) * $perPage;
    $productsOnDisplay = array_slice($filteredProducts, $offset, $perPage);

    $firstShown = ($totalProducts === 0) ? 0 : $offset + 1;
    $lastShown  = min($offset + $perPage, $totalProducts);

    $pageWindow       = 3;
    $halfWindow = (int)floor($pageWindow / 2);
    $visiblePages     = min($pageWindow, $totalPages);
    $firstVisiblePage = max(1, min($page - $halfWindow, $totalPages - $visiblePages + 1));
    $lastVisiblePage = $firstVisiblePage + $visiblePages - 1;
    $hasHiddenPages = $visiblePages < $totalPages;
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Loxja Cafe: cafe especial, doces e momentos aconchegantes.">
        <title>Loxja Cafe | Seu momento favorito</title>
        <link href="MVP.css" rel="stylesheet">
        <link href="style_index.css" rel="stylesheet">
        <link href="style_index_exhibition.css" rel="stylesheet">
    </head> 
    
<!--===================[APAGAR]========================-->
    <a href="Administration/index.php" style='padding:5px; background-color:rgb(64, 171, 77);'>got to administration</a>  ||  <a href="Client/index.php" style='padding:5px; background-color:rgb(64, 171, 77);'>got to client area</a>
<!--===================[------]========================-->

    <body>
        <?php if ($cartFlash !== null): ?>
            <div role="status" aria-live="polite"
                class="cart-notification">
                <?= htmlspecialchars($cartFlash) ?>
            </div>
        <?php endif; ?>
        <header class="site-header" id="inicio">
            <nav class="topbar" aria-label="Navegacao principal">
                <a class="brand" href="#inicio" aria-label="Loxja Cafe, inicio">
                <img src="logo.png" alt="Loxja Cafe" class="brand-mark">
                <span>Loxja <small>cafe</small></span></a>
                <ul class="nav-links">
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#sobre">Sobre</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
                <?php
                    if(isset($_SESSION['client_object'])){
                ?> 
                    <a href="profile/index.php">
                        <img src="perfilsemfoto.png" alt="Loxja Cafe" class="brand-mark">
                    </a>
                    
                <?php //                       ^  trocar pela foto de perfil
                    } else {
                ?>
                    <a class='login-button' href='Login/index.php'>Login</a>
                    <?php
                }
                ?>
            </nav>
        </header>
        <main>
            <section class="hero" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow">Torra artesanal &bull; carinho em cada xicara</p>
                    <h1 id="hero-title">Um cafe quentinho para chamar de seu.</h1>
                    <p>Escolha seu momento favorito: aromas marcantes, doces delicados e aquele aconchego que cabe na rotina.</p>
                    <a class="primary-button" href="#produtos">Explorar sabores <span aria-hidden="true">&rarr;</span></a>
                </div>
                <div class="hero-art" role="img" aria-label="Xicara de cafe sobre uma mesa de madeira"></div>
            </section>
            <section class="products-section" id="produtos" aria-labelledby="products-title">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Feitos para desacelerar</p>
                        <h2 id="products-title">Nosso cardapio</h2>
                    </div>
                </div>
                <section class="search-strip" aria-label="Pesquisa e utilidades">
                <form class="search-form" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) ?>#produtos" method="get">
                    <label for="search">O que vai deixar seu dia mais gostoso?</label>

                    <div class="search-control">
                        <input id="search" name="search" type="search"
                            value="<?= htmlspecialchars($search, ENT_QUOTES) ?>"
                            placeholder="Busque por cafe, doce...">
                        <button type="submit">Buscar</button>
                    </div>

                    <div class="price-control">
                        <label for="minPrice">De R$</label>
                        <input
                            id="minPrice"
                            name="minPrice"
                            type="number"
                            min="0"
                            step="0.01"
                            inputmode="decimal"
                            placeholder="Preço mínimo"
                            value="<?= ($minPrice !== '' && $minPrice !== null && (float)$minPrice > 0)
                                ? htmlspecialchars((string)$minPrice, ENT_QUOTES) : '' ?>">

                        <label for="maxPrice">até R$</label>
                        <input
                            id="maxPrice"
                            name="maxPrice"
                            type="number"
                            min="0"
                            step="0.01"
                            inputmode="decimal"
                            placeholder="Preço máximo"
                            value="<?= ($maxPrice !== '' && $maxPrice !== null)
                                ? htmlspecialchars((string)$maxPrice, ENT_QUOTES) : '' ?>">
                    </div>

                    <!-- mantém a categoria ativa quando o usuário busca por texto ou preço -->
                    <input type="hidden" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES) ?>">
                </form>
                <a class="cart-link" href="ShoppingCart/index.php">Carrinho <span><?= (int)$cartItemCount ?></span></a>
                </section>
                <?php
                    $categories = ['all' => 'Todos'];

                    foreach($allProducts as $product) {
                        $productCategory = $product->getCategory();
                        $slug = mb_strtolower($productCategory, 'UTF-8');
                        $categories[$slug] = $productCategory;
                    }

                    $activeCategory = ($category === '')
                    ? 'all'
                    : mb_strtolower($category, 'UTF-8');
                ?>

                <nav class="pre-filters" aria-label="Filtrar por categoria">
                    <?php foreach ($categories as $slug => $label): ?>
                        <?php
                            $isActive = ($activeCategory === $slug);
                            $url = ($slug === 'all')
                            ? productUrl($base, ['category' => ''])
                            : productUrl($base, ['category' => $slug]);
                        ?>
                        <a
                            class="filter-chip<?= $isActive ? ' is-active' : '' ?>"
                            href="<?= htmlspecialchars($url) ?>"
                            <?= $isActive ? 'aria-current="true"' : '' ?>
                        >
                            <?= htmlspecialchars($label) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <?php if(empty($productsOnDisplay)): ?>
                    <p class="empty-state">Nenhum produto encontrado.</p>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($productsOnDisplay as $product): ?>
                            <a href="#detalhe-<?= (int)$product->getId() ?>">
                                <article class="product-card">
                                    <img
                                        class="product-image"
                                        src="<?= htmlspecialchars($product->getImage()) ?>"
                                        alt="<?= htmlspecialchars($product->getName()) ?>">

                                        <div class="product-info">
                                            <p class="product-type"><?= htmlspecialchars($product->getCategory()) ?></p>

                                            <h3><?= htmlspecialchars($product->getName()) ?></h3>
                                            <p><?= htmlspecialchars($product->getDescription()) ?></p>

                                            
                                            <strong>R$ <?= number_format($product->getPrice(), 2, ',', '.') ?></strong>
                                        </div>
                                </article>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php foreach ($productsOnDisplay as $product): ?>
                        <div class="product-detail-overlay" id="detalhe-<?= (int)$product->getId() ?>">
                            <div class="product-detail-card">
                                <a class="product-detail-close" href="#produtos" aria-label="Fechar">&times;</a>

                                <img
                                    class="product-detail-image"
                                    src="<?= htmlspecialchars($product->getImage()) ?>"
                                    alt="<?= htmlspecialchars($product->getName()) ?>"
                                >
                                <p class="product-type"><?= htmlspecialchars($product->getCategory()) ?></p>

                                <h3><?= htmlspecialchars($product->getName()) ?></h3>
                                <p><?= htmlspecialchars($product->getDescription()) ?></p>
                                <p class="product-detail-amount">
                                    R$ <?= number_format($product->getPrice(), 2, ',', '.') ?>
                                </p>
                                <form class="add-to-cart-form" method="post"
                                    action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) ?>">
                                    <label class="add-to-cart-quantity-label" for="qty-<?= (int)$product->getId() ?>">Quantidade</label>
                                    <div class="add-to-cart-row">
                                        <input
                                            class="add-to-cart-quantity"
                                            id="qty-<?= (int)$product->getId() ?>"
                                            type="number"
                                            name="add_to_cart_quantity"
                                            min="1"
                                            step="1"
                                            value="1"
                                            inputmode="numeric">
                                        <input type="hidden" name="add_to_cart_id" value="<?= (int)$product->getId() ?>">
                                        <input type="hidden" name="return_query"
                                            value="<?= htmlspecialchars($_SERVER['QUERY_STRING'] ?? '', ENT_QUOTES) ?>">
                                        <button type="submit" class="add-to-cart-button">Adicionar ao carrinho</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if($totalPages > 1): ?>
                        <nav class="pagination-control" aria-label="Paginacao do cardapio">
                            <?php if($page > 1): ?>
                                <a
                                    class="pagination-arrow is-jump"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => 1])) ?>"
                                    aria-label="Primeira pagina"
                                    title="Primeira Pagina">&laquo;
                                </a>
                                <a
                                    class="pagination-arrow"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => $page - 1])) ?>"
                                    aria-label="Pagina anterior"
                                    title="Anterior">&lsaquo;
                                </a>
                            <?php else: ?>
                                <span class="pagination-arrow is-jump is-disabled" aria-hidden="true">&laquo;</span>
                                <span class="pagination-arrow is-jump is-disabled" aria-hidden="true">&lsaquo;</span>
                            <?php endif; ?>

                            <!-- index.php (pagination form) -->
                            <form
                                class="pagination-goto"
                                method="get"
                                action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) ?>#produtos">

                                <input type="hidden" name="search"   value="<?= htmlspecialchars($search,   ENT_QUOTES) ?>">
                                <input type="hidden" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES) ?>">
                                <input type="hidden" name="minPrice" value="<?= htmlspecialchars($minPrice, ENT_QUOTES) ?>">
                                <input type="hidden" name="maxPrice" value="<?= htmlspecialchars($maxPrice, ENT_QUOTES) ?>">

                                <input
                                    class="pagination-jump"
                                    type="number"
                                    name="page"
                                    min="1"
                                    max="<?= (int)$totalPages ?>"
                                    placeholder="..."
                                    aria-label="Ir para pagina">
                            </form>

                            <?php if($page < $totalPages): ?>
                                <a
                                    class="pagination-arrow"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => $page + 1])) ?>"
                                    aria-label="Proxima pagina"
                                    title="Proxima">&rsaquo;
                                </a>
                                <a
                                    class="pagination-arrow is-jump"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => $totalPages])) ?>"
                                    aria-label="Ultima pagina"
                                    title="Ultima Pagina">&raquo;
                                </a>
                            <?php else: ?>
                                <span class="pagination-arrow is-jump is-disabled" aria-hidden="true">&rsaquo;</span>
                                <span class="pagination-arrow is-jump is-disabled" aria-hidden="true">&raquo;</span>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
            <section class="contact-section" id="contato" aria-labelledby="contact-title">
                <div>
                    <p class="eyebrow">Vem tomar um cafe</p>
                    <h2 id="contact-title">Seu cantinho fica na Rua do Aroma, 42.</h2>
                    <p>De segunda a sabado, das 8h as 19h. Fale com a gente e reserve sua mesa.</p>
                </div>
                <a class="primary-button eyebrow" href="mailto:oi@loxjacafe.com">Contate-nos<span aria-hidden="true">&rarr;</span></a>
            </section>
            <section class="about-section" id="sobre" aria-labelledby="about-title">
                <div class="about-image"
                     role="img"
                     aria-label="Interior aconchegante da Loxja Cafe, com mesas de madeira e luz suave"></div>
                <div class="about-copy">
                    <p class="eyebrow">Sobre nós</p>
                    <h2 id="about-title">Um cantinho feito à mão, xícara por xícara.</h2>
                    <p>A Loxja nasceu do desejo de desacelerar. Em 2026 abrimos nossas portas com uma ideia simples: servir cafés especiais e doces delicados em um ambiente onde o tempo passa mais devagar.</p>
                    <p>Cada grão é escolhido a dedo entre pequenos produtores brasileiros, torrado em pequenos lotes e preparado com o cuidado de quem acredita que um bom café é, antes de tudo, um gesto de carinho.</p>
                    <a class="primary-button" href="#produtos">Conheça nosso cardápio <span aria-hidden="true">&rarr;</span></a>
                </div>
            </section>
        </main>
        <footer>
            <a class="brand footer-brand" href="#inicio">
                <span class="brand-mark">
                    <img src="logo.png" alt="Loxja Cafe" class="brand-mark">
                </span>
                <span>Loxja <small>cafe</small></span></a><p>&copy; 2026 Loxja Cafe. Todos os direitos reservados.</p>
            <a href="#inicio" class="back-top" aria-label="Voltar ao inicio">&uarr;</a>
        </footer>
    </body>
</html>
