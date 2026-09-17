<?php

function getNumber($post, $cliente) :string 
{
    $stmt = $pdo->prepare("SELECT ld_number FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getRoad($post, $cliente) :string 
{
    $stmt = $pdo->prepare("SELECT ld_road FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getNeighborhood($post, $cliente) :string 
{
    $stmt = $pdo->prepare("SELECT ld_neighborhood FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getCity($post, $cliente) :string 
{
    $stmt = $pdo->prepare("SELECT lb_city FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getState($post, $cliente) :string 
{
    $stmt = $pdo->prepare("SELECT ld_state FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getContry($post, $cliente) :string 
{
    $stmt = $pdo->prepare("SELECT ld_contry FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getCEP($post, $cliente) :string  
{
    $stmt = $pdo->prepare("SELECT ld_cep FROM LocationData WHERE clt_id = :id");
    $stmt->execute(['id' => $cliente->getId()]); 
    $outputDB = $stmt->fetchColumn();

    if(!$outputDB){
        $output = "Não informado";
    }

    return $output;
}

function getAdress($post, $cliente) :string 
{
    $number       = getNumber($post, $cliente);
    $road         = getRoad($post, $cliente);
    $neighborhood = getNeighborhood($post, $cliente);
    $city         = getCity($post, $cliente);
    $state        = getState($post, $cliente);
    $contry       = getContry($post, $cliente);

    $output =  $road . ',' . $number . ',' . $neighborhood . ',' . $city . ',' . $state . ',' $contry;
}