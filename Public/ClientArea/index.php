<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Do Cliente</title>
    <link href="../style_index.css" rel="stylesheet">
</head>
<body>
    <h1>Informações de envio</h1>
    <section>
        <div>
          <?php
          //------------conectar--------------+
            $pdo = Connection::conectar();#   |
          //----------------------------------+
          
            //---===Declaração de vars===----
            $CEP = 'Não registrado';
            //-------------------------------

            $db = new ClientAddressDB($pdo);
            $clientAddress = $db->getAddressByClientID($_SESSION['client_object']->getId());
            if ($clientAddress !== null) {
              $CEP = $clientAddress->getCEP();
            }

          //-----------desconectar------------+
            $pdo = null;#                     |
          //----------------------------------+
          ?>
          <form action="">
            <label for="">CEP: </label>
            <input type="text" value="<?= htmlspecialchars((string) $CEP) ?>">
          </form>
        </div>
      </section>
</body>
</html>