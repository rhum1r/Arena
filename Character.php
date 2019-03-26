<?php

require_once 'DbConnect.php';

class Character {
    
    private $_id;
    private $_name;
    private $_strength;
    private $_damage;
    private $_level;
    private $_xp;
    
    public function __construct($datas) {
        
        self::hydrate($datas);
    }
    
    public function hydrate(array $datas)
    {
        foreach ($datas as $key => $value)
        {
            $method = 'set'.ucfirst($key);
            // Si le setter correspondant existe.
            
            if (method_exists($this, $method))
            {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }
    // Liste des getters
    
    public function id()
    {
        return $this->_id;
    }
    
    public function name()
    {
        return $this->_name;
    }
    
    public function strength()
    {
        return $this->_strength;
    }
    
    public function damage()
    {
        return $this->_damage;
    }
    
    public function level()
    {
        return $this->_level;
    }
    
    public function xp()
    {
        return $this->_xp;
    }
    
    // Liste des setters
    
    public function setId($id)
    {
        // On convertit l'argument en nombre entier.
        // Si c'en était déjà un, rien ne changera.
        // Sinon, la conversion donnera le nombre 0 (à quelques exceptions près, mais rien d'important ici).
        $id = (int) $id;
        
        // On vérifie ensuite si ce nombre est bien strictement positif.
        if ($id > 0)
        {
            // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
            $this->_id = $id;
        }
    }
    
    public function setName($name)
    {
        // On vérifie qu'il s'agit bien d'une chaîne de caractères.
        if (is_string($name))
        {
            $this->_name = $name;
        }
    }
    
    public function setStrength($strength)
    {
        $strength = (int) $strength;
        
        if ($strength >= 1 && $strength <= 100)
        {
            $this->_strength = $strength;
        }
    }
    
    public function setDamage($damage)
    {
        $damage = (int) $damage;
        
        if ($damage >= 0 && $damage <= 100)
        {
            $this->_damage = $damage;
        }
    }
    
    public function setLevel($level)
    {
        $level = (int) $level;
        
        if ($level >= 1 && $level <= 100)
        {
            $this->_level = $level;
        }
    }
    
    public function setXp($xp)
    {
        $xp = (int) $xp;
        
        if ($xp >= 0 && $xp <= 100)
        {
            $this->_xp = $xp;
        }
    }
    
    
//////////////////////////////////////////////////////////////////////////////   
    
    public function attack(Character $target)
    {
        return $target->receiveDamage($target);
    }
    
    public function receiveDamage(Character $target)
    {
        return $target->_damage = $this->_strength + $target->_damage;
    }
}



