<?php
    require("../DAO.php");

    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    $data = json_decode($_REQUEST["data"], true);

    $r = [
        "error" => null,
        "elementId" => "tb-" . $data["table"]
    ];

    $db = getDatabase();

    if (!$db->connect()) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    $columns = [];
    $values = [];

    $types = [
        "client" => Client::getParamConfigs(),
        "car" => Car::getParamConfigs(),
        "rent" => Rent::getParamConfigs()
    ];

    foreach ($data["row"] as $key => $value) {
        if (!isset($types[$data["table"]][$key])) {
            echo "{\"error\": \"Parameter Not Founded\"}";
            die;
        }

        $paramConfig = $types[$data["table"]][$key];

        if (!$paramConfig->match($value)) {
            echo "{\"error\": \"Parameter Parse Error: $key - " . $paramConfig->getError() . "\"}";
            die;
        }
        $val = "";
        switch ($paramConfig->getType()) {
            case "string":
                $val = "'$value'";
                break;
            default:
                $val = $value;
        }
        if (!is_null($value)) {
            array_push($values, $val);
            array_push($columns, $key);
        }
    }

    $q = $db->query("insert into " . $data["table"] . "(" . implode(",", $columns) . ") values (" . implode(",", $values) . ")");
    if (!$q) {
        $r["error"] = "Insert Error: " . $db->getError();
        echo json_encode($r);
        die;
    }

    $r["result"] = [];

    $q = $db->query("select * from " . $data["table"]);
    if (!$q) {
        $r["error"] = "Select Error: " . $db->getError();
        echo json_encode($r);
        die;
    }

    while ($item = mysqli_fetch_array($q)) {
        array_push($r["result"], $item);
    }

    echo json_encode($r);