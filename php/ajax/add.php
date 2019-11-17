<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    $r = [
        "error" => null,
        "elementId" => "tb-" . $data["table"]
    ];

    $db = getDatabase();

    if (!$db->connect()) throwError($db->getError());

    $columns = [];
    $values = [];

    $types = [
        "client" => Client::getParamConfigs(),
        "car" => Car::getParamConfigs(),
        "rent" => Rent::getParamConfigs()
    ];

    foreach ($data["row"] as $key => $value) {
        if (!isset($types[$data["table"]][$key])) throwError("Parameter not founded");

        $paramConfig = $types[$data["table"]][$key];

        if (!$paramConfig->match($value)) throwError("Parameter Parse Error: $key - " . $paramConfig->getError());
        $val = $db->escapeString($value);

        if(is_null($val) && !is_null($value)) throwError($db->getError());

        switch ($paramConfig->getType()) {
            case "string":
                $val = "'$val'";
                break;
        }

        if (!is_null($value)) {
            array_push($values, $val);
            array_push($columns, $key);
        }
    }

    $q = $db->query("insert into " . $data["table"] . "(" . implode(",", $columns) . ") values (" . implode(",", $values) . ")");
    if (!$q) throwError("Insert Error: " . $db->getError());

    echo json_encode($r);