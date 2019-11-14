<?php
    require("../DAO.php");

    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    $data = json_decode($_REQUEST["data"], true);

    $r = [
        "error" => null,
        "elementId" => "tb-" . $data["table"]
    ];

    $db = getDatabase();

    if (!$db->connect()) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    # Implode nas chaves das rows e nos valores, testar condições para cada tabela (valores nulos)

    $columns = [];
    $values = [];

    foreach ($data["row"] as $key => $value) {
        array_push($columns, $key);
        array_push($values, "'" . $value . "'"); # Arrumar tipagem
    }

    $q = $db->query("insert into " . $data["table"] . "(" . implode(",", $columns) . ") values (" . implode(",", $values) . ")");
    if (!$q) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    $r["result"] = [];

    $q = $db->query("select * from " . $data["table"]);
    if (!$q) {
        $r["error"] = $db->getError();
        echo json_encode($r);
        die;
    }

    while ($item = mysqli_fetch_array($q)) {
        array_push($r["result"], $item);
    }

    # Retornar dados atualizados

    echo json_encode($r);