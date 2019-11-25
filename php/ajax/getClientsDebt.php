<?php
    require("../DAO.php");
    require("connectionBase.php");

    $r = [
        "error" => null,
        "response" => ""
    ];

    $db = getDatabase();
    if (!$db->connect()) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    $q = $db->query("select sum(debt) as totalDebt from Client");
    if (!$q) {
        echo "{\"error\":\"" . $db->getError() . "\"}";
        die;
    }

    $debt = mysqli_fetch_array($q)["totalDebt"];

    if (is_null($debt)) $debt = 0;

    $r["response"] = $debt;

    send($db, $r);