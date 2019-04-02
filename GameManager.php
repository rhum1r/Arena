<?php

require_once 'Barbarian.php';
require_once 'Wizard.php';
require_once 'CharacterManager.php';

class GameManager {
    public function __construct() {
        $this->run();
    }
    
    public function run () {
        

       $this->attackCheck();
       $this->enchantCheck();
        
    }
    
    public function checkCreation($charManager) {
        
        $charManager->charCreationCheck();
        return $characters = $charManager->getAllChar();
    
    }
    
    public function disableConnexionCheck() {
    
        if(isset($_GET['unsetSession']) && $_GET['unsetSession'] == TRUE) {
            session_unset();
        }
    }
    
    public function useCharCheck() {
    
        if(isset($_GET['use'])) {
            $infoChar = $charManager->getCharById($_GET['use']);
            $_SESSION['userChar'] = new $infoChar['type']($infoChar);
        }
    }
    
    public function attackCheck() {
    
        if(isset($_GET['attack']) && $charManager->checkCharExistsByInfo((int)$_GET['attack']) && isset($_SESSION['userChar'])) {
            $infosTarget = $charManager->getCharById((int)$_GET['attack']);
            $target = new $infosTarget['type']($infosTarget);
            
            $combatChars= [$target, $_SESSION['userChar'] ];
            if($target->id() != $_SESSION['userChar']->id()) {
                $_SESSION['userChar']->attack($target);   
                
                if($target->damage() >= 100) {
                    
                    $charManager->deleteChar($target);
                    $_SESSION['userChar']->levelUp();
                }
                
                $charManager->updateChar($combatChars);
                header('Location: http://dev.Arena.com');
            }
            else {
                $_SESSION['errorMessage'] = "Impossible de se frapper soi même!";
            }
            
        }
    }
    
    public function enchantCheck() {
    
        if(isset($_GET['enchant']) && $charManager->checkCharExistsByInfo((int)$_GET['enchant']) && isset($_SESSION['userChar'])) {
            $infosTarget = $charManager->getCharById((int)$_GET['enchant']);
            
            $target = new $infosTarget['type']($infosTarget);
            
            $combatChars= [$target, $_SESSION['userChar'] ];
            if($target->id() != $_SESSION['userChar']->id()) {
                
                $_SESSION['userChar']->enchant($target);
                $charManager->updateChar($combatChars);
            }
        }
    }
}
    
        
        
//         $uriParameters = $_SERVER['REQUEST_URI'];
//         $uri = explode("/", $uriParameters);
        

        

        
//         if(!isset($_SESSION['userChar'])) {
//             $_SESSION['errorMessage'] = "veuillez créer un personnage pour pouvoir rentrer dans l'arêne!";
//         }
        
        
        

//     }
// }
