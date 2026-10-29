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
$senha = trim($_POST['senha'] ?? '');
$isImageOnlyUpdate = ($_POST['action'] ?? '') === 'upload_image';

if ($isImageOnlyUpdate) {
    $nome = $client->getName();
    $email = $client->getEmail();
}

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

if (!$isImageOnlyUpdate && strlen($senha) < 6) {
    $_SESSION['erro'] = 'A senha precisa ter ao menos 6 caracteres.';
    header('Location: index.php');
    exit;
}

$client->setName($nome);
$client->setEmail($email);

$maxImageSize = 5 * 1024 * 1024;
$allowedImageTypes = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];
$uploadedImagePath = null;
$oldImageFilename = $client->getImageFilename();
$saved = false;

try {
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload = $_FILES['imagem'];
        if (
            !is_array($upload)
            || !isset($upload['error'], $upload['size'], $upload['tmp_name'])
            || !is_int($upload['error'])
            || !is_numeric($upload['size'])
            || !is_string($upload['tmp_name'])
        ) {
            throw new RuntimeException('O arquivo enviado é inválido.');
        }

        if ($upload['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Não foi possível enviar a imagem. Tente novamente.');
        }

        if ($upload['size'] > $maxImageSize) {
            throw new RuntimeException('A imagem deve ter no máximo 5 MB.');
        }

        $imageInfo = getimagesize($upload['tmp_name']);
        $mimeType = $imageInfo['mime'] ?? '';
        if ($imageInfo === false || !isset($allowedImageTypes[$mimeType])) {
            throw new RuntimeException('A imagem deve estar no formato JPG, PNG ou WebP.');
        }

        $uploadDirectory = __DIR__ . '/../images/client';
        if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
            throw new RuntimeException('Não foi possível preparar o armazenamento da imagem.');
        }

        $imageFilename = bin2hex(random_bytes(16)) . '.' . $allowedImageTypes[$mimeType];
        $uploadedImagePath = $uploadDirectory . DIRECTORY_SEPARATOR . $imageFilename;
        if (!move_uploaded_file($upload['tmp_name'], $uploadedImagePath)) {
            throw new RuntimeException('Não foi possível salvar a imagem enviada.');
        }

        $client->setImageFilename($imageFilename);
    }

    if ($isImageOnlyUpdate && $uploadedImagePath === null) {
        throw new RuntimeException('Escolha uma imagem antes de enviar.');
    }

    $pdo->beginTransaction();
    $clientDB->updateClient($client);
    if (!$isImageOnlyUpdate) {
        $clientDB->changeClientPasswordById($senha, $client->getId());
    }
    $pdo->commit();
    $saved = true;

    $_SESSION['client_object'] = $client;
    $_SESSION['msg'] = $isImageOnlyUpdate
        ? 'Foto de perfil atualizada com sucesso!'
        : 'Dados atualizados com sucesso!';

    if ($oldImageFilename !== null && $oldImageFilename !== $client->getImageFilename()) {
        $oldImagePath = __DIR__ . '/../images/client/' . $oldImageFilename;
        if (is_file($oldImagePath) && !unlink($oldImagePath)) {
            error_log('Não foi possível remover a imagem de perfil antiga: ' . $oldImagePath);
        }
    }
} catch (DuplicateEmail $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['erro'] = 'Este email já está sendo usado por outro cliente.';
} catch (RuntimeException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['erro'] = $e->getMessage();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Erro ao atualizar perfil: ' . $e->getMessage());
    $_SESSION['erro'] = 'Ocorreu um erro ao salvar os dados. Tente novamente.';
} finally {
    if (!$saved && $uploadedImagePath !== null && is_file($uploadedImagePath) && !unlink($uploadedImagePath)) {
        error_log('Não foi possível remover uma imagem de perfil não salva: ' . $uploadedImagePath);
    }
}

$pdo = null;
header('Location: index.php');
exit;