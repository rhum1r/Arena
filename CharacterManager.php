<?php


require_once 'DbConnect.php';
require_once 'Character.php';

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
        if(isset($_POST) && !empty($_POST['charName'])) {
            
            if($this->checkCharExistsByInfo($_POST['charName']) == false) {
                $this->addChar($_POST['charName']);
                
                $_SESSION['errorMessage'] = "";
                return $_SESSION['userChar'] = $this->charCreate($_POST['charName']); 
                 
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
   
    public function charCreate($newCharName) {
        $char = new Character(
           ['name' => $newCharName,
            'id' => $this->_db->lastInsertId(),
            'damage' => 0,
            'xp' => 0,
            'level' => 1,
            'strength' => 10
            ]);
        return $char;
    }
    
    public function addChar($newCharName) {

        $req = $this->_db->prepare('INSERT INTO characters(name) VALUES(:name)'); // Préparation de la requête d'insertion.
        $req->bindValue(':name', $newCharName);
        $req->execute();
        

    }    
    
    public function updateChar($attCharId, $targetCharId) {
        var_dump($targetCharId);
        $q = $this->_db->prepare('UPDATE characters SET damage = :damage,
                                                        xp = :xp,
                                                        level = :level,
                                                        strength = :strength,
                                                        name = :name

                                                        WHERE id = :id');
        
        $q->bindValue(':damage', $targetCharId->damage(), PDO::PARAM_INT);        
        $q->bindValue(':xp', $targetCharId->xp(), PDO::PARAM_INT);        
        $q->bindValue(':level', $targetCharId->level(), PDO::PARAM_INT);        
        $q->bindValue(':strength', $targetCharId->strength(), PDO::PARAM_INT);        
        $q->bindValue(':id', $targetCharId->id(), PDO::PARAM_INT);
        $q->bindValue(':name', $targetCharId->name(), PDO::PARAM_STR);
        
        $q->execute();
    }    
    
    public function deleteChar($CharId) {

    }    
}

