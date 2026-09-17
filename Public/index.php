<?php

/**
 * H1PI - Visitação
 * "eu como visitante quero conseguir visualizar todos os itens e inspecionar eles"
 *
 * Tudo que foi adicionado para este requisito está isolado neste bloco PHP
 * no topo do arquivo, e no bloco <style> logo abaixo do <head>. O resto do
 * arquivo (header, hero, contato, footer) continua igual ao original.
 */

require_once __DIR__ . "/../Src/Connection.php";
require_once __DIR__ . "/../Src/Class/Product/Product.php";
require_once __DIR__ . "/../Src/Class/Product/ProductDB.php";

// --- quantos produtos mostrar por página ---
const PRODUTOS_POR_PAGINA = 6;

$produtos = [];
$totalPaginas = 1;
$paginaAtual = 1;
$erroBanco = null;

try {
    $pdo = Connection::conectar();

    // página atual vem da URL (?pagina=2). Se não vier, ou vier inválida, usamos 1.
    $paginaAtual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($paginaAtual < 1) {
        $paginaAtual = 1;
    }

    $offset = ($paginaAtual - 1) * PRODUTOS_POR_PAGINA;

    // total de produtos no banco, pra saber quantas páginas existem
    $totalProdutos = (int) $pdo->query("SELECT COUNT(*) FROM Product")->fetchColumn();
    $totalPaginas = (int) max(1, ceil($totalProdutos / PRODUTOS_POR_PAGINA));

    // se a página pedida passar do total, volta pra última válida
    if ($paginaAtual > $totalPaginas) {
        $paginaAtual = $totalPaginas;
        $offset = ($paginaAtual - 1) * PRODUTOS_POR_PAGINA;
    }

    // busca só a "fatia" de produtos desta página (LIMIT/OFFSET)
    $stmt = $pdo->prepare(
        "SELECT pdt_id, pdt_name, pdt_price, pdt_description, pdt_amount, pdt_category
         FROM Product
         ORDER BY pdt_id
         LIMIT :limite OFFSET :offset"
    );
    $stmt->bindValue(':limite', PRODUTOS_POR_PAGINA, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $produtos[] = new Product(
            (int) $linha['pdt_id'],
            $linha['pdt_name'],
            (float) $linha['pdt_price'],
            $linha['pdt_description'] ?? '',
            (int) $linha['pdt_amount'],
            $linha['pdt_category'] ?? ''
        );
    }
} catch (PDOException $e) {
    // se o banco falhar, a página continua funcionando, só sem produtos
    $erroBanco = "Não foi possível carregar os produtos no momento.";
}

/**
 * Formata preço float pro padrão brasileiro (R$ 12,90)
 */
function formatarPreco(float $preco): string
{
    return number_format($preco, 2, ',', '.');
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
        <style>
            /* ===== H1PI - estilos do tab-card de detalhes e da paginação ===== */
            /* Cada card de produto tem um link "#produto-ID". O overlay de detalhes
               tem esse mesmo ID, então o CSS :target o exibe sem precisar de JS. */
            .product-detail-overlay {
                display: none;
                position: fixed;
                inset: 0;
                z-index: 999;
                padding: 24px;
                background: rgba(0, 0, 0, 0.55);
                align-items: center;
                justify-content: center;
            }

            .product-detail-overlay:target {
                display: flex;
            }

            .product-detail-card {
                position: relative;
                width: min(560px, 100%);
                max-height: 85vh;
                overflow-y: auto;
                background: #fff;
                border-radius: 16px;
                padding: 32px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            }

            .product-detail-close {
                position: absolute;
                top: 12px;
                right: 16px;
                font-size: 1.6rem;
                line-height: 1;
                text-decoration: none;
                color: inherit;
            }

            .product-detail-image {
                display: block;
                width: 100%;
                height: 200px;
                border-radius: 12px;
                margin-bottom: 16px;
                background-color: #e8ddcf;
                object-fit: cover;
            }

            /* imagens de teste vindas do dummyimage.com nos cards da grade */
            img.product-image {
                display: block;
                width: 100%;
                height: 180px;
                object-fit: cover;
                border-radius: 12px 12px 0 0;
                background-color: #e8ddcf;
            }

            .product-detail-amount {
                margin: 8px 0;
                font-size: 0.95rem;
                opacity: 0.8;
            }

            .product-card .text-link {
                display: inline-block;
                margin-top: 8px;
            }

            /* ===== Paginação com círculos (máx 3 números + "..." digitável) ===== */
            .pagination-control {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 12px;
                margin-top: 32px;
                flex-wrap: wrap;
            }

            .pagination-pages {
                display: flex;
                gap: 8px;
                flex-wrap: wrap;
                justify-content: center;
                align-items: center;
            }

            .pagination-number,
            .pagination-arrow,
            .pagination-jump {
                /* tamanho fixo e idêntico pros 3 tipos */
                box-sizing: border-box;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                padding: 0;
                margin: 0;
                line-height: 1;
                vertical-align: middle;

                border-radius: 50%;
                border: 1px solid currentColor;
                text-decoration: none;
                color: inherit;
                font-weight: 600;
                font-size: 0.95rem;
                font-family: inherit;
                background: transparent;
                transition: transform 0.15s ease, background 0.15s ease, color 0.15s ease;
            }

            .pagination-arrow {
                font-size: 1.2rem;
            }

            .pagination-number:hover,
            .pagination-arrow:hover,
            .pagination-jump:hover {
                background: rgba(111, 78, 55, 0.12);
                transform: translateY(-1px);
            }

            .pagination-number.is-active {
                background: #6f4e37;
                color: #fff;
                border-color: #6f4e37;
                cursor: default;
            }

            .pagination-number.is-active:hover {
                transform: none;
                background: #6f4e37;
            }

            /* setas de "pular tudo" um pouco mais discretas */
            .pagination-arrow.is-jump {
                font-size: 1.05rem;
                opacity: 0.85;
            }

            .pagination-arrow.is-disabled {
                opacity: 0.35;
                cursor: not-allowed;
            }

            .pagination-arrow.is-disabled:hover {
                background: transparent;
                transform: none;
            }

            /* O "..." como input circular */
            .pagination-jump {
                text-align: center;
                -moz-appearance: textfield;
                appearance: textfield;
                cursor: text;
                font-size: 0.95rem;
            }

            .pagination-jump::-webkit-outer-spin-button,
            .pagination-jump::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .pagination-jump:focus {
                outline: 2px solid #6f4e37;
                outline-offset: 2px;
                background: #fff;
            }

            .pagination-jump::placeholder {
                color: currentColor;
                opacity: 0.75;
                font-weight: 700;
                letter-spacing: 1px;
            }

            .empty-state {
                text-align: center;
                padding: 24px;
                opacity: 0.8;
            }
        </style>
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
                <a class="login-button" href="Client/index.php">Area do Cliente</a>
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
                        <h2 id="products-title">Nosso cardapio</h2>
                    </div>
                </div>

                <?php if ($erroBanco): ?>
                    <p class="empty-state"><?= htmlspecialchars($erroBanco) ?></p>
                <?php elseif (empty($produtos)): ?>
                    <p class="empty-state">Nenhum produto cadastrado ainda.</p>
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

                    <?php if ($totalPaginas > 1): ?>
                        <div class="pagination-control">
                            <?php // ---- Primeira página ---- ?>
                            <?php if ($paginaAtual > 1): ?>
                                <a class="pagination-arrow is-jump"
                                   href="?pagina=1#produtos"
                                   aria-label="Primeira página"
                                   title="Primeira página">&laquo;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-jump is-disabled"
                                      aria-disabled="true">&laquo;</span>
                            <?php endif; ?>

                            <?php // ---- Página anterior ---- ?>
                            <?php if ($paginaAtual > 1): ?>
                                <a class="pagination-arrow"
                                   href="?pagina=<?= $paginaAtual - 1 ?>#produtos"
                                   aria-label="Página anterior"
                                   title="Página anterior">&larr;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-disabled"
                                      aria-disabled="true">&larr;</span>
                            <?php endif; ?>

                            <div class="pagination-pages">
                                <?php
                                // Janela de até 3 números:
                                //   - se tem 3 páginas ou menos, mostra todas;
                                //   - senão, mostra 3 centradas na página atual, sem passar dos limites.
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
                                           href="?pagina=<?= $i ?>#produtos"
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
                                            window.location.href = '?pagina=' + v + '#produtos';
                                        }
                                    }"
                                >
                            <?php endif; ?>

                            <?php // ---- Próxima página ---- ?>
                            <?php if ($paginaAtual < $totalPaginas): ?>
                                <a class="pagination-arrow"
                                   href="?pagina=<?= $paginaAtual + 1 ?>#produtos"
                                   aria-label="Próxima página"
                                   title="Próxima página">&rarr;</a>
                            <?php else: ?>
                                <span class="pagination-arrow is-disabled"
                                      aria-disabled="true">&rarr;</span>
                            <?php endif; ?>

                            <?php // ---- Última página ---- ?>
                            <?php if ($paginaAtual < $totalPaginas): ?>
                                <a class="pagination-arrow is-jump"
                                   href="?pagina=<?= $totalPaginas ?>#produtos"
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
                <span class="brand-mark">L</span>
                <span>Loxja <small>cafe</small></span></a><p>&copy; 2026 Loxja Cafe. Todos os direitos reservados.</p>
            <a href="#inicio" class="back-top" aria-label="Voltar ao inicio">&uarr;</a>
        </footer>
    </body>
</html>