<?php

require_once __DIR__ . "/../../Class/Client/Client.php";
require_once __DIR__ . "/../ConnectionTests.php";

require_once __DIR__ . "/../../../bootstrap.php";

$pdo = ConnectionTests::createDBIfNeededAndConnect();

ConnectionTests::deleteDBIfItExists();
