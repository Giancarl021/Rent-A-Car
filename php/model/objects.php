<?php
    //    $d = new Database(new ConnectionData("localhost", "root", "", "db_rentacar"));
    //    $d->connect();
    //    $r = $d->query("");
    //    echo $d->getError();
    //    $d->disconnect();
    //    var_dump($r);

    class Client {
        private $cpf, $name, $address, $telephone, $debt;

        public function __construct($cpf, $name, $address, $telephone, $debt) {
            $this->cpf = $cpf;
            $this->name = $name;
            $this->address = $address;
            $this->telephone = $telephone;
            $this->debt = $debt;
        }

        public function getCpf() {
            return $this->cpf;
        }

        public function setCpf($cpf) {
            $this->cpf = $cpf;
        }

        public function getName() {
            return $this->name;
        }

        public function setName($name) {
            $this->name = $name;
        }

        public function getAddress() {
            return $this->address;
        }

        public function setAddress($address) {
            $this->address = $address;
        }

        public function getTelephone() {
            return $this->telephone;
        }

        public function setTelephone($telephone) {
            $this->telephone = $telephone;
        }

        public function getDebt() {
            return $this->debt;
        }

        public function setDebt($debt) {
            $this->debt = $debt;
        }

        public function toString($tag = null) {
            $str = "";
            foreach ([$this->cpf, $this->name, $this->address, $this->telephone, $this->debt] as $attr) {
                if (!is_null($tag))
                    $str .= "<" . $tag . ">$attr</" . $tag . ">";
                else
                    $str .= $attr . PHP_EOL;
            }
            return $str;
        }
    }

    class Car {
        private $carPlate, $carYear, $model, $description, $km, $kmPrice, $dailyTax, $observations;

        public function __construct($carPlate, $carYear, $model, $description, $km, $kmPrice, $dailyTax, $observations) {
            $this->carPlate = $carPlate;
            $this->carYear = $carYear;
            $this->model = $model;
            $this->description = $description;
            $this->km = $km;
            $this->kmPrice = $kmPrice;
            $this->dailyTax = $dailyTax;
            $this->observations = $observations;
        }

        public function getCarPlate() {
            return $this->carPlate;
        }

        public function setCarPlate($carPlate) {
            $this->carPlate = $carPlate;
        }

        public function getCarYear() {
            return $this->carYear;
        }

        public function setCarYear($carYear) {
            $this->carYear = $carYear;
        }

        public function getModel() {
            return $this->model;
        }

        public function setModel($model) {
            $this->model = $model;
        }

        public function getDescription() {
            return $this->description;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function getKm() {
            return $this->km;
        }

        public function setKm($km) {
            $this->km = $km;
        }

        public function getKmPrice() {
            return $this->kmPrice;
        }

        public function setKmPrice($kmPrice) {
            $this->kmPrice = $kmPrice;
        }

        public function getDailyTax() {
            return $this->dailyTax;
        }

        public function setDailyTax($dailyTax) {
            $this->dailyTax = $dailyTax;
        }

        public function getObservations() {
            return $this->observations;
        }

        public function setObservations($observations) {
            $this->observations = $observations;
        }

        public function toString($tag = null) {
            $str = "";
            foreach ([$this->carPlate, $this->carYear, $this->model, $this->description, $this->km, $this->kmPrice, $this->dailyTax, $this->observations] as $attr) {
                if (!is_null($tag))
                    $str .= "<" . $tag . ">$attr</" . $tag . ">";
                else
                    $str .= $attr . PHP_EOL;
            }
            return $str;
        }
    }

    class Rent {
        private $clientCpf, $carPlate, $expired, $initDate, $expirationDate;

        public function __construct($clientCpf, $carPlate, $expired, $initDate, $expirationDate) {
            $this->clientCpf = $clientCpf;
            $this->carPlate = $carPlate;
            $this->expired = $expired;
            $this->initDate = $initDate;
            $this->expirationDate = $expirationDate;
        }

        public function getClientCpf() {
            return $this->clientCpf;
        }

        public function setClientCpf($clientCpf) {
            $this->clientCpf = $clientCpf;
        }

        public function getCarPlate() {
            return $this->carPlate;
        }

        public function setCarPlate($carPlate) {
            $this->carPlate = $carPlate;
        }

        public function getExpired() {
            return $this->expired;
        }

        public function setExpired($expired) {
            $this->expired = $expired;
        }

        public function getInitDate() {
            return $this->initDate;
        }

        public function setInitDate($initDate) {
            $this->initDate = $initDate;
        }

        public function getExpirationDate() {
            return $this->expirationDate;
        }

        public function setExpirationDate($expirationDate) {
            $this->expirationDate = $expirationDate;
        }

        public function toString($tag = null) {
            $str = "";
            foreach ([$this->carPlate, $this->clientCpf, $this->initDate, $this->expirationDate, $this->expired] as $attr) {
                if (!is_null($tag))
                    $str .= "<" . $tag . ">$attr</" . $tag . ">";
                else
                    $str .= $attr . PHP_EOL;
            }
            return $str;
        }
    }