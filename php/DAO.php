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
        $q = $database->query("select * from Car order by model");
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
        $q = $database->query("select * from Rent where expirationDate = '' order by carPlate");
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
                $arr["expirationDate"]
            ));
        }
        return $r;
    }

    function getClients($database) {
        $q = $database->query("select * from Client order by name");
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