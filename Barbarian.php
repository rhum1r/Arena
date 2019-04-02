<?php

require_once 'Character.php';

class Barbarian extends Character {

    public function inflictDamages (Character $target)
    {
        $criticalHit = rand(0,2);
        if($criticalHit == 2) {
            $criticalHit = $this->_strength / $criticalHit;
        }
        else {
            $criticalHit = 0;
        }
        $target->_damage += $this->_strength + $criticalHit;
    }
}




 