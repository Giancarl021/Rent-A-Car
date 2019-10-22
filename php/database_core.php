<?php
    define("host", "localhost");
    define("user", "root");
    define("password", "");
    define("database", "db_rentacar");
    global $mysqli, $mysqli_error;

    function db_connect() {
        $mysqli = new mysqli(host, user, password, database);
        if(!$mysqli) {
            _throw_error($mysqli->error);
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
        $query = $mysqli->query($query);
        if(!$query) {
            _throw_error($mysqli->error);
            return false;
        }
        return $query;
    }

    function _throw_error($error) {
        $GLOBALS['mysqli_error'] = $error;
    }