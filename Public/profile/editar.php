<?php

require_once __DIR__ . '/../../Src/Class/Client/Client.php';
require_once __DIR__ . '/../../Src/Connection.php';
require_once __DIR__ . '/../../Src/Class/Client/ClientDB.php';
require_once __DIR__ . '/../../Src/Validation.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['client_object'])) {
    header('Location: ../Login/index.php?error=not_logged');
    exit;
}

$pdo = Connection::conectar();
$clientDB = new ClientDB($pdo);
$client = $clientDB->getClientByID((int) $_SESSION['client_object']->getId());

if (!$client) {
    session_destroy();
    header('Location: ../Login/index.php?error=nonexistent_user');
    exit;
}

$nome  = trim($_POST['nome']  ?? '');
$email = trim($_POST['email'] ?? '');

if (strlen($nome) < 3) {
    $_SESSION['erro'] = 'O nome precisa ter ao menos 3 caracteres.';
    header('Location: index.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['erro'] = 'Email inválido.';
    header('Location: index.php');
    exit;
}

// Atualiza o objeto
$client->setName($nome);
$client->setEmail($email);

try {
    $clientDB->updateClient($client);   // ← nome correto!
    $_SESSION['client_object'] = $client;
    $_SESSION['msg'] = 'Dados atualizados com sucesso!';
} catch (DuplicateEmail $e) {
    $_SESSION['erro'] = 'Este email já está sendo usado por outro cliente.';
} catch (Exception $e) {
    $_SESSION['erro'] = 'Erro ao atualizar: ' . $e->getMessage();
}

$pdo = null;
header('Location: index.php');
exit;