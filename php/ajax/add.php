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
    if (!$q) throwError("Insert Error: " . $db->getError());

    $r["result"] = [];

    $q = $db->query("select * from " . $data["table"]);
    if (!$q) throwError("Select Error: " . $db->getError());

    while ($item = mysqli_fetch_array($q)) {
        array_push($r["result"], $item);
    }

    echo json_encode($r);