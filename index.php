<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php
        require("php/DAO.php");
        $db = getDatabase();
        $err = null;
        if (!$db->connect()) {
            $err = $db->getError();
        }
    ?>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--    <link rel="icon" href="img/logo.png" type="image/x-icon">-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css"/>
    <!-- PRESET BY GIANCARL021 -->
    <!--    <script src="js/HomeCanvas.js"></script>-->
    <script src="js/script.js"></script>
</head>

<body onload="init()">
    <header>
        <div class="tab-selector" onclick="changeTab('home', this)">
            Home
        </div>
        <div class="tab-selector" onclick="changeTab('clients', this)">
            Clientes
        </div>
        <div class="tab-selector" onclick="changeTab('cars', this)">
            Carros
        </div>
        <div class="tab-selector" onclick="changeTab('rents', this)">
            Aluguéis
        </div>

    </header>
    <section>
        <div class="tab" id="home">
            HOME
        </div>
        <div class="tab" id="clients">
            <?php
                $clients = getClients($db);
                if (!$clients) {
                    echo $db->getError();
                } else {
                    echo "<table>
                            <tr>
                                <th>CPF</th>
                                <th>Nome</th>
                                <th>Endereço</th>
                                <th>Telefone</th>
                                <th>Dívida</th>
                            </tr>";

                    foreach ($clients as $client) {
                        echo "<tr>". $client->toString("td") . "</tr>";
                    }

                    echo "</table >";
                }
            ?>
        </div>
        <div class="tab" id="cars">
            B
        </div>
        <div class="tab" id="rents">
            C
        </div>
    </section>
</body>

</html>
