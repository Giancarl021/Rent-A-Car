<?php
    require("DAO.php");
    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    $data = json_decode($_REQUEST["data"], true);
    # table => select
    # condition => 2
    # elementId => pass

    $r = [
        "error" => null,
        "elementId" => $data["elementId"]
    ];

    $db = getDatabase();
    if (!$db->connect()) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    $condition = $data["condition"];

    # 1 => with Debt / Avaliable / Active
    # 2 => withoutDebt / Rented / Expired
    # 3 =>

    $table = $data["table"];
    $options = null;
    if ($condition !== 0) {
        switch ($table) {
            case "client":
                if ($condition === 1) $options = "where debt <> '' and debt <> 0";
                else if ($condition === 2) $options = "where debt = '' or debt is null or debt = 0";
                break;
            case "car":
                if ($condition === 1) $options = "left join Rent as r on (r.carPlate = $table.carPlate and (r.expirationDate is null or r.expirationDate = ''))";
                else if ($condition === 2) $options = "erro";
                break;
            case "rent":
                if ($condition === 1) $options = "erro";
                else if ($condition === 2) $options = "erro";
                break;
            default:
                $table = null;
        }
    }
    if (!is_null($table)) {
        $q = $db->query("select * from $table" . ($condition === 0 ? "" : " $options"));
        if(!$q) {
            $r["error"] = $db->getError();
            echo json_encode($r);
            die;
        }
    } else {
        $r["error"] = "Table not founded";
        echo json_encode($r);
        die;
    }

    $r["result"] = [];

    while ($item = mysqli_fetch_array($q)) {
        array_push($r["result"], $item);
    }

    echo json_encode($r);