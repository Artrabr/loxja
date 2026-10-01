<?php
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

//=========================codigo================================

    //check se a data chegou

    //verificar se já existe um produto com o mesmo id

    //addicionar mais uma unidade do produto ou    
    //adicionar novo produto

?>