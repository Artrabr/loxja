<?php

class SaleReport
{
    private int $id;
    private int $clientId;
    private int $productId;
    private string $method;
    private float $price;
    private string $date;
    private int $amount;

    public function __construct(
        int $id,
        int $clientId,
        int $productId,
        string $method,
        float $price,
        string $date,
        int $amount
    ) {
        $this->id = $id;
        $this->clientId = $clientId;
        $this->productId = $productId;
        $this->method = $method;
        $this->price = $price;
        $this->date = $date;
        $this->amount = $amount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId(): int
    {
        return $this->clientId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
