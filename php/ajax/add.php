<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    if (!isset($data["row"]) || !isset($data["table"])) throwError("Parameter not founded");

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

    if(!isset($types[$data["table"]])) throwError("Table not founded");

    foreach ($data["row"] as $key => $value) {
        if (!isset($types[$data["table"]][$key])) throwError("Parameter not founded");

        $paramConfig = $types[$data["table"]][$key];

        $val = matchParam($db, $paramConfig, $value, $key);

        if (!is_null($value)) {
            array_push($values, $val);
            array_push($columns, $key);
        }
    }
    $q = $db->query("insert into " . $data["table"] . "(" . implode(",", $columns) . ") values (" . implode(",", $values) . ")");
    if (!$q) throwError("Insert Error: " . $db->getError());

    echo json_encode($r);