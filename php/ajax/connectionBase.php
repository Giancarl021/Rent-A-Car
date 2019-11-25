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
        if (is_null($q)) return [];
        $r = [];
        while ($item = mysqli_fetch_array($q)) {
            array_push($r, $item);
        }
        return $r;
    }

    function matchParam($database, $paramConfig, $value, $key) {
        if (!$paramConfig->match($value)) throwError("Parameter Parse Error: $key - " . $paramConfig->getError());
        $val = $database->escapeString($value);

        if (is_null($val) && !is_null($value)) throwError($database->getError());

        switch ($paramConfig->getType()) {
            case "string":
                $val = "'$val'";
                break;
        }
        return $val;
    }

    function send($database, $response) {
        if (!$database->disconnect()) $response["error"] = $database->getError();
        echo json_encode($response);
    }