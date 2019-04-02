<?php


require_once 'DbConnect.php';
require_once 'Barbarian.php';
require_once 'Wizard.php';

class CharacterManager extends DbConnect {
    
    public function getAllChar() {
        
        $q = $this->_db->prepare('SELECT * FROM characters ORDER BY name');
        $q->execute(array());
        
        $datas = $q->fetchAll();

        return $datas;
    }    
    
    
    public function getCharById($charId) {
        $q = $this->_db->prepare('SELECT * FROM characters WHERE id = :id');
        $q->execute(array(':id' => $charId));
        
        return  $q->fetch();
    }   
    
    public function charCreationCheck() {
        if(isset($_POST) && isset($_POST['charName']) && isset($_POST['charType'])) {
            
            
            if($this->checkCharExistsByInfo($_POST['charName']) == false) {
                $this->addChar($_POST['charName'], $_POST['charType']);
                
                $_SESSION['errorMessage'] = "";
                return $_SESSION['userChar'] = $this->charCreate($_POST['charName'], $_POST['charType']);                 
            }
            else {
                return $_SESSION['errorMessage'] = "Il semblerait que le personnage " .$_POST['charName']. " soit déjà pris!";
            }
        }
    }   
    
    public function checkCharExistsByInfo($info) {
        // On veut voir si tel personnage ayant pour id $charId existe.
        if (is_int($info)) 
        {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM characters WHERE id = ' . $info)->fetchColumn();
        }
        // Sinon, c'est qu'on veut vérifier que le nom existe ou pas.
        $q = $this->_db->prepare('SELECT COUNT(*) FROM characters WHERE name = :name');
        $q->execute(array(':name' => $info));
        
        return (bool) $q->fetchColumn();
    }
   
    public function charCreate($newCharName, $newCharType) {
        
        $char = new $newCharType(
           ['name' => $newCharName,
            'id' => $this->_db->lastInsertId(),
            'damage' => 0,
            'xp' => 0,
            'level' => 1,
            'strength' => 10,
            'type' => $newCharType,
            'handcapsleep' => 0
            ]);
        return $char;
    }
    
    public function addChar($newCharName, $newCharType) {
        $req = $this->_db->prepare('INSERT INTO characters(name, type) VALUES(:name, :type)'); // Préparation de la requête d'insertion.
        $req->bindValue(':name', $newCharName);
        $req->bindValue(':type', $newCharType);
        $req->execute();
        

    }    
    
    public function updateChar($chars) {
        
        foreach($chars as $char){
            $q = $this->_db->prepare('UPDATE characters SET damage = :damage,
                                                            xp = :xp,
                                                            level = :level,
                                                            strength = :strength,
                                                            name = :name,
                                                            handicapsleep = :handicapsleep
    
                                                            WHERE id = :id');
            
            $q->bindValue(':damage', $char->damage(), PDO::PARAM_INT);        
            $q->bindValue(':xp', $char->xp(), PDO::PARAM_INT);        
            $q->bindValue(':level', $char->level(), PDO::PARAM_INT);        
            $q->bindValue(':strength', $char->strength(), PDO::PARAM_INT);        
            $q->bindValue(':id', $char->id(), PDO::PARAM_INT);
            $q->bindValue(':name', $char->name(), PDO::PARAM_STR);
            $q->bindValue(':handicapsleep', $char->handicapsleep(), PDO::PARAM_INT);
            
            $q->execute();
        }
    }    
    
    public function deleteChar(Character $char) {
        $req = $this->_db->prepare('DELETE from characters WHERE id = :id'); // Préparation de la requête d'insertion.
        $req->bindValue(':id', $char->id(), PDO::PARAM_INT);
        $req->execute();
    }    
}

