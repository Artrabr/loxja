-- =========================================================
-- loxjaDataBase - Produtos de teste para banco de dados
-- =========================================================

USE loxjaDataBase;

INSERT INTO Product (pdt_name, pdt_price, pdt_description, pdt_amount, pdt_category) VALUES
('Expresso Raiva Matinal', 8.90, 'Um espresso tão forte que sua raiva de segunda-feira vira produtividade. Efeito colateral: falar rápido demais em reunião.', 40, 'Espresso'),
('Cappuccino Chorando', 14.50, 'Cappuccino com espuma tão alta que parece estar derramando lágrimas de leite. Consolo garantido em forma de canela por cima.', 25, 'Cappuccino'),
('Latte Procrastinando', 15.90, 'Feito devagar, tomado ainda mais devagar. Ideal para quem jurou que ia trabalhar depois desse cafézinho.', 30, 'Latte'),
('Mocha Crise Existencial', 16.90, 'Chocolate e café se perguntando qual dos dois é o verdadeiro sabor da xícara. Ninguém sabe. Todo mundo ama.', 20, 'Mocha'),
('Cold Brew Insonia', 13.90, 'Extraído a frio por 18 horas, igual sua última noite de sono antes da prova. Gelado, forte, sem arrependimentos.', 18, 'Cold Brew'),
('Descafeinado Decepção', 9.50, 'Para quem quer o ritual do café sem o superpoder da cafeína. Alguns clientes ainda não superaram essa escolha.', 22, 'Descafeinado'),
('Macchiato Passivo Agressivo', 15.50, 'Uma mancha de leite educada por cima de um espresso que não está nada feliz com você. Sutilmente intenso.', 27, 'Macchiato'),
('Affogato Derretendo', 17.90, 'Sorvete se afogando em espresso quente igual seus planos de dieta assim que o cardápio chega na mesa.', 15, 'Sobremesa'),
('Flat White Sem Drama', 15.90, 'O amigo equilibrado da família dos cafés com leite. Zero teatro, 100% cremosidade.', 24, 'Flat White'),
('Frappe Segunda-Feira', 18.50, 'Gelado, batido e meio confuso, igual todo mundo tentando lembrar a senha do computador no primeiro dia da semana.', 20, 'Gelado'),
('Ristretto Sem Paciencia', 11.90, 'Curto, direto e sem tempo pra enrolação. Assim como você quando o wi-fi cai no meio da call.', 35, 'Espresso'),
('Cafe Coado Vo Zeferina', 10.90, 'Receita de família, feito no pano igual antigamente. Vem com o carinho e a demora da vovó pra contar historia.', 32, 'Coado'),
('Whey com Granulado de Café', 22.90, 'Whey protein batido com café e granulados crocantes por cima. Músculo e energia no mesmo copo.', 25, 'Proteico'),
('Café Gelado com Coco', 16.90, 'Café coado gelado com leite de coco e bastante gelo. Tropical, cremoso e refrescante.', 30, 'Gelado'),
('Bolo de Café com Chocolate', 12.50, 'Fatia generosa de bolo de café com cobertura de chocolate meio amargo. Combinação clássica.', 18, 'Acompanhamento'),
('Pão de Queijo com Café', 9.90, 'Pão de queijo quentinho acompanhado de café coado. Dupla mineira imbatível.', 40, 'Acompanhamento'),
('Café Turco', 14.90, 'Preparado tradicionalmente com borra. Forte, aromático e cheio de personalidade.', 15, 'Especial'),
('Café Irlandês', 24.90, 'Café quente com whisky, açúcar e creme. Aquece por dentro e por fora.', 12, 'Especial'),
('Brownie de Café', 13.90, 'Brownie denso com nibs de café. Doce no ponto certo, intenso no final.', 22, 'Acompanhamento'),
('Frappé de Caramelo com Café', 19.90, 'Frappé cremoso com calda de caramelo e shot de espresso. Sobremesa e café na mesma taça.', 20, 'Gelado'),
('Café com Leite Condensado', 15.90, 'Espresso com leite condensado. Doce, cremoso e viciante.', 28, 'Latte'),
('Cheesecake de Café', 17.90, 'Cheesecake com base de biscoito e recheio de café. Fatia generosa.', 14, 'Sobremesa'),
('Muffin de Café', 10.90, 'Muffin de café com gotas de chocolate. Perfeito para a pausa da tarde.', 26, 'Acompanhamento'),
('Cookies com Nibs de Café', 8.50, 'Cookies crocantes com pedaços de café. Ideal para acompanhar um espresso.', 35, 'Acompanhamento'),
('Café Vienense', 18.90, 'Café com chantilly e raspas de chocolate. Sofisticado como Viena.', 16, 'Especial'),
('Café Carioca', 11.90, 'Café coado forte com açúcar. Simples, honesto e do jeito que o Rio gosta.', 30, 'Coado'),
('Café Mineiro', 12.90, 'Café coado no filtro de pano, doce e encorpado. Sabor de fazenda.', 32, 'Coado'),
('Cappuccino Italiano', 16.50, 'Cappuccino tradicional italiano com espuma firme e cacau polvilhado.', 24, 'Cappuccino'),
('Café Bombom', 14.50, 'Espresso com leite condensado, chocolate e creme. Explosão de doçura.', 20, 'Especial'),
('Café com Baunilha', 15.50, 'Latte com xarope de baunilha natural. Suave e perfumado.', 22, 'Latte'),
('Café com Canela', 14.90, 'Cappuccino com canela em pó. Aroma que abraça.', 25, 'Cappuccino'),
('Mocha Branco', 17.50, 'Mocha feito com chocolate branco e espresso. Doce e elegante.', 18, 'Mocha'),
('Café com Nutella', 19.90, 'Latte com generosa camada de Nutella. Para os dias que pedem indulgência.', 16, 'Especial'),
('Croissant com Café', 16.90, 'Croissant folhado acompanhado de café coado. Café da manhã parisiense.', 20, 'Acompanhamento'),
('Café com Creme de Avelã', 17.90, 'Espresso com creme de avelã e chantilly. Sobremesa em forma de café.', 18, 'Especial'),
('Chá de Cascara', 12.90, 'Infusão da casca do grão de café. Leve, frutado e sem cafeína.', 20, 'Especial');