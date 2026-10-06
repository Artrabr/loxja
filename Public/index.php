<?php
    require_once __DIR__ . "/../Src/Connection.php";
    require_once __DIR__ . "/../Src/Class/Product/Product.php";
    require_once __DIR__ . "/../Src/Class/Product/ProductDB.php";

    // funções para a seção de paginação
    // 1. exibir produtos
    // 2. exibir produtos com filtros baseados em
    //    nome
    //    categoria
    // 3. 
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
        <link href="style_index_exibicao.css" rel="stylesheet">
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
                    <label for="busca">O que vai deixar seu dia mais gostoso?</label>
                    <div class="search-control">
                        <input id="busca" name="busca" type="search"
                            value="<?= htmlspecialchars($busca) ?>"
                            placeholder="Busque por cafe, doce...">
                        <!-- mantém a categoria ativa quando o usuário busca por texto -->
                        <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria) ?>">
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

                <?php if (!empty($categorias)): ?>
                    <div class="pre-filters" role="group" aria-label="Pré-filtros por categoria">
                        <a class="filter-chip <?= $categoria === '' ? 'is-active' : '' ?>"
                        href="<?= htmlspecialchars(urlComFiltros(['categoria' => '', 'pagina' => 1])) ?>">
                            Todas as categorias
                        </a>
                        <?php foreach ($categorias as $cat): ?>
                            <a class="filter-chip <?= $categoria === $cat ? 'is-active' : '' ?>"
                            href="<?= htmlspecialchars(urlComFiltros(['categoria' => $cat, 'pagina' => 1])) ?>">
                                <?= htmlspecialchars($cat) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($busca !== '' || $categoria !== ''): ?>
                    <div class="filter-status">
                        <span>
                            <?php
                                $partes = [];
                                if ($busca !== '')     $partes[] = 'busca: <strong>' . htmlspecialchars($busca) . '</strong>';
                                if ($categoria !== '') $partes[] = 'categoria: <strong>' . htmlspecialchars($categoria) . '</strong>';
                                echo implode(' &middot; ', $partes);
                            ?>
                            &mdash; <?= (int) $totalProdutos ?> resultado(s)
                        </span>
                        <a class="text-link" href="?pagina=1#produtos">Limpar filtros</a>
                    </div>
                <?php endif; ?>

                <?php if ($erroBanco): ?>
                    <p class="empty-state"><?= htmlspecialchars($erroBanco) ?></p>
                <?php elseif (empty($produtos)): ?>
                    <p class="empty-state">
                        <?php if ($busca !== '' || $categoria !== ''): ?>
                            Nenhum produto encontrado com os filtros atuais.
                            <br>
                            <a class="text-link" href="<?= htmlspecialchars(urlComFiltros(['pagina' => 1])) ?>">Limpar filtros</a>
                        <?php else: ?>
                            Nenhum produto cadastrado ainda.
                        <?php endif; ?>
                    </p>
                <?php else: ?>
                    <div class="product-grid">
                        <?php foreach ($produtos as $produto): ?>
                            <article class="product-card">
                                <img
                                    class="product-image"
                                    src="https://dummyimage.com/400x250/6f4e37/f5e9da.png&text=<?= urlencode($produto->getName()) ?>"
                                    alt="<?= htmlspecialchars($produto->getName()) ?>"
                                    loading="lazy"
                                >
                                <div class="product-info">
                                    <p class="product-type"><?= htmlspecialchars($produto->getCategory()) ?></p>
                                    <h3><?= htmlspecialchars($produto->getName()) ?></h3>
                                    <p><?= htmlspecialchars(mb_strimwidth($produto->getDescription(), 0, 90, '...')) ?></p>
                                    <strong>R$ <?= formatarPreco((float) $produto->getPrice()) ?></strong>
                                    <br>
                                    <a class="text-link" href="#produto-<?= $produto->getId() ?>">Ver detalhes <span aria-hidden="true">&rarr;</span></a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <?php $urlTemplatePagina = htmlspecialchars(urlComFiltros(['pagina' => '__PAGINA__'])); ?>
                                            <?php $urlTemplatePagina = htmlspecialchars(urlComFiltros(['pagina' => '__PAGINA__'])); ?>

                    <?php if ($totalPaginas > 1): ?>
                        <div class="pagination-control">
                            <?php // ---- Primeira página ---- ?>
                            <?php if ($paginaAtual > 1): ?>
                                <a class="pagination-arrow is-jump"
                                   href="<?= htmlspecialchars(urlComFiltros(['pagina' => 1])) ?>"
                                   aria-label="Primeira página"
                                   title="Primeira página">&laquo;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-jump is-disabled"
                                      aria-disabled="true">&laquo;</span>
                            <?php endif; ?>

                            <?php // ---- Página anterior ---- ?>
                            <?php if ($paginaAtual > 1): ?>
                                <a class="pagination-arrow"
                                   href="<?= htmlspecialchars(urlComFiltros(['pagina' => $paginaAtual - 1])) ?>"
                                   aria-label="Página anterior"
                                   title="Página anterior">&larr;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-disabled"
                                      aria-disabled="true">&larr;</span>
                            <?php endif; ?>

                            <div class="pagination-pages">
                                <?php
                                if ($totalPaginas <= 3) {
                                    $inicio = 1;
                                    $fim    = $totalPaginas;
                                } else {
                                    $inicio = max(1, min($paginaAtual - 1, $totalPaginas - 2));
                                    $fim    = $inicio + 2;
                                }

                                for ($i = $inicio; $i <= $fim; $i++):
                                    if ($i === $paginaAtual): ?>
                                        <span class="pagination-number is-active"
                                              aria-current="page"><?= $i ?></span>
                                    <?php else: ?>
                                        <a class="pagination-number"
                                           href="<?= htmlspecialchars(urlComFiltros(['pagina' => $i])) ?>"
                                           aria-label="Ir para a página <?= $i ?>"><?= $i ?></a>
                                    <?php endif;
                                endfor; ?>
                            </div>

                            <?php if ($totalPaginas > 3): ?>
                                <input
                                    type="number"
                                    class="pagination-jump"
                                    min="1"
                                    max="<?= $totalPaginas ?>"
                                    placeholder="..."
                                    aria-label="Digite o número da página e pressione Enter"
                                    title="Digite o número da página e aperte Enter"
                                    onkeydown="if (event.key === 'Enter') {
                                        event.preventDefault();
                                        var v = parseInt(this.value, 10);
                                        if (v >= 1 && v <= <?= $totalPaginas ?>) {
                                            window.location.href = '<?= $urlTemplatePagina ?>'.replace('__PAGINA__', v);
                                        }
                                    }"
                                >
                            <?php endif; ?>

                            <?php // ---- Próxima página ---- ?>
                            <?php if ($paginaAtual < $totalPaginas): ?>
                                <a class="pagination-arrow"
                                   href="<?= htmlspecialchars(urlComFiltros(['pagina' => $paginaAtual + 1])) ?>"
                                   aria-label="Próxima página"
                                   title="Próxima página">&rarr;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-disabled"
                                      aria-disabled="true">&rarr;</span>
                            <?php endif; ?>

                            <?php // ---- Última página ---- ?>
                            <?php if ($paginaAtual < $totalPaginas): ?>
                                <a class="pagination-arrow is-jump"
                                   href="<?= htmlspecialchars(urlComFiltros(['pagina' => $totalPaginas])) ?>"
                                   aria-label="Última página"
                                   title="Última página">&raquo;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-jump is-disabled"
                                      aria-disabled="true">&raquo;</span>
                            <?php endif; ?>
                        </div>

                    <?php endif; ?>

                    <?php foreach ($produtos as $produto): ?>
                        <div class="product-detail-overlay" id="produto-<?= $produto->getId() ?>">
                            <div class="product-detail-card">
                                <a class="product-detail-close" href="#produtos" aria-label="Fechar detalhes">&times;</a>
                                <img
                                    class="product-detail-image"
                                    src="https://dummyimage.com/600x300/6f4e37/f5e9da.png&text=<?= urlencode($produto->getName()) ?>"
                                    alt="<?= htmlspecialchars($produto->getName()) ?>"
                                    loading="lazy"
                                >
                                <p class="product-type"><?= htmlspecialchars($produto->getCategory()) ?></p>
                                <h3><?= htmlspecialchars($produto->getName()) ?></h3>
                                <p><?= htmlspecialchars($produto->getDescription()) ?></p>
                                <p class="product-detail-amount">Disponivel: <?= (int) $produto->getAmountAvailable() ?> unidade(s)</p>
                                <strong>R$ <?= formatarPreco((float) $produto->getPrice()) ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
        <script>
        // H2PI - filtro "dinâmico" enquanto digita.
        // Isto filtra apenas os produtos já carregados na página atual.
        // A busca "de verdade" (que varre todas as páginas) continua sendo a do
        // formulário, disparada ao apertar Enter ou clicar em "Buscar".
        (function () {
            var input = document.getElementById('busca');
            var grid  = document.querySelector('.product-grid');
            if (!input || !grid) return;

            var cards = Array.prototype.slice.call(grid.querySelectorAll('.product-card'));
            var names = cards.map(function (card) {
                var h3 = card.querySelector('h3');
                return h3 ? h3.textContent.toLowerCase() : '';
            });

            var notice = document.createElement('p');
            notice.className = 'empty-state';
            notice.style.display = 'none';
            notice.textContent = 'Nenhum produto nesta página corresponde à busca. Aperte Enter para buscar em todas as páginas.';
            grid.parentNode.insertBefore(notice, grid.nextSibling);

            var timer = null;
            input.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    var q = input.value.trim().toLowerCase();
                    var visiveis = 0;
                    cards.forEach(function (card, i) {
                        var match = q === '' || names[i].indexOf(q) !== -1;
                        card.style.display = match ? '' : 'none';
                        if (match) visiveis++;
                    });
                    notice.style.display = visiveis === 0 ? '' : 'none';
                }, 120);
            });
        })();
        </script>
    </body>
</html>