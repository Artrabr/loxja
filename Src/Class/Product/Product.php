<?php

class Product
{
    protected int $id;
    protected string $name;
    protected float $price;
    protected string $description;
    protected int $amountAvailable;
    protected string $image;
    protected string $category;

    // Keep image optional and after category so older callers that do not handle images remain compatible.
    public function __construct(int $id, string $name, float $price, string $description, int $amountAvailable, string $category, ?string $image = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
        $this->amountAvailable = $amountAvailable;
        $this->category = $category;
        $this->image = $image ?: '/loxja/Src/images/client/defaultimage.jpg';
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price)
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

    public function getAmountAvailable(): int
    {
        return $this->amountAvailable;
    }

    public function setAmountAvailable(int $amountAvailable)
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

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image) 
    {//ALTERAR O METODO DE SET PARA link/legal.edivertido/($VARIAVEL DE ENTRADA);
        $this->image = $image;
    }
}
