<?php

class Product
{
    protected int $id;
    protected string $name;
    protected float $price;
    protected string $description;
    protected int $amountAvailable;
    protected string $category;

    public function __construct(int $id, string $name, float $price, string $description, int $amountAvailable, string $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->amountAvailable = $amountAvailable;
        $this->category = $category;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price)
    {
        $this->price = $price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getAmountAvailable(): string
    {
        return $this->amountAvailable;
    }

    public function setAmountAvailable(string $amountAvailable)
    {
        $this->amountAvailable = $amountAvailable;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category)
    {
        $this->category = $category;
    }
}
