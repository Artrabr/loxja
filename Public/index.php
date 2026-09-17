<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Loxja Cafe: cafe especial, doces e momentos aconchegantes.">
        <title>Loxja Cafe | Seu momento favorito</title>
        <link href="MVP.css" rel="stylesheet">
        <link href="style_index.css" rel="stylesheet">
    </head>
    <body>
        <header class="site-header" id="inicio">
            <nav class="topbar" aria-label="Navegacao principal">
                <a class="brand" href="#inicio" aria-label="Loxja Cafe, inicio"><span class="brand-mark">L</span><span>Loxja <small>cafe</small></span></a>
                <ul class="nav-links">
                    <li><a href="#produtos">Produtos</a></li>
                    <li><a href="#sobre">Sobre</a></li>
                    <li><a href="#contato">Contato</a></li>
                </ul>
                <a class="login-button" href="Login/index.php">Login</a>
            </nav>
        </header>
        <main>
            <section class="search-strip" aria-label="Pesquisa e utilidades">
                <form class="search-form" action="#produtos" method="get">
                    <label for="busca">O que vai deixar seu dia mais gostoso?</label>
                    <div class="search-control">
                        <input id="busca" name="busca" type="search" placeholder="Busque por cafe, doce...">
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
                        <h2 id="products-title">Favoritos da casa</h2>
                    </div>
                    <a class="text-link" href="produtos/cassio/index.php">Ver todos <span aria-hidden="true">&rarr;</span></a>
                </div>
                <div class="product-grid">
                    <article class="product-card">
                        <div class="product-image image-coffee" role="img" aria-label="Cafe coado especial"></div>
                        <div class="product-info">
                            <p class="product-type">Cafe especial</p>
                            <h3>Coado da Serra</h3>
                            <p>Notas de chocolate e frutas amarelas.</p>
                            <strong>R$ 12,90</strong>
                        </div>
                    </article>
                    <article class="product-card">
                        <div class="product-image image-cappuccino" role="img" aria-label="Cappuccino cremoso"></div>
                        <div class="product-info">
                            <p class="product-type">Quentinho</p>
                            <h3>Cappuccino Loxja</h3>
                            <p>Espuma cremosa e canela na medida.</p>
                            <strong>R$ 15,90</strong>
                        </div>
                    </article>
                    <article class="product-card">
                        <div class="product-image image-cake" role="img" aria-label="Fatia de bolo caseiro"></div>
                        <div class="product-info">
                            <p class="product-type">Da vitrine</p>
                            <h3>Bolo de cenoura</h3>
                            <p>Massa macia com cobertura de chocolate.</p>
                            <strong>R$ 10,90</strong>
                        </div>
                    </article>
                </div>
            <section class="contact-section" id="contato" aria-labelledby="contact-title">
                <div>
                    <p class="eyebrow">Vem tomar um cafe</p>
                    <h2 id="contact-title">Seu cantinho fica na Rua do Aroma, 42.</h2>
                    <p>De segunda a sabado, das 8h as 19h. Fale com a gente e reserve sua mesa.</p>
                </div>
                <a class="primary-button eyebrow" href="mailto:oi@loxjacafe.com">Contate-nos<span aria-hidden="true">&rarr;</span></a>
            </section>
        </main>
        <footer>
            <a class="brand footer-brand" href="#inicio">
                <span class="brand-mark">L</span>
                <span>Loxja <small>cafe</small></span></a><p>&copy; 2026 Loxja Cafe. Todos os direitos reservados.</p>
            <a href="#inicio" class="back-top" aria-label="Voltar ao inicio">&uarr;</a>
        </footer>
    </body>
</html>
