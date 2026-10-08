<?php
    require_once __DIR__ . "/../bootstrap.php";

    require_once __DIR__ . "/../Src/Connection.php";
    require_once __DIR__ . "/../Src/Class/Product/Product.php";
    require_once __DIR__ . "/../Src/Class/Product/ProductDB.php";

    $pdo = Connection::conectar();
    $db = new ProductDB($pdo);

    function isNameMatching($name, $description, $category, $search) {
        if(!empty($search)){
            if(!str_contains($name, $search) &&
               !str_contains($description, $search) &&
               !str_contains($category, $search)) {
                    
               return false;
            }
        }
        return true;
    }

    function isCategoryMatching($chosenCategory, $productCategory) {
        if(!empty($chosenCategory) && $chosenCategory !== 'all'){
            if(mb_strtolower($chosenCategory, 'UTF-8') !== $productCategory){
                return false;
            }
        }
        return true;
    }

    function isWithinPriceRange($minPrice, $maxPrice, $productPrice) {
        if(!empty($minPrice) && $productPrice < (float)$minPrice){
            return false;
        }
        if(!empty($maxPrice) && $productPrice > (float)$maxPrice){
            return false;
        }

        return true;
    }

    $search   = trim($_GET['search']     ?? '');
    $category = trim($_GET['category'] ?? '');
    $minPrice = $_GET['minPrice'] ?? 0;
    $maxPrice = $_GET['maxPrice'] ?? null;

    if($minPrice < 0) $minPrice = 0;
    if(is_numeric($minPrice) && is_numeric($maxPrice)) {
        if($minPrice > $maxPrice) {
            $buffer = $minPrice;

            $minPrice = $maxPrice;
            $maxPrice = $buffer;
        }
    }

    $allProducts = $db->getAllProducts();
 
    $filteredProducts = [];

    $lowerSearch   = mb_strtolower($search, 'UTF-8');
    foreach($allProducts as $product) {
        $nameMatch     = true;
        $categoryMatch = true;
        $priceMatch    = true; 

        $lowerName = mb_strtolower($product->getName(), 'UTF-8');
        $lowerDescription = mb_strtolower($product->getDescription(), 'UTF-8');
        $lowerCategory = mb_strtolower($product->getCategory(), 'UTF-8');
        $price    = $product->getPrice();

        $nameMatch = isNameMatching($lowerName, $lowerDescription, $lowerCategory, $lowerSearch);
        $categoryMatch = isCategoryMatching($category, $lowerCategory);
        $priceMatch = isWithinPriceRange($minPrice, $maxPrice, $price);

        if($nameMatch && $categoryMatch && $priceMatch){
            $filteredProducts[] = $product;
        }
    }
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
        <link href="style_index_exibition.css" rel="stylesheet">
    </head> 
    
<!--===================[APAGAR]========================-->
    <a href="Administration/index.php" style='padding:5px; background-color:rgb(64, 171, 77);'>got to administration</a>  ||  <a href="Client/index.php" style='padding:5px; background-color:rgb(64, 171, 77);'>got to client area</a>
<!--===================[------]========================-->

    <body>
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
                    }else{
                ?>
                    <a class='login-button' href='Login/index.php'>Login</a>
                <?php
                    }
                ?>
            </nav>
        </header>
        <main>
            <section class="search-strip" aria-label="Pesquisa e utilidades">
                <form class="search-form" action="#produtos" method="get">
                    <label for="search">O que vai deixar seu dia mais gostoso?</label>
                    <div class="search-control">
                        <input id="search" name="search" type="search"
                            value="<?= htmlspecialchars($search) ?>"
                            placeholder="Busque por cafe, doce...">
                        <!-- mantém a category ativa quando o usuário busca por texto -->
                        <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
                        <button type="submit">Buscar</button>
                    </div>
                </form>
                <a class="cart-link" href="carrinho/index.php">Carrinho <span>0</span></a>
            </section>
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

                <!--- fazer seção de cardápio --->

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