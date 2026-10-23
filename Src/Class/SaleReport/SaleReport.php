<?php

require_once __DIR__ . "/../Client/ClientDB.php";
require_once __DIR__ . "/../Product/ProductDB.php";
require_once __DIR__ . "/../../Connection.php";

class SaleReport
{
    private int $id;
    private int $client_id;
    private int $product_id;
    private Client $client;
    private Product $product;
    private string $method;
    private float $price;
    private string $date;
    private int $amount;

    public function __construct(
        int $id,
        int $client_id,
        int $product_id,
        Client $client,
        Product $product,
        string $method,
        float $price,
        string $date,
        int $amount,
    ) {
        $this->id = $id;
        $this->client_id = $client_id;
        $this->product_id = $product_id;
        $this->client = $client;
        $this->product = $product;
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
        return $this->client_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getProduct(): Product
    {
        return $this->product;
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

    public function getTotal(): float
    {
        return $this->price * $this->amount;
    }
}

