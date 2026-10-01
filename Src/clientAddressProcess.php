<?php

//  O arquivo envia os dados ao banco de dados com o ID do usuario
// para indentificação do usuario que esta alterando as informações
// e cruzamento de dados. Atualmente o código apenas envia os dados
// para o banco de dados através da classe ClientAddressDB mas ele
// NÃO ALTERA AS LINHAS ele apenas adiciona, sua função como 
// desenvolvedor é antes de enviar os dados para o banco (linha 39)
// verificar se já existe um ID igual ao ID recebido e caso exista
// apagar a linha e adicionar uma nova linha com os novos dados ou
// atualizar os dados existentes.

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

function checkIfDataExists($pdo, $clientId)
{
    $db = new ClientAddressDB($pdo);
    return $db->addressExistsByID($clientId);
}

function convertValueToCorrectType($value, $type)
{//é presuposto que tu nao vai ser um maluco e por um bool nessa coisa aqui pq tbm é papo de largar o curso

    switch($type){
        case 'int':
            if (preg_match('/^[0-9]+$/', $value)){ //caso a variavel contenha letras da erro e retorna o valor inicial
                throw new InvalidArgumentException("01ERRO DE CONVERCAO: variavel contém letras");
                return $value;
            }
            return (int)$value;
        case 'string':
            return (string)$value;
        default:
            throw new InvalidArgumentException("Tipo de dado inválido: $type");
    }
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

    //conversao de valores para seu tipo correto

    $clientId     = convertValueToCorrectType($clientId, 'int');
    $number       = convertValueToCorrectType($number, 'int');
    $cep          = convertValueToCorrectType($cep, 'int');

    $db = new ClientAddressDB($pdo);

    $addressData = [
        'clt_id' => $clientId,
        'ld_number' => $number,
        'ld_road' => $road,
        'ld_neighborhood' => $neighborhood,
        'ld_city' => $city,
        'ld_state' => $state,
        'ld_country' => $country,
        'ld_cep' => $cep
    ];

    $fieldsToCheck = [
        'ld_number',
        'ld_road',
        'ld_neighborhood',
        'ld_city',
        'ld_state',
        'ld_country',
        'ld_cep'
    ];

    $nullFields = $db->catchNullAddressFields(
        $fieldsToCheck,
        $addressData
    );

    if (!empty($nullFields)) {
        throw new InvalidArgumentException(
            "Existem campos obrigatórios vazios: " . implode(', ', $nullFields)
        );
    }
/*
foreach ($addressData as $key => $value) {
                    if (is_null($value) || !isset($value)) {
                        switch ($key) {
                            case is_string($value):
                                $value = 'Não informado';
                                break;
                            case is_int($value):
                                $value = 0;
                                break;
                            default:
                                $value = 'Não informado';
                        }
                    }
                }
*/
    if (checkIfDataExists($pdo, $clientId)) {
        foreach ($addressData as $key => $value) {
            if (is_null($value) || !isset($value)) {
                switch ($key) {
                    case is_string($value):
                        $value = 'Não informado';
                        break;
                    case is_int($value):
                        $value = 0;
                        break;
                    default:
                        $value = 'Não informado';
                }
            }
        }
        $db->updateAddress(
            new ClientAddress(
                $clientId,
                $number,
                $road,
                $neighborhood,
                $city,
                $state,
                $country,
                $cep
            )
        );
    } else {
        $db->createAddress(
            $clientId,
            $number,
            $road,
            $neighborhood,
            $city,
            $state,
            $country,
            $cep
        );
    }

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