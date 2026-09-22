<?php

require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "../Class/Client/ClientDB.php";
require_once __DIR__ . "../Validation.php";

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

$admlogin = 'adm@cafe.com';

//verifica chegada de dados via POST
if(!Validation::isValidPassword($password) || !Validation::isValidEmail($email)){
    header("Location: ../Public/Login/index.php?error=invalid_data");
    exit();
}

//setar variaveis
$login = $_POST["email"];
$password = $_POST["senha"];

$pdo = connectToDatabase();
//                                                                                              ___________
$clientDB = new ClientDB($pdo);
//pega o cliente pelo email                                                                     \_supreme_I______      
$cliente = $clientDB->getClientByEmail($login); //funcao da classe clientDB (linha 4)              /------\
//                                                                                               I o  o  I
//verifica se o cliente existe                                                                   \  v   /
if(is_null($cliente)){//                                                                          -----
    header("Location: ../Public/Login/index.php?error=nonexistent_user");//                        /I\
    exit();//                                                                                     / I \ 
}//                                                                                              /  I  \
//                                                                                                 / \
//verifica se a senha está correta                                                                /   \
if(!password_verify($password, getPassword($cliente))){//                                        /     \
    header("Location: ../Public/Login/index.php?error=invalid_password");//                  ===============
    exit();//                                                                                    O      O
}

//da uma sessao ao cliente
ClientSession($cliente);

//disconecta do banco de dados
disconnectFromDatabase($pdo);

if($login == $admlogin){
    
}

header("Location: ../Public/index.php?success=login_success");
exit();