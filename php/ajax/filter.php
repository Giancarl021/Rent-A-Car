<?php
    require("../DAO.php");
    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    $data = json_decode($_REQUEST["data"], true);

    $r = [
        "error" => null,
        "elementId" => $data["elementId"]
    ];

    $db = getDatabase();
    if (!$db->connect()) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    $condition = $data["condition"];

    $table = $data["table"];
    $options = null;
    $select = null;
    if ($condition !== 0) {
        switch ($table) {
            case "client":
                if ($condition === 1) $options = "where debt <> '' and debt <> 0 order by name";
                else if ($condition === 2) $options = "where debt = '' or debt is null or debt = 0 order by name";
                break;
            case "car":
                if ($condition === 1) $options = "join Rent as r on r.carPlate = $table.carPlate and r.expirationDate = '' order by model";
                else if ($condition === 2) $options = "join Rent as r on r.carPlate = $table.carPlate and r.expirationDate <> '' order by model"; # CORRIGIR QUERY
                $select = "$table.carPlate, $table.carYear, $table.model, $table.description, $table.km, $table.kmPrice, $table.dailyTax, $table.observations";
                break;
            case "rent":
                if ($condition === 1) $options = "where expirationDate = '' order by carPlate";
                else if ($condition === 2) $options = "where expirationDate <> '' order by carPlate";
                break;
            default:
                $table = null;
        }
    }
    if (!is_null($table)) {
        $q = $db->query("select " . (is_null($select) ? "*" : $select) . " from $table" . ($condition === 0 ? "" : " $options"));
        if (!$q) {
            $r["error"] = $db->getError();
            echo json_encode($r);
            die;
        }
    } else {
        $r["error"] = "Table not founded";
        echo json_encode($r);
        die;
    }

    $r["result"] = [];

    while ($item = mysqli_fetch_array($q)) {
        array_push($r["result"], $item);
    }

    echo json_encode($r);