<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php
        require("php/DAO.php");
    ?>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--    <link rel="icon" href="img/logo.png" type="image/x-icon">-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css"/>
    <!-- PRESET BY GIANCARL021 -->
    <script src="js/script.js"></script>
</head>

<body>
    <h1>RentaCar</h1>
    Listagem:
    <?php
        $db = getDatabase();
        $db->connect();
        $cc = getClients($db);
        echo "<table><tr><th>CPF</th><th>NOME</th><th>ENDEREÇO</th><th>TELEFONE</th><th>DÍVIDA</th></tr>";
        foreach ($cc as $c) {
            echo $c->toString("td");
        }
        echo "</table>";
    ?>
</body>

</html>