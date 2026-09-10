<?php

require_once __DIR__ . "/../../Class/Client/ClientDB.php";
require_once __DIR__ . "/../ConnectionTests.php";
require_once __DIR__ . "/../../../bootstrap.php";

ConnectionTests::deleteDBIfItExists();
$pdo = ConnectionTests::InitializeScheme();


$testResults = [
    "getClientByID" => false,
    "getClientByEmail" => false,
    "createClient" => false,
    "updateClient" => false,
    "isRightPassword" => false
];

$clientDB = new ClientDB($pdo);

$clientName = "um cara ai";
$clientEmail = "email@deumcara.com";
$clientPassword = "senha";


try {
    $client = $clientDB->createClient($clientName, $clientEmail, $clientPassword);
} catch (Exception $e) {
    $testResults['createClient'] = true;
}

$testResults['isRightPassword'] = !$clientDB->isRightPassword($clientEmail, $clientPassword);

try {
    $new_client = $clientDB->getClientByEmail($clientEmail);
    $testResults['getClientByEmail'] = !($new_client == $client);
} catch (Exception $e) {
    $testResults['getClientByEmail'] = true;
}

try {
    $new_client = $clientDB->getClientByID($client->getId());
    $testResults['getClientByID'] = !($new_client == $client);
} catch (Exception $e) {
    $testResults['getClientByID'] = true;
}

//altera o cliente; ultimo a rodar
try {
    $client->setEmail("emailnovo2@email.com");
    $clientDB->updateClient($client);
    $return = $clientDB->getClientByEmail("emailnovo2@email.com");
    if (is_null($return) && $return != $client) {
        $testResults['updateClient'] = true;
    }
} catch (Exception $e) {
    $testResults['updateClient'] = true;
}


return $testResults;
