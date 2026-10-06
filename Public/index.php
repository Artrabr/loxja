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