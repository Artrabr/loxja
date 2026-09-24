<?php
require_once __DIR__ . "/../../Src/Tests/ConnectionTests.php";

ConnectionTests::deleteDBIfItExists();
ConnectionTests::createDBIfNeededAndConnect();
ConnectionTests::InitializeScheme();

function passedAll($input)
{
    array_walk_recursive($input, function ($value) {
        if ($value === false) {
            return false;
        }
    });
    return true;
}

$t = microtime(true);

$results = [
    "Classes" => [
        // "Client" => [
        //     "getId" => false,
        //     "getName" => false,
        //     "setName" => false,
        //     "getEmail" => false,
        //     "setEmail" => false
        // ],
        "ClientDB" => [
            "getClientByID" => false,
            "getClientByEmail" => false,
            "createClient" => false,
            "updateClient" => false,
            "isRightPassword" => false
        ],
        "Product" => [
            "getId" => false,
            "getName" => false,
            "setName" => false,
            "getPrice" => false,
            "setPrice" => false,
            "getDescription" => false,
            "setDescription" => false,
            "getAmountAvailable" => false,
            "setAmountAvailable" => false,
            "getCategory" => false,
            "setCategory" => false
        ],
        "ProductDB" => [
            "getProductByID" => false,
            "createProduct" => false,
        ],
        "ShoppingCartItem" => [
            "getProduct" => false,
            "SetAmount" => false,
            "GetAmount" => false,
        ],
        // "ShoppingCart" => [
        //     "getId" => false,
        //     "getName" => false,
        //     "setName" => false,
        //     "getEmail" => false,
        //     "setEmail" => false
        // ],
        // "ShoppingCartDB" => [
        //     "getShoppingCartByClientId" => false,
        //     "setShoppingCartByClientId" => false,
        // ],
    ],
];

$results['Classes']['ClientDB'] = require_once __DIR__ . "/../../Src/Tests/Client/TestsClientDB.php";
$results['Classes']['Product'] = require_once __DIR__ . "/../../Src/Tests/Product/TestProduct.php";
$results['Classes']['ProductDB'] = require_once __DIR__ . "/../../Src/Tests/Product/TestProductDB.php";
$results['Classes']['ShoppingCartItem'] = require_once __DIR__ . "/../../Src/Tests/ShoppingCart/TestShoppingCartItem.php";

$testAmount = 0;
$errorCount = 0;
array_walk_recursive($results, function ($value, $key) use (&$errorCount, &$testAmount) {
    $testAmount++;
    if ($value === true) {
        $errorCount++;
    }
});


if ($errorCount == 0) {
    $class_header = "pass";
} else {
    $class_header = "fail";
}

?>

<!DOCTYPE html>
<html lang="pt-br" data-theme="dark">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link href="pico.pumpkin.min.css" rel="stylesheet">
    <link href="index_style.css" rel="stylesheet">
  </head>

  <body>

    <main class="container">
      <h1>Testes loxja</h1>

      <section id="summary">
        <article id="header" class="affected">
          <h1> <span class="<?=$class_header ?>"><?=$errorCount ?> erros</span> de <?=$testAmount ?> testes</h1>
        </article>
      </section>

      <section id="errors">
        <article id="error_list">
          <?php foreach ($results['Classes'] as $Class => $methods) : ?>
                <?php if (passedAll($results['Classes'][$Class])) : ?>
              <h2><?=$Class?> <span class="pass">passed</span></h2>
                <?php else : ?>
              <h2><?=$Class?> <span class="fail">failed</span></h2>
              <ul>
                    <?php foreach ($methods as $method => $failed) : ?>
                        <?php if ($failed) : ?>
                <li><h4><?=$method?>: <span class="fail">failed</span></h4></li>
                        <?php else : ?>
                <li><h4><?=$method?>: <span class="pass">passed</span></h4></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
              </ul>
                <?php endif; ?>
          <?php endforeach; ?>
        </article>
      </section>

      <section>
        <pre><code>
          <?php var_dump($results); ?>
        </code></pre>
      </section>

      <section>
        <?php echo microtime(true) - $t; ?>
      </section>
    </main>


    <script src="/js/htmx.min.js"></script>
  </body>

</html>
