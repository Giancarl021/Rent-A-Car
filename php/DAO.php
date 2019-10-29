<?php
    require("model/database.php");
    require("model/objects.php");

    global $connectionData, $database;
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
        $q = $database->query("select * from Car");
        if (!$q) {
            return false;
        }
        $r = [];
        for ($i = 0; $i < $q->num_rows; $i++) {
            $arr = mysqli_fetch_array($q);
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
        $q = $database->query("select * from Rent");
        if (!$q) {
            return false;
        }
        $r = [];
        for ($i = 0; $i < $q->num_rows; $i++) {
            $arr = mysqli_fetch_array($q);
            array_push($r, new Rent(
                $arr["clientCpf"],
                $arr["carPlate"],
                $arr["expired"],
                $arr["initDate"],
                $arr["expirationData"]
            ));
        }
        return $r;
    }

    function getClients($database) {
        $q = $database->query("select * from Client");
        if (!$q) {
            return false;
        }
        $r = [];
        for ($i = 0; $i < $q->num_rows; $i++) {
            $arr = mysqli_fetch_array($q);
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