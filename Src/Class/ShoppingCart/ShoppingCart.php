<?php

require_once __DIR__ . "/ShoppingCartItem.php";

class ProductDoesntExist extends Exception
{
}

class ShoppingCartItemNotSet extends Exception
{
}

class ShoppingCart
{
    //NOTE: design decision that needs to be made: shall i verify if the amount of an item in the shopping cart is bigger than the maximum (the amount in stock) whenever the amount changes or with some function that does that once when called
    //
    //definição de que o array só pode ter shoppingCartItem:
    /**
    * @var ShoppingCartItem[]
    */
    protected array $shoppingCartItems = [];

    public function __construct()
    {
    }

    public function getProducts(): array
    {
        return $this->shoppingCartItems;
    }

    public function getProductByID(int $id): Product
    {
        if (!isset($this->shoppingCartItems[$id])) {
            throw new ShoppingCartItemNotSet("");
        }
        return $this->shoppingCartItems[$id]->getProduct();
    }

    public function setProducts(array $newProducts)
    {
        $this->shoppingCartItems = $newProducts;
    }

    public function addProduct(Product $newProduct, int $amount = 1)
    {
        $newProductId = $newProduct->getId();

        if (isset($this->shoppingCartItems[$newProductId])) {
            $oldAmount = $this->shoppingCartItems[$newProductId]->getAmount();
            $this->shoppingCartItems[$newProductId]->setAmount($oldAmount + $amount);
            return;
        }
        $shoppingCartItem = new ShoppingCartItem($newProduct, $amount);
        $this->shoppingCartItems[$newProductId] = $shoppingCartItem;
    }

    public function removeProduct(Product $id)
    {
        if (!isset($this->shoppingCartItems[$id])) {
            throw new ShoppingCartItemNotSet("");
        }
        unset($this->shoppingCartItems[$id]);
    }

    public function addProductById(int $productId, int $amount = 1)
    {
        if (isset($this->shoppingCartItems[$productId])) {
            $oldAmount = $this->shoppingCartItems[$productId]->getAmount();
            $this->shoppingCartItems[$productId]->setAmount($oldAmount + $amount);
            return;
        }

        $pdo = new ProductDB(Connection::conectar());
        $productDB = new ProductDB($pdo);
        $product = $productDB->getProductByID($productId);
        if (is_null($product)) {
            throw new ProductDoesntExist("");
        }
        $this->shoppingCartItems[$product->getId()] = new ShoppingCartItem($product, $amount);
    }

    public function setProductAmount(int $id, int $amount, bool $deleteIfNone = false)
    {
        if (!isset($this->shoppingCartItems[$id])) {
            throw new ShoppingCartItemNotSet("");
        }
        if ($deleteIfNone && $amount <= 0) {
            unset($this->shoppingCartItems[$id]);
        }
        $this->shoppingCartItems[$id]->setAmount($amount);
    }

    public function getProductAmount(int $id): int
    {
        if (!isset($this->shoppingCartItems[$id])) {
            throw new ShoppingCartItemNotSet("");
        }
        return $this->shoppingCartItems[$id]->getAmount();
    }
}
