<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    $r = [
        "error" => null,
        "elementId" => $data["elementId"]
    ];

    $db = getDatabase();
    if (!$db->connect()) throwError($db->getError());

    $condition = $data["condition"];

    $table = $data["table"];
    $options = null;
    $select = null;
    $order = [
        "client" => "name",
        "car" => "car.model",
        "rent" => "carPlate"
    ];

    if ($condition != 0 && $condition != 1 && $condition != 2) throwError("Condition Error");

    if ($condition !== 0) {
        switch ($table) {
            case "client":
                if ($condition === 1) $options = "where debt <> '' and debt <> 0";
                else if ($condition === 2) $options = "where debt = '' or debt is null or debt = 0";
                break;
            case "car":
                if ($condition === 1) $options = "join Rent as r on r.carPlate = car.carPlate and r.devolutionDate = ''";
                else if ($condition === 2) $options = "left join Rent as r on r.carPlate = car.carPlate where r.devolutionDate <> '' or r.id is null";
                $select = "car.carPlate, car.carYear, car.model, car.description, car.km, car.kmPrice, car.dailyTax, car.observations";
                break;
            case "rent":
                if ($condition === 1) $options = "where devolutionDate = ''";
                else if ($condition === 2) $options = "where devolutionDate <> ''";
                break;
            default:
                $table = null;
        }
    }
    if (!is_null($table)) {
        $q = $db->query("select " . (is_null($select) ? "*" : $select) . " from $table" . ($condition === 0 ? "" : " $options") . " order by " . $order[$table]);
    if (!$q)throwError($db->getError());
    } else {
    throwError("Table not founded");
}

    $r["result"] = fetchQuery($q);

    echo json_encode($r);