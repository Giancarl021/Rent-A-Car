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
    $where = null;
    if ($condition !== 0) {
        switch ($table) {
            case "client":
                if ($condition === 1) $where = "debt <> ''";
                else if ($condition === 2) $where = "debt = '' or debt is null or debt = 0";
                break;
            case "car":
                if ($condition === 1) $where = "";
                else if ($condition === 2) $where = "";
                break;
            case "rent":
                if ($condition === 1) $where = "a";
                else if ($condition === 2) $where = "a";
                break;
            default:
                $table = null;
        }
    }
    if (!is_null($table)) {
        $q = $db->query("select * from $table" . ($condition === 0 ? "" : " where $where"));
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