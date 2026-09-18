<?php

use PDO;
class ClientAddress
{
    private ?array $data = null; //ou é definido como array ou como null pra evitar bug
    private PDO $pdo;
    private $client;

    public function __construct(PDO $pdo, $client)
    {
        $this->pdo = $pdo;
        $this->client = $client;
    }

    private function row(): array
    {
        if ($this->data === null) {
            $stmt = $this->pdo->prepare(
                "SELECT ld_number, ld_road, ld_neighborhood, ld_city,
                ld_state, ld_contry, ld_cep
                FROM LocationData WHERE clt_id = :id"
            );
            $stmt->execute(['id' => $this->client->getId()]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->data = $result ?: []; // linha ausente => array vazio; so pra nao dar um erro ele da um vazio dai nao popa na tela
        }

        return $this->data;
    }

    private function field(string $key): string
    {
        return $this->row()[$key] ?? "Não informado";
    }

    public function getNumber(): string       
    { 
        return $this->field('ld_number');
    }

    public function getRoad(): string         
    {
         return $this->field('ld_road');
    }

    public function getNeighborhood(): string 
    {
         return $this->field('ld_neighborhood'); 
    }

    public function getCity(): string         
    { 
        return $this->field('ld_city'); 
    }

    public function getState(): string        
    { 
        return $this->field('ld_state'); 
    }

    public function getCountry(): string      
    { 
        return $this->field('ld_contry'); 
    }

    public function getCEP(): string          
    { 
        return $this->field('ld_cep'); 
    }

    public function getFull(): string
    {
        return implode(', ', [
            $this->getRoad(),
            $this->getNumber(),
            $this->getNeighborhood(),
            $this->getCity(),
            $this->getState(),
            $this->getCountry(),
        ]);
    }
}