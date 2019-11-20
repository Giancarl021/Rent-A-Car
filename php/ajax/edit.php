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

    $columns = [];

    $types = [
        "client" => Client::getParamConfigs(),
        "car" => Car::getParamConfigs(),
        "rent" => Rent::getParamConfigs()
    ];

    if(!isset($types[$data["table"]])) throwError("Table not founded");

    $pk = $types[$data["table"]]["pk"];
    $id = matchParam($db, $types[$data["table"]][$pk], $data["pk"], $pk);

    foreach ($data["row"] as $key => $value) {
        if ($key === $pk) continue;
        if (!isset($types[$data["table"]][$key])) throwError("Parameter not founded");

        $paramConfig = $types[$data["table"]][$key];

        $val = matchParam($db, $paramConfig, $value, $key);

        if (!is_null($value)) {
            array_push($columns, "$key = $val");
        }
    }

    if ($data["table"] === "rent" && !isset($data["row"]["devolutionDate"])) {
        array_push($columns, "devolutionDate = ''");
    }

    $r["query"] = "update " . $data["table"] . " set " . implode(", ", $columns) . " where $pk = $id";
    $q = $db->query("update " . $data["table"] . " set " . implode(", ", $columns) . " where $pk = $id");
    if (!$q) throwError("Update Error: " . $db->getError());

    echo json_encode($r);