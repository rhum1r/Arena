<?php

class DbConnect {
    
    protected $_db = null;
    
    public function __construct() {
        try {
           return  $this->_db = new PDO('mysql:host=localhost;dbname=combatGame2.0;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}