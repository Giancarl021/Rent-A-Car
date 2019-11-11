<?php
    require("../DAO.php");

    if (!isset($_REQUEST["data"])) {
        echo "{\"error\": \"Input Error\"}";
        die;
    }

    json_decode($_REQUEST["data"], true);