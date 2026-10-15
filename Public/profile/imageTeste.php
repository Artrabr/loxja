<?php
    require_once __DIR__ . "/../../bootstrap.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>trocar imagem ou adicioanr</title>
</head>
<body>
    <div>
        <form action="/submit-endpoint" method="post">
            <label for="submit-btn">Submit Form:</label>
            <input type="image" id="submit-btn" src="submit-button.png" alt="Submit Form">
        </form>
    </div>
</body>
</html>