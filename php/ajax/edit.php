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

    $vars = [];

    $types = [
        "client" => Client::getParamConfigs(),
        "car" => Car::getParamConfigs(),
        "rent" => Rent::getParamConfigs()
    ];

    foreach ($data["row"] as $key => $value) {
        if (!isset($types[$data["table"]][$key])) throwError("Parameter not founded");

        $paramConfig = $types[$data["table"]][$key];

        $val = matchParam($db, $paramConfig, $value, $key);

        if (!is_null($value)) {
            $vars[$key] = $val;
        }
    }
    $r["query"] = "update " . $data["table"] . "(" . implode(",", $columns) . ") values (" . implode(",", $values) . ")";
    $q = $db->query("update " . $data["table"] . "set " . implode(",", $columns) . ") values (" . implode(",", $values) . ")");
    if (!$q) throwError("Insert Error: " . $db->getError());

    echo json_encode($r);