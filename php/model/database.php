<?php
    class ConnectionData {
        private $host, $user, $password, $database;

        public function __construct($host, $user, $password, $database) {
            $this->host = $host;
            $this->user = $user;
            $this->password = $password;
            $this->database = $database;
        }

        function setHost($host) {
            $this->host = $host;
        }

        function getHost() {
            return $this->host;
        }

        function setUser($user) {
            $this->user = $user;
        }

        function getUser() {
            return $this->user;
        }

        function setPassword($password) {
            $this->password = $password;
        }

        function getPassword() {
            return $this->password;
        }

        function setDatabase($database) {
            $this->database = $database;
        }

        function getDatabase() {
            return $this->database;
        }
    }

    class Database {
        private $db, $error, $data, $isConnected;

        public function __construct($connectionData) {
            if(gettype($connectionData) === "object" && get_class($connectionData) === "ConnectionData") {
                $this->data = $connectionData;
            } else {
                $this->data = null;
            }
            $this->db = null;
            $this->error = null;
        }

        public function getData($isArray = false) {
            if ($isArray) {
                return [
                    "host" => $this->data->getHost(),
                    "user" => $this->data->getUser(),
                    "password" => $this->data->getPassword(),
                    "database" => $this->data->getDatabase()
                ];
            }
            return $this->data;
        }

        public function setData($data) {
            if (gettype($data) === "object" && get_class($data) === "ConnectionData") {
                $this->data = $data;
                return true;
            }
            return false;
        }

        public function getError() {
            return $this->error;
        }

        public function connect() {
            if (is_null($this->data)) {
                $this->error = "No ConnectionData provided";
                return false;
            }
            $this->db = @new mysqli(
                $this->data->getHost(),
                $this->data->getUser(),
                $this->data->getPassword(),
                $this->data->getDatabase()
            );
            if(mysqli_connect_errno()) {
                $this->error = "Connection error";
                return false;
            }
            $this->isConnected = true;
            return true;
        }

        public function disconnect() {
            if(is_null($this->db)) {
                return false;
            }
            @$this->db->close();
            $this->db = null;
            $this->isConnected = false;
            return true;
        }

        public function query($query) {
            if(!$this->isConnected) {
                $this->error = "No connection established";
                return false;
            }
            $query = @$this->db->query($query);
            if(!$query) {
                $this->error = $this->db->error;
                return false;
            }
            return $query;
        }
    }