<?php
    require("../DAO.php");

    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    echo json_decode($_REQUEST["data"], true);