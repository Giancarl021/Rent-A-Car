<?php
    require("model/database.php");
    require("model/objects.php");

    global $connectionData, $db;
    $GLOBALS["connectionData"] = new ConnectionData(
        "localhost",
        "root",
        "",
        "db_rentacar"
    );

    $GLOBALS["database"] = new Database($GLOBALS["connectionData"]);

    function getDatabase() {
        return $GLOBALS["database"];
    }

    function getCars($database) {
        $q = $database->query("select distinct car.carPlate, car.carYear, car.model, car.description, car.km, car.kmPrice, car.dailyTax, car.observations from Car left join Rent as r on r.carPlate = car.carPlate where r.devolutionDate <> '' or r.id is null order by car.model, car.carPlate");
        if (!$q) {
            return false;
        }
        $r = [];
        while ($arr = mysqli_fetch_array($q)) {
            array_push($r, new Car(
                $arr["carPlate"],
                $arr["carYear"],
                $arr["model"],
                $arr["description"],
                $arr["km"],
                $arr["kmPrice"],
                $arr["dailyTax"],
                $arr["observations"]
            ));
        }
        return $r;
    }

    function getRents($database) {
        $q = $database->query("select * from Rent where devolutionDate = '' order by devolutionDate, id");
        if (!$q) {
            return false;
        }
        $r = [];
        while ($arr = mysqli_fetch_array($q)) {
            array_push($r, new Rent(
                $arr["id"],
                $arr["clientCpf"],
                $arr["carPlate"],
                $arr["initDate"],
                $arr["devolutionDate"]
            ));
        }
        return $r;
    }

    function getClients($database) {
        $q = $database->query("select * from Client order by name, cpf");
        if (!$q) {
            return false;
        }
        $r = [];
        while ($arr = mysqli_fetch_array($q)) {
            array_push($r, new Client(
                $arr["cpf"],
                $arr["name"],
                $arr["address"],
                $arr["telephone"],
                $arr["debt"]
            ));
        }
        return $r;
    }