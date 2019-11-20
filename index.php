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
    <link rel="icon" href="img/logo.png" type="image/x-icon">
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
                        onclick="filter('client', 1, 'tb-client'); selectButton(this)">Clientes com dívidas
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('client', 2, 'tb-client'); selectButton(this)">Clientes sem dívidas
                </button>
                <button type="button" class="filter-selector button-selected"
                        onclick="filter('client', 0, 'tb-client'); selectButton(this)">Todos os Clientes
                </button>
                <button class="sub-tab-button" onclick="addRow('clients')">Adicionar Cliente</button>
                <div class="sub-tab-info">
                    Total das dívidas dos clientes:
                    <span id="clientsDebt"></span>
                </div>
            </div>
            <table id='tb-client'>
                <tr>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Endereço</th>
                    <th>Telefone</th>
                    <th>Dívida</th>
                    <th class="button-th"></th>
                    <th class="button-th"></th>
                </tr>
                <?php
                    $clients = getClients($db);
                    if (!$clients) {
                        echo "</table>";
                        echo $db->getError();
                    } else {
                        foreach ($clients as $client) {
                            echo "<tr>" . $client->toString("td") .
                                "<td><button class='table-button edit-button' type='button' onclick=\"editRow('clients', this)\"><img src='img/edit.svg' alt='Edit'/></button></td>" .
                                "<td><button class='table-button delete-button' onclick=\"callConfirmWindow('Deseja excluir esta linha? Esta ação NÃO poderá ser desfeita!', databaseDelete, {table: 'client', origin: this})\" type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>";
                        }

                        echo "</table>";
                    }
                ?>
        </div>
        <div class="tab" id="cars">
            <div class="sub-tab">
                <button type="button" class="filter-selector" onclick="filter('car', 1, 'tb-car'); selectButton(this)">
                    Carros Alugados
                </button>
                <button type="button" class="filter-selector button-selected"
                        onclick="filter('car', 2, 'tb-car'); selectButton(this)">
                    Carros Disponíveis
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('car', 0, 'tb-car'); selectButton(this)">Todos os Carros
                </button>
                <button class="sub-tab-button" onclick="addRow('cars')">Adicionar Carro</button>
            </div>
            <table id="tb-car">
                <tr>
                    <th>Placa</th>
                    <th>Ano</th>
                    <th>Modelo</th>
                    <th>Descrição</th>
                    <th>Km Rodados</th>
                    <th>R$/Km</th>
                    <th>Taxa diária</th>
                    <th>Observações</th>
                    <th class="button-th"></th>
                    <th class="button-th"></th>
                </tr>
                <?php
                    $cars = getCars($db);
                    if (!$cars) {
                        echo "</table>";
                        echo $db->getError();
                    } else {
                        foreach ($cars as $car) {
                            echo "<tr>" . $car->toString("td") .
                                "<td><button class='table-button edit-button' type='button' onclick=\"editRow('cars', this)\"><img src='img/edit.svg' alt='Edit'/></button></td>" .
                                "<td><button class='table-button delete-button' onclick=\"callConfirmWindow('Deseja excluir esta linha? Esta ação NÃO poderá ser desfeita!', databaseDelete, {table: 'car', origin: this})\" type='button'><img src='img/remove.svg' alt='Edit'/></button></td></tr>";
                        }

                        echo "</table>";
                    }
                ?>
        </div>
        <div class="tab" id="rents">
            <div class="sub-tab">
                <button type="button" class="filter-selector button-selected"
                        onclick="filter('rent', 1, 'tb-rent'); selectButton(this)">Aluguéis Pendentes
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('rent', 2, 'tb-rent'); selectButton(this)">Aluguéis fechados
                </button>
                <button type="button" class="filter-selector"
                        onclick="filter('rent', 0, 'tb-rent'); selectButton(this)">Todos os Aluguéis
                </button>
                <button type="button" class="sub-tab-button" onclick="addRow('rents')">Registrar Aluguel</button>
            </div>
            <table id="tb-rent">
                <tr>
                    <th>ID</th>
                    <th>CPF do Cliente</th>
                    <th>Placa do Carro</th>
                    <th>Data de Aluguel</th>
                    <th>Data de Devolução</th>
                    <th class="button-th"></th>
                    <th class="button-th"></th>
                    <th class="button-th"></th>
                </tr>
                <?php
                    $rents = getRents($db);
                    if (!$rents) {
                        echo "</table>";
                        echo $db->getError();
                    } else {
                        foreach ($rents as $rent) {
                            echo "<tr>" . $rent->toString("td") .
                                "<td><button class='table-button edit-button' type='button' onclick=\"editRow('rents', this)\"><img src='img/edit.svg' alt='Edit'/></button></td>" .
                                "<td><button class='table-button delete-button' type='button' onclick=\"callConfirmWindow('Deseja excluir esta linha? Esta ação NÃO poderá ser desfeita!', databaseDelete, {table: 'rent', origin: this})\" type='button'><img src='img/remove.svg' alt='Edit'/></button></td>" .
                                "<td>" . ($rent->getDevolutionDate() === "0000-00-00 00:00:00" ? "<button class='table-button return-button' type='button'><img src='img/return.svg' alt='Return'/></button>" : "<button class='table-button return-button disabled-button' type='button'><img src='img/return.svg' alt='Return'/></button>"). "</td>" .
                                "</tr>";
                        }

                        echo "</table>";
                    }
                ?>
        </div>
    </section>
    <div id="modal" style="pointer-events: none; opacity: 0"></div>
    <div id="confirm" style="pointer-events: none; opacity: 0">
        <h1></h1>
        <button type="button" class="window-confirm-button">Excluir</button>
        <button type="button" onclick="closeConfirmWindow()">Cancelar</button>
    </div>
    <div id="toast" style="pointer-events: none; opacity: 0"></div>
</body>

</html>
