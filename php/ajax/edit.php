<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    if (!isset($data["row"]) || !isset($data["table"]) || !isset($data["pk"])) throwError("Parameter not founded");

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

    $pk = $types[$data["table"]]["pk"];
    $id = matchParam($db, $types[$data["table"]][$pk], $data["pk"], $pk);

    foreach ($data["row"] as $key => $value) {
        if($key === $pk) continue;
        if (!isset($types[$data["table"]][$key])) throwError("Parameter not founded");

        $paramConfig = $types[$data["table"]][$key];

        $val = matchParam($db, $paramConfig, $value, $key);

        if (!is_null($value)) {
            array_push($vars, "$key = $val");
        }
    }
    $r["query"] = "update " . $data["table"] . " set " . implode(", ", $vars) . " where $pk = $id";
    $q = $db->query($r["query"]);
    if (!$q) throwError("Insert Error: " . $db->getError());

    echo json_encode($r);