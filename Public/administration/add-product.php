<?php
    require_once __DIR__ . "/../../bootstrap.php";
    if(isset($_SESSION['client_object'])) {
        session_destroy();
    }
    /*    
    protected int $id;
    protected string $name;
    protected float $price;
    protected string $description;
    protected int $amountAvailable;
    protected string $category;
    */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
</head>
<body>
    <form action="../../Src/addProduct.php" method="POST">
        <label for="name">Nome</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Preço</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="description">Descrição</label>
        <textarea id="description" name="description" required></textarea>

        <label for="quantity">Quantidade Disponível</label>
        <input type="number" id="quantity" name="quantity" min="0" required>

        <label for="category">Categoria</label>
        <input type="text" id="category" name="category" required>

        <button type="submit">Adicionar Produto</button>
    </form>
</body>
</html>