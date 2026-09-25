<?php

//  O arquivo envia os dados ao banco de dados com o ID do usuario
// para indentificação do usuario que esta alterando as informações
// e cruzamento de dados. Atualmente o código apenas envia os dados
// para o banco de dados através da classe ClientAddressDB mas ele
// NÃO ALTERA AS LINHAS ele apenas adiciona, sua função como 
// desenvolvedor é antes de enviar os dados para o banco (linha 39)
// verificar se já existe um ID igual ao ID recebido e caso exista
// apagar a linha e adicionar uma nova linha com os novos dados.

require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "/Class/ClientAddress/ClientAddressDB.php";

function connectToDatabase()
{
    $pdo = Connection::conectar();
    return $pdo;
}

function disconnectFromDatabase(&$pdo)
{
    $pdo = null;
}

//----------------===========================-------------------

try {
    $pdo = connectToDatabase();

    $clientId     = isset($_POST['id'])           ? $_POST['id']           : '';
    $cep          = isset($_POST['cep'])          ? $_POST['cep']          : '';
    $road         = isset($_POST['road'])         ? $_POST['road']         : '';
    $number       = isset($_POST['number'])       ? $_POST['number']       : '';
    $neighborhood = isset($_POST['neighborhood']) ? $_POST['neighborhood'] : '';
    $city         = isset($_POST['city'])         ? $_POST['city']         : '';
    $state        = isset($_POST['state'])        ? $_POST['state']        : '';
    $country      = isset($_POST['country'])      ? $_POST['country']      : '';

    $db = new ClientAddressDB($pdo);

    $obj_endereco = $db->createAddress(
        (int)$clientId, 
        (int)$number, 
        $road, 
        $neighborhood, 
        $city, 
        $state, 
        $country,
        (int)$cep
    );

    disconnectFromDatabase($pdo);

    header("Location: ../Public/Client/index.php?CAPsuccess=true");
    exit();

} catch (Exception $e) {
    // Caso ocorra qualquer erro no try, o código cai aqui
    if (isset($pdo)) {disconnectFromDatabase($pdo);}

    error_log($e->getMessage());

    header("Location: ../Public/Client/index.php?CAPerror=false");
    exit();
}