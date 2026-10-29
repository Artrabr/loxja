<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_tema'])) {
        $temaAtual = $_COOKIE['tema'] ?? 'light';
        $temaNovo  = ($temaAtual === 'dark') ? 'light' : 'dark';

        setcookie('tema', $temaNovo, [
            'expires'  => time() + 60 * 60 * 24 * 365, // 1 ano
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

    $tema = $_COOKIE['tema'] ?? 'light';
    
    require_once __DIR__ . "/../Src/Class/Client/Client.php";
    require_once __DIR__ . "/../Src/Connection.php";
    require_once __DIR__ . "/../bootstrap.php";
    require_once __DIR__ . "/../Src/Class/Product/Product.php";
    require_once __DIR__ . "/../Src/Class/Product/ProductDB.php";
    require_once __DIR__ . "/../Src/Class/ShoppingCart/ShoppingCart.php";
    require_once __DIR__ . "/../Src/Class/ShoppingCart/ShoppingCartDB.php";

    $client = $_SESSION['client_object'] ?? null;
    $pdo = Connection::conectar();
    $db  = new ProductDB($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart_id'])) {
        $productId   = (int)$_POST['add_to_cart_id'];
        $returnQuery = (string)($_POST['return_query'] ?? '');
        $quantity    = max(1, (int)($_POST['add_to_cart_quantity'] ?? 1));

        if ($client instanceof Client) {
            try {
                $clientId = $client->getId();
                $cartDB   = new ShoppingCartDB($pdo);
                $cartDB->addItemToShoppingCartByClientId($clientId, $productId,$quantity);
                $_SESSION['cart_flash'] = 'Produto adicionado ao carrinho!';
            } catch (Throwable $e) {
                $_SESSION['cart_flash'] = 'Erro: ' . $e->getMessage();
            }
        } else {
            $_SESSION['cart_flash'] = 'Faça login para adicionar produtos ao carrinho.';
        }

        $path     = strtok($_SERVER['REQUEST_URI'], '?');
        $location = $path . ($returnQuery !== '' ? '?' . $returnQuery : '') . '#detalhe-' . $productId;

        header('Location: ' . $location);
        exit;
    }

    $cartFlash = $_SESSION['cart_flash'] ?? null;
    unset($_SESSION['cart_flash']);

    $cartItemCount = 0;
    if ($client instanceof Client) {
        try {
            $clientId = $client->getId();
            $cartDB   = new ShoppingCartDB($pdo);
            $cart     = $cartDB->getShoppingCartByClientId($clientId);
            if ($cart !== null) {
                foreach ($cart->getProducts() as $item) {
                    $cartItemCount += $item->getAmount();
                }
            }
        } catch (Throwable $e) {
            $cartItemCount = 0;
        }
    }

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
<html lang="pt-BR" data-theme="<?= htmlspecialchars($tema) ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Loxja Café: café especial, doces e momentos aconchegantes.">
        <title>Loxja Café | Seu momento favorito</title>
        <link href="MVP.css" rel="stylesheet">
        <link href="style_index.css" rel="stylesheet">
        <link href="style_index_exhibition.css" rel="stylesheet">
    </head> 
    <body>
        <?php if ($cartFlash !== null): ?>
            <div role="status" aria-live="polite"
                class="cart-notification">
                <?= htmlspecialchars($cartFlash) ?>
            </div>
        <?php endif; ?>
        <header class="site-header" id="inicio">
            <nav class="topbar" aria-label="Navegação principal">
                <a class="brand" href="#inicio" aria-label="Loxja Café, início">
                <img src="logo.png" alt="" class="brand-mark">
                <span>Loxja <small>café</small></span></a>
                <ul class="nav-links">
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#sobre">Sobre</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
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
                <?php
                    $email = '';
                    if(isset($_SESSION['client_object'])){
                        $email = $_SESSION['client_object']->getEmail();
                        $imageUrl = $_SESSION['client_object']->getImage();
                    
                ?> 
                    <a href="profile/index.php">
                        <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Foto de perfil" class="profile-avatar">
                    </a>
                <?php
                    } else {
                ?>
                    <a class="login-button" href="Login/index.php">Entrar</a>
                <?php
                }

                if ($client instanceof Client && $client->isAdmin()) {
                ?>
                    <a href="./Administration/index.php" rel="admin">Administração</a>
                <?php } ?>
            </nav>
        </header>
        <main>
            <section class="hero" aria-labelledby="hero-title">
                <div class="hero-copy">
                    <p class="eyebrow">Torra artesanal &bull; carinho em cada xícara</p>
                    <h1 id="hero-title">Um café quentinho para chamar de seu.</h1>
                    <p>Escolha seu momento favorito: aromas marcantes, doces delicados e aquele aconchego que cabe na rotina.</p>
                    <a class="primary-button" href="#produtos">Explorar sabores <span aria-hidden="true">&rarr;</span></a>
                </div>
                <div class="hero-art" role="img" aria-label="Xícara de café sobre uma mesa de madeira"></div>
            </section>
            <section class="products-section" id="produtos" aria-labelledby="products-title">
                <div class="section-heading">
                    <div>
                        <p class="eyebrow">Feitos para desacelerar</p>
                        <h2 id="products-title">Nosso cardápio</h2>
                    </div>
                </div>
                <section class="search-strip" aria-label="Pesquisa e utilidades">
                <form class="search-form" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES) ?>#produtos" method="get">
                    <label for="search">O que vai deixar seu dia mais gostoso?</label>

                    <div class="search-control">
                        <input id="search" name="search" type="search"
                            value="<?= htmlspecialchars($search, ENT_QUOTES) ?>"
                            placeholder="Busque por café, doce...">
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
                                    aria-label="Primeira página"
                                    title="Primeira página">&laquo;
                                </a>
                                <a
                                    class="pagination-arrow"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => $page - 1])) ?>"
                                    aria-label="Página anterior"
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
                                    aria-label="Ir para página">
                            </form>

                            <?php if($page < $totalPages): ?>
                                <a
                                    class="pagination-arrow"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => $page + 1])) ?>"
                                    aria-label="Próxima página"
                                    title="Proxima">&rsaquo;
                                </a>
                                <a
                                    class="pagination-arrow is-jump"
                                    href="<?= htmlspecialchars(productUrl($base, ['page' => $totalPages])) ?>"
                                    aria-label="Ultima pagina"
                                    title="Última página">&raquo;
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
                    <p class="eyebrow">Venha tomar um café</p>
                    <h2 id="contact-title">Seu cantinho fica na Rua do Aroma, 42.</h2>
                    <p>De segunda a sábado, das 8h às 19h. Fale com a gente e reserve sua mesa.</p>
                </div>
                <a class="primary-button eyebrow" href="mailto:oi@loxjacafe.com">Contate-nos<span aria-hidden="true">&rarr;</span></a>
            </section>
            <section class="about-section" id="sobre" aria-labelledby="about-title">
                <div class="about-image"
                     role="img"
                     aria-label="Interior aconchegante da Loxja Café, com mesas de madeira e luz suave"></div>
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
                    <img src="logo.png" alt="" class="brand-mark">
                </span>
                <span>Loxja <small>café</small></span></a><p>&copy; 2026 Loxja Café. Todos os direitos reservados.</p>
            <a href="#inicio" class="back-top" aria-label="Voltar ao início">&uarr;</a>
        </footer>
    </body>
</html>
