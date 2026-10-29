<?php

require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "/Class/Client/ClientDB.php";
require_once __DIR__ . "/Validation.php";

function connectToDatabase()
{
    $pdo = Connection::conectar();
    return $pdo;
}


//--------------------------------
//            CODIGO
//--------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../Public/Registration/index.php");
    exit();
}

// Campos obrigatórios enviados pelo formulário de cadastro.
$name = trim((string) ($_POST["nome"] ?? ''));
$email = trim((string) ($_POST["email"] ?? ''));
$password = (string) ($_POST["senha"] ?? '');

$valid_name = false;
try {
    $valid_name = Validation::isValidUsername($name);
} catch (\Throwable $th) {
    $valid_name = false;
}
$valid_email = false;
try {
    $valid_email = Validation::isValidEmail($email);
} catch (\Throwable $th) {
    $valid_email = false;
}
$valid_password = false;
try {
    $valid_password = Validation::isValidPassword($password);
} catch (\Throwable $th) {
    $valid_password = false;
}

if (!$valid_name || !$valid_email || !$valid_password) {
    header("Location: ../Public/Registration/index.php?erro=camposobrigatorios");
    exit();
}

$pdo = connectToDatabase();
$db_client = new ClientDB($pdo);

try {
    $client = $db_client->createClient($name, $email, $password);
} catch (DuplicateEmail $e) {
    header("Location: ../Public/index.php?erro=usuarioexistente");
    exit();
}

$_SESSION["client_object"] = $client;
header("Location: ../Public/index.php?success=login_success");
exit();
