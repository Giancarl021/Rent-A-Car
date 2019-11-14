<?php
    require("../DAO.php");

    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    $data = json_decode($_REQUEST["data"], true);
    
    $r = [
        "error" => null,
        "data" => $data
    ];

    # Ou retorna dados atualizados ou trocar callback de repaint table

    echo json_encode($r);