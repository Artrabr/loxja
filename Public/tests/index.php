<?php
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
        "Client" => [
            "getId" => false,
            "getName" => false,
            "setName" => false,
            "getEmail" => false,
            "setEmail" => false
        ],
        "ClientDB" => [
            "getClientByID" => false,
            "getClientByEmail" => false,
            "createClient" => false,
            "updateClient" => false,
            "isRightPassword" => false
        ],
    ],
];

$testAmount = 0;
$errorCount = 0;
array_walk_recursive($results, function ($value, $key) use (&$errorCount, &$testAmount) {
    $testAmount++;
    if ($value === true) {
        $errorCount++;
    }
});

$results['Classes']['ClientDB'] = require_once __DIR__ . "/../../Src/Tests/Client/TestsClientDB.php";

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
