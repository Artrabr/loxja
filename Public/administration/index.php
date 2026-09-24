<?php
session_start();
// TODO: verificar se o usuário tem permissão de administrador.

require_once __DIR__ . '/../../Src/Class/ClientAddress/ClientAddressDB.php';
require_once __DIR__ . '/../../Src/Connection.php';
?>

<!DOCTYPE html>
<html lang="pt-br" color-mode="user">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="../MVP.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
  </head>
  <body>

    <header>
      <h1>Loxja - Painel de Administração</h1>
    </header>

    <main>
      <!-- <section> -->
      <!--   <header> -->
      <!--     <h1>Gerenciar produtos</h1> -->
      <!--   </header> -->
      <!--   <a href=""> -->
      <!--     <aside> -->
      <!--       <h2> Criar Produto</h2> -->
      <!--     </aside> -->
      <!--   </a> -->
      <!--   <a href=""> -->
      <!--     <aside> -->
      <!--       <h2> Editar Produto</h2> -->
      <!--     </aside> -->
      <!--   </a> -->
      <!-- </section> -->
      <section>
        <header>
          <h1>Gerenciar Produtos</h1>
        </header>
        <nav class="full-width-nav">
          <ul>
            <li><a href="adicionar-produto.php"><b>Adicionar Produto</b></a></li>
            <li><a href="editar-produto.php"><b>Editar Produto</b></a></li>
            <li><a href="remover-produto.php"><b>Remover Produto</b></a></li>
          </ul>
        </nav>
      </section>
    </main>

    <footer>
      <hr>
      <p>
        <small>&copy; 2026 Loxja.</small>
      </p>
    </footer>


  
  </body>
</html>
