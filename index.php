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
            <h1 id="main-title">Rent-a-Car</h1>
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
                        echo "<tr>" . $client->toString("td") . "</tr>";
                    }

                    echo "</table >";
                }
            ?>
        </div>
        <div class="tab" id="cars">
            <?php
                $cars = getCars($db);
                if (!$cars) {
                    echo $db->getError();
                } else {
                    echo "<table>
                            <tr>
                                <th>Placa</th>
                                <th>Ano</th>
                                <th>Modelo</th>
                                <th>Descrição</th>
                                <th>Km Rodados</th>
                                <th>R$/Km</th>
                                <th>Taxa diária</th>
                                <th>Observações</th>
                            </tr>";

                    foreach ($cars as $car) {
                        echo "<tr>" . $car->toString("td") . "</tr>";
                    }

                    echo "</table>";
                }
            ?>
        </div>
        <div class="tab" id="rents">
            <?php
                $rents = getRents($db);
                if (!$rents) {
                    echo $db->getError();
                } else {
                    echo "<table>
                            <tr>
                                <th>CPF do Cliente</th>
                                <th>Carro</th>
                                <th>Data de Aluguel</th>
                                <th>Data de Devolução</th>
                            </tr>";

                    foreach ($rents as $rent) {
                        echo "<tr>" . $rent->toString("td") . "</tr>";
                    }

                    echo "</table>";
                }
            ?>
        </div>
    </section>
</body>

</html>
