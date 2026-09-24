<?php

class ClientAddress
{
    private int $clientID;
    private int $number;
    private string $road;
    private string $neighborhood;
    private string $city;
    private string $state;
    private string $country;
    private string $CEP;

    public function __construct(
        int $clientID,
        int $number,
        string $road,
        string $neighborhood,
        string $city,
        string $state,
        string $contry,
        int $CEP
    ) {
        $this->clientID = $clientID;
        $this->number = $number;
        $this->road = $road;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->contry = $contry;
        $this->CEP = $CEP;
    }

    public function getId(): int
    {
        return $this->clientID;
    }

    public function getClientId(): int
    {
        return $this->clientID;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getRoad(): string
    {
        return $this->road;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getCountry(): string
    {
        return $this->contry;
    }

    public function getCEP(): int
    {
        return $this->CEP;
    }

    public function getFull(): string
    {
        return implode(', ', [
            $this->road,
            $this->number,
            $this->neighborhood,
            $this->city,
            $this->state,
            $this->contry,
        ]);
    }
}