<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    # table
    # pk

    $r = [
        "error" => null,
        "elementId" => "tb-" . $data["table"]
    ];

    $db = getDatabase();

    if (!$db->connect()) throwError($db->getError());

    $table = $db->escapeString($data["table"]);
    $pk = $db->escapeString($data["pk"]);

    if(is_null($table) || is_null($pk)) throwError($db->getError());


    switch($table) {
        case "client":
            $paramConfig = Client::getParamConfigs();
            break;
        case "car":
            $paramConfig = Car::getParamConfigs();
            break;
        case "rent":
            $paramConfig = Rent::getParamConfigs();
            break;
        default:
            throwError("Table not founded");
    }

    $var = $paramConfig["pk"];
    $type = $paramConfig[$var]->getType();

    if($type === "string") {
        $pk = "'$pk'";
    }
    
    $q = $db->query("delete from $table where $var = $pk");

    if(!$q) throwError($db->getError());

    $r["query"] = "delete from $table where $var = $pk";

    send($db, $r);