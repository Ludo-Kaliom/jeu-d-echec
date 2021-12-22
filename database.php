<?php
class DataBase extends PDO {
    private $DB_HOST = 'localhost';
    private $DB_USER = 'khaliom';
    private $DB_PASS  = 'KjzF=t5R';
    private $DB_NAME  = 'khaliom';

    function __construct() {
        try {
        parent::__construct("mysql:host=".$this->DB_HOST.";dbname=".$this->DB_NAME, $this->DB_USER, $this->DB_PASS);
        }
        catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
    }
}