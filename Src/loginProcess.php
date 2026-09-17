<?php

require_once __DIR__ . "/Connection.php";
require_once __DIR__ . "../Class/Client/ClientDB.php";

function validateData($post, array $input){
    for($i = 0; $i < count($input); $i++){
        if(!isset($post[$input[$i]]) || empty($post[$input[$i]])){
            return false;
        }
    }
    return true;
}

function connectToDatabase()
{
    $pdo = Connection::conectar();
    return $pdo;
}

function disconnectFromDatabase($pdo)
{
    $pdo = null;
}

function getPassword($cliente){ //retorna a senha do banco de dados do cliente
    $id = $client::getId();
    $smt = $pdo->prepare("SELECT clt_password FROM Client WHERE clt_id = :id");
    $smt->execute(['id' => $id]);
    return $smt->fetchColumn();
}

function ClientSession($cliente){
    session_start();
    $_SESSION["client_object"] = $cliente;
}

//--------------------------------
//            CODIGO
//--------------------------------

//verifica chegada de dados via POST
if(!validateData($_POST, ["email", "senha"])){
    header("Location: ../Public/Login/index.php?error=invalid_data");
    exit();
}

//setar variaveis
$login = $_POST["email"];
$password = $_POST["senha"];

$pdo = connectToDatabase();
//                                                                                              ___________
//pega o cliente pelo email                                                                     \_supreme_I______      
$cliente = ClientDB::getClientByEmail($login); //funcao da classe clientDB (linha 4)              /------\
//                                                                                               I o  o  I
//verifica se o cliente existe                                                                   \  v   /
if(!$cliente){//                                                                                  -----
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

header("Location: ../Public/index.php?success=login_success");
exit();