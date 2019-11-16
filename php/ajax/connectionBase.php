<?php
    function requestData() {
        if (!isset($_REQUEST["data"])) {
            echo "{\"error\": \"Input Error\"}";
            die;
        }
        return json_decode($_REQUEST["data"], true);
    }

    function throwError($errorMessage = "Ambiguous error occurred") {
        echo "{\"error\": \"$errorMessage\"}";
        die;
    }

    function fetchQuery($q = null) {
        if(is_null($q)) return [];
        $r = [];
        while ($item = mysqli_fetch_array($q)) {
            array_push($r, $item);
        }
        return $r;
    }