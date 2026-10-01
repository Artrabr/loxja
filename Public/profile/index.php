<?php
echo "oi fdp";
    session_start();
    if(!isset($_SESSION['client_object'])){
        header("Location: ../Login/login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <link href="../MVP.css" rel="stylesheet">
    <link href="../style_index.css" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if(!isset($SESSION["cliente_objeto"])) {
            header("../index.php");
        } else {
            echo <a href="deleteSession.php">deslogar brutalmente</a>
        }
    ?>
</body>

</html>