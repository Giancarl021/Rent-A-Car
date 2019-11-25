<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    if (!isset($data["row"]) || !isset($data["pk"])) throwError("Parameter not founded");

    $r = [
        "error" => null,
        "elementId" => "tb-rent"
    ];

    $db = getDatabase();

    if (!$db->connect()) throwError($db->getError());

    $RentParamConfigs = Rent::getParamConfigs();
    $CarParamConfigs = Car::getParamConfigs();

    $devolutionDate = matchParam($db, $RentParamConfigs["devolutionDate"], $data["row"]["devolutionDate"], "devolutionDate");
    $km = matchParam($db, $CarParamConfigs["km"], $data["row"]["km"], "km");
    $pk = matchParam($db, $RentParamConfigs[$RentParamConfigs["pk"]], $data["pk"], $RentParamConfigs[$RentParamConfigs["pk"]]);

    $q = $db->query("select * from rent where id = $pk");
    if (!$q) throwError($db->getError());

    $rent = fetchQuery($q)[0];

    $q = $db->query("select datediff(" . $devolutionDate . ", '" . $rent["initDate"] . "') as diff");
    if (!$q) throwError($db->getError());

    $daysDiff = fetchQuery($q)[0]["diff"];
    if ($daysDiff < 0) throwError("Days difference is negative");

    $q = $db->query("select * from car where carPlate = '" . $rent["carPlate"] . "'");
    if (!$q) throwError($db->getError());

    $car = fetchQuery($q)[0];

    $kmDiff = $km - $car["km"];

    if($kmDiff < 0) throwError("Km difference is negative");

    $price = $kmDiff * $car["kmPrice"] + $daysDiff * $car["dailyTax"];



    $q = $db->query("update rent set devolutionDate = $devolutionDate where id = $pk");
    if(!$q) throwError($db->getError());

    $q = $db->query("update car set km = $km where carPlate = '" . $car["carPlate"] . "'");
    if(!$q) throwError($db->getError());

    $q = $db->query("select * from client where cpf = '" . $rent["clientCpf"] . "'");
    if(!$q) throwError($db->getError());

    $client = fetchQuery($q)[0];

    $debt = $client["debt"] + $price;

    $q = $db->query("update client set debt = $debt where cpf = '" . $client["cpf"] . "'");
    if(!$q) throwError($db->getError());

    $r["result"] = [
        "price" => $price,
        "clientCpf" => $client["cpf"],
        "clientName" => $client["name"]
    ];

    send($db, $r);