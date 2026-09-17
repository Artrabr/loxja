<?php

require_once __DIR__ . "/ShoppingCartItem.php";

class ShoppingCart
{
    //NOTE: design decision that needs to be made: shall i verify if the amount of an item in the shopping cart is bigger than the maximum (the amount in stock) whenever the amount changes or with some function that does that once when called
    //
    //definição de que o array só pode ter shoppingCartItem:
    /**
    * @var ShoppingCartItem[]
    */
    protected array $ShoppingCardItems = [];

    public function getProducts(): array
    {
        return $this->ShoppingCardItems;
    }

    public function getProductByID(int $id): Product
    {
        return $this->ShoppingCardItems[$id]->getProduct();
    }

    public function setProducts(array $newProducts)
    {
        $this->ShoppingCardItems = $newProducts;
    }

    public function addProduct(Product $newProduct, int $amount = 1)
    {
        $shoppingCartItem = new ShoppingCartItem($newProduct, $amount);
        $this->ShoppingCardItems[$newProduct->getId()] = $shoppingCartItem;
    }

    public function removeProduct(Product $id)
    {
        unset($this->ShoppingCardItems[$id]);
    }

    public function addProductById(int $productId, int $amount = 1)
    {
        //TODO: adicionar try catch com exceção aqui quando o método de pegar por id for implementado
        $pdo = new ProductDB(Connection::conectar());
        $productDB = new ProductDB($pdo);
        $product = $productDB->getProductByID($productId);
        $this->ShoppingCardItems[$product->getId()] = new ShoppingCartItem($product, $amount);
    }

    public function setProductAmount(int $id, int $amount, bool $deleteIfNone = false)
    {
        if ($deleteIfNone && $amount <= 0) {
            unset($this->ShoppingCardItems[$id]);
        }
        $this->ShoppingCardItems[$id]->setAmount($amount);
    }

    public function getProductAmount(int $id): int
    {
        return $this->ShoppingCardItems[$id]->getAmount();
    }
}
