<?php
    require_once __DIR__ . "/../../bootstrap.php";
    if(!isset($_SESSION['client_object'])) {
        header("Location: ../Login/index.php?error=not_logged_in");
        exit();
    }
    /*if(!isset($_SESSION['adm'] == true)) {                              ADICIONAR APÓS O SISTEMA DE LOGIN DE ADM FICAR PRONTO!!!!!!!!!!!!
        header("Location: ../Login/index.php?error=notadm");
        exit();
    }*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Produto</title>
</head>
<body>
    <form action="../../Src/addProduct.php" method="POST" enctype="multipart/form-data">
        <label for="name">Nome</label>
        <input type="text" id="name" name="name" required>

        <label for="price">Preço</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="description">Descrição</label>
        <textarea id="description" name="description" required></textarea>

        <label for="quantity">Quantidade Disponível</label>
        <input type="number" id="quantity" name="quantity" min="0" required>

        <label for="image">Image</label>
        <input type="file" id="image" name="image" required>

        <label for="category">Categoria</label>
        <input type="text" id="category" name="category" required>

        <button type="submit">Adicionar Produto</button>
    </form>
</body>
</html>