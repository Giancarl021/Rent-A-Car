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
    <title>Rent-A-Car</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!--    <link rel="icon" href="img/logo.png" type="image/x-icon">-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/style.css"/>
    <!-- PRESET BY GIANCARL021 -->
    <script src="js/script.js"></script>
</head>

<body onload="init()">
    <header>
        <div class="tab-selector" id="home-selector" onclick="changeTab('home', this)">
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
            <div class="sub-tab">
                <button type="button" class="filter-selector"
                        onclick="filter('client', 1, 'tb-clients'); selectButton(this)">Clientes com dívidas
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('client', 2, 'tb-clients'); selectButton(this)">Clientes sem dívidas
                </button>
                <button type="button" class="filter-selector button-selected"
                        onclick="filter('client', 0, 'tb-clients'); selectButton(this)">Todos os Clientes
                </button>
                <button class="sub-tab-button" onclick="addRow('clients')">Adicionar Cliente</button>
                <div class="sub-tab-info">
                    Total das dívidas dos clientes: R$
                    <?php
                        echo getDebtFromClients($db);
                    ?>
                </div>
            </div>
            <table id='tb-clients'>
                <tr>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Dívida</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    $clients = getClients($db);
                    if (!$clients) {
                        echo "</table>";
                        echo $db->getError();
                    } else {
                        foreach ($clients as $client) {
                            echo "<tr>" . $client->toString("td") .
                                "<td><button class='table-button edit-button' type='button'><img src='img/edit.svg' alt='Edit'/></button></td>" .
                                "<td><button class='table-button delete-button' type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>";
                        }

                        echo "</table>";
                    }
                ?>
        </div>
        <div class="tab" id="cars">
            <div class="sub-tab">
                <button type="button" class="filter-selector" onclick="filter('car', 1, 'tb-cars'); selectButton(this)">
                    Carros Alugados
                </button>
                <button type="button" class="filter-selector" onclick="filter('car', 2, 'tb-cars'); selectButton(this)">
                    Carros Disponíveis
                </button>
                <button type="button" class="filter-selector button-selected"
                        onclick="filter('car', 0, 'tb-cars'); selectButton(this)">Todos os Carros
                </button>
                <button class="sub-tab-button" onclick="addRow('cars')">Adicionar Carro</button>
            </div>
            <table id="tb-cars">
                <tr>
                    <th>Placa</th>
                    <th>Ano</th>
                    <th>Modelo</th>
                    <th>Descrição</th>
                    <th>Km Rodados</th>
                    <th>R$/Km</th>
                    <th>Taxa diária</th>
                    <th>Observações</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    $cars = getCars($db);
                    if (!$cars) {
                        echo "</table>";
                        echo $db->getError();
                    } else {
                        foreach ($cars as $car) {
                            echo "<tr>" . $car->toString("td") .
                                "<td><button class='table-button edit-button' type='button'><img src='img/edit.svg' alt='Edit'/></button></td>" .
                                "<td><button class='table-button delete-button' type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>";
                        }

                        echo "</table>";
                    }
                ?>
        </div>
        <div class="tab" id="rents">
            <div class="sub-tab">
                <button type="button" class="filter-selector button-selected"
                        onclick="filter('rent', 1, 'tb-rents'); selectButton(this)">Aluguéis em aberto
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('rent', 2, 'tb-rents'); selectButton(this)">Aluguéis fechados
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('rent', 0, 'tb-rents'); selectButton(this)">Todos os Aluguéis
                </button>
                <button class="sub-tab-button" onclick="addRow('rents')">Adicionar Aluguel</button>
            </div>
            <table id="tb-rents">
                <tr>
                    <th>CPF do Cliente</th>
                    <th>Carro</th>
                    <th>Data de Aluguel</th>
                    <th>Data de Devolução</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php
                    $rents = getRents($db);
                    if (!$rents) {
                        echo "</table>";
                        echo $db->getError();
                    } else {
                        foreach ($rents as $rent) {
                            echo "<tr>" . $rent->toString("td") .
                                "<td><button class='table-button edit-button' type='button'><img src='img/edit.svg' alt='Edit'/></button></td>" .
                                "<td><button class='table-button delete-button' type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>";
                        }

                        echo "</table>";
                    }
                ?>
        </div>
    </section>
    <div id="modal" style="pointer-events: none; opacity: 0"></div>
</body>

</html>
