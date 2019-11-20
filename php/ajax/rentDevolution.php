<?php
    require("../DAO.php");
    require("connectionBase.php");

    $data = requestData();

    if (!isset($data["row"]) || !isset($data["pk"])) throwError("Parameter not founded");

    $r = [
        "error" => null,
        "receiptDate" => ""
    ];

    $db = getDatabase();

    if (!$db->connect()) throwError($db->getError());

    $devolutionDate = matchParam($db, Rent::getParamConfigs()["devolutionDate"], $data["row"]["devolutionDate"], "devolutionDate");

    $q = $db->query("select datediff(" . $devolutionDate . ", (select initDate from rent where id = 8)) as diff");
    if (!$q) throwError($db->getError());

    $r["result"] = fetchQuery($q)[0]["diff"];

    echo json_encode($r);