<?php

    class ParamConfig {
        private $regex, $type, $isNotNull, $validator_func, $error;

        public function __construct($regex, $type, $isNotNull, $validator_func = null) {
            $this->regex = $regex;
            $this->type = $type;
            $this->isNotNull = $isNotNull;
            $this->validator_func = $validator_func;
            $this->error = null;
        }

        public function getRegex() {
            return $this->regex;
        }

        public function setRegex($regex) {
            $this->regex = $regex;
        }

        public function getType() {
            return $this->type;
        }

        public function setType($type) {
            $this->type = $type;
        }

        public function getIsNotNull() {
            return $this->isNotNull;
        }

        public function setIsNotNull($isNotNull) {
            $this->isNotNull = $isNotNull;
        }

        public function getValidatorFunc() {
            return $this->validator_func;
        }

        public function setValidatorFunc($validator_func) {
            $this->validator_func = $validator_func;
        }

        public function match($value) {
            if (is_null($value)) {
                if ($this->isNotNull) {
                    $this->error = "Value is null";
                    return false;
                } else {
                    return true;
                }
            }
            if (gettype($value) !== $this->type) {
                if ($this->type === "number") {
                    if (!is_numeric($value)) {
                        $this->error = "Value type is not numeric";
                        return false;
                    }
                } else {
                    $this->error = "Value type error";
                    return false;
                }
            };
            if (!is_null($this->regex)) {
                if (!preg_match($this->regex, $value)) {
                    $this->error = "Regex does not match $value";
                    return false;
                }
            }

            if (!is_null($this->validator_func) && is_callable($this->validator_func)) {
                if (!call_user_func($this->validator_func, $value)) {
                    $this->error = "Validator function returned false";
                    return false;
                }
            }
            return true;
        }

        public function getError() {
            return $this->error;
        }
    }

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

        public static function getParamConfigs() {
            return [
                "pk" => "cpf",
                "cpf" => new ParamConfig("/\d{11}/", "string", true, function ($value) {
                    if (strlen($value) !== 11) return false;
                    $digits = str_split($value);

                    if (sizeof(array_unique($digits)) === 1) return false;

                    for ($t = 9; $t < 11; $t++) {
                        for ($d = 0, $c = 0; $c < $t; $c++) {
                            $d += $value[$c] * (($t + 1) - $c);
                        }
                        $d = ((10 * $d) % 11) % 10;
                        if ($value[$c] != $d) {
                            return false;
                        }
                    }
                    return true;
                }),
                "name" => new ParamConfig("/^.{1,50}$/", "string", true),
                "address" => new ParamConfig("/^.{1,150}$/", "string", true),
                "telephone" => new ParamConfig("/\d{10,11}/", "string", true),
                "debt" => new ParamConfig(null, "number", false, function ($value) {
                    return ($value >= 0);
                })
            ];
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

        public static function getParamConfigs() {
            $isPositiveNumber = function ($value) {
                return ($value >= 0);
            };
            return [
                "pk" => "carPlate",
                "carPlate" => new ParamConfig("/^.{7}$/", "string", true),
                "carYear" => new ParamConfig(null, "number", true, $isPositiveNumber),
                "model" => new ParamConfig("/^.{1,20}$/", "string", true),
                "description" => new ParamConfig("/^.{1,240}$/", "string", true),
                "km" => new ParamConfig(null, "number", true, $isPositiveNumber),
                "kmPrice" => new ParamConfig(null, "number", true, $isPositiveNumber),
                "dailyTax" => new ParamConfig(null, "number", true, $isPositiveNumber),
                "observations" => new ParamConfig("/^.{1,240}$/", "string", false)
            ];
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
        private $id, $clientCpf, $carPlate, $initDate, $devolutionDate;

        public function __construct($id, $clientCpf, $carPlate, $initDate, $devolutionDate) {
            $this->id = $id;
            $this->clientCpf = $clientCpf;
            $this->carPlate = $carPlate;
            $this->initDate = $initDate;
            $this->devolutionDate = $devolutionDate;
        }

        public function getId() {
            return $this->id;
        }

        public function setId($id) {
            $this->id = $id;
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

        public function getInitDate() {
            return $this->initDate;
        }

        public function setInitDate($initDate) {
            $this->initDate = $initDate;
        }

        public function getdevolutionDate() {
            return $this->devolutionDate;
        }

        public function setdevolutionDate($devolutionDate) {
            $this->devolutionDate = $devolutionDate;
        }

        public static function getParamConfigs() {
            return [
                "pk" => "id",
                "id" => new ParamConfig(null, "integer", false),
                "clientCpf" => Client::getParamConfigs()["cpf"],
                "carPlate" => Car::getParamConfigs()["carPlate"],
                "initDate" => new ParamConfig(null, "string", true), # FILTRO DE DATA
                "devolutionDate" => new ParamConfig(null, "string", false) # FILTRO DE DATA
            ];
        }

        public function toString($tag = null) {
            $str = "";
            foreach ([$this->id, $this->clientCpf, $this->carPlate, $this->initDate, $this->devolutionDate] as $attr) {
                if (!is_null($tag))
                    $str .= "<" . $tag . ">$attr</" . $tag . ">";
                else
                    $str .= $attr . PHP_EOL;
            }
            return $str;
        }
    }
