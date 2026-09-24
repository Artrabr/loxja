<?php

require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "/Class/Client/ClientDB.php";
require_once __DIR__ . "/Validation.php";

session_start();

function connectToDatabase()
{
    $pdo = Connection::conectar();
    return $pdo;
}

function disconnectFromDatabase($pdo)
{
    $pdo = null;
}

function getPassword(Client $client){ //retorna a senha do banco de dados do cliente
    $id = $client->getId();
    global $pdo;
    $smt = $pdo->prepare("SELECT clt_password FROM Client WHERE clt_id = :id");
    $smt->execute(['id' => $id]);
    return $smt->fetchColumn();
}

function ClientSession($cliente){
    $_SESSION["client_object"] = $cliente;
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
$login = trim((string) ($_POST["email"] ?? ''));
$password = (string) ($_POST["senha"] ?? '');

if ($name === '' || $login === '' || $password === '') {
    header("Location: ../Public/Registration/index.php?erro=camposobrigatorios");
    exit();
}

$pdo = connectToDatabase();
$db = new ClientDB($pdo);

$client = $db->getClientByEmail($login);
if ($client !== null) {
    disconnectFromDatabase($pdo);
    header("Location: ../Public/index.php?erro=usuarioexistente");
    exit();
}
$client = $db->createClient($name,$login,$password);
ClientSession($client);
disconnectFromDatabase($pdo);
header("Location: ../Public/index.php?success=login_success");
exit();