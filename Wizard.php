<?php

require_once 'Character.php';

class Wizard extends Character {
    
    public function enchant(Character $target) {
        $enchantSuccess = rand(0,3);
        if($target->_handicapsleep > 0) {     
            $_SESSION['errorMessage'] = $target->_name. " est déjà endormi!";
        }
        elseif($enchantSuccess != 0) {     
            $_SESSION['errorMessage'] = "Quelque Chose a capoté! Votre sort est loupé!";
        }
        else {
            $target->_handicapsleep = time();
            $_SESSION['errorMessage'] = "";      
        }
        
    }
}