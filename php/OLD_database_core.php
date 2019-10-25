<?php

    global $mysqli, $mysqli_error;

    function db_connect() {
        $mysqli = @new mysqli(host, user, password, database);
        if(mysqli_connect_errno()) {
            _throw_error("Connection error");
            return false;
        }
        $GLOBALS['mysqli'] = $mysqli;
        return true;
    }

    function db_disconnect() {
        $mysqli = $GLOBALS['mysqli'];
        $mysqli->close();
    }

    function db_query($query) {
        $mysqli = $GLOBALS['mysqli'];
        if(!$mysqli) {
            _throw_error("No connection error");
            return false;
        }
        $query = $mysqli->query($query);
        if(!$query) {
            _throw_error($mysqli->error, true);
            return false;
        }
        return $query;
    }

    function _throw_error($error, $print = false) {
        $GLOBALS['mysqli_error'] = $error;
        if($print) echo $error;
    }