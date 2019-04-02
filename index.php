<?php 


require_once 'Barbarian.php';
require_once 'Wizard.php';
require_once 'CharacterManager.php';

if(!isset($_SESSION))
{
    session_start();
} 

// if(isset($_SESSION['userChar'])) {
//     $userChar = new Character($_SESSION['userChar']);
//     var_dump($userChar);
// }
$charManager = new CharacterManager();

$charManager->charCreationCheck();
$characters = $charManager->getAllChar();

$uriParameters = $_SERVER['REQUEST_URI'];
$uri = explode("/", $uriParameters);

if(isset($_GET['unsetSession']) && $_GET['unsetSession'] == TRUE) {
    session_unset();
}

if(isset($_GET['use'])) {
    $infoChar = $charManager->getCharById($_GET['use']);
    $_SESSION['userChar'] = new $infoChar['type']($infoChar);
}

if(!isset($_SESSION['userChar'])) {
    $_SESSION['errorMessage'] = "veuillez créer un personnage pour pouvoir rentrer dans l'arêne!";
}

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

if(isset($_GET['enchant']) && $charManager->checkCharExistsByInfo((int)$_GET['enchant']) && isset($_SESSION['userChar'])) {
    $infosTarget = $charManager->getCharById((int)$_GET['enchant']);
    
    $target = new $infosTarget['type']($infosTarget);
    
    $combatChars= [$target, $_SESSION['userChar'] ];
    if($target->id() != $_SESSION['userChar']->id()) {
        
        $_SESSION['userChar']->enchant($target);
        $charManager->updateChar($combatChars);
    }
}
?>



<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Arena</title>
        <link href="/css/template.css" rel="stylesheet" />
    </head>
    
    <body>
    	<?php 	
    	 if(isset($_SESSION['userChar'])){ ?>
  		    <p><a href="?unsetSession=TRUE">Déconnexion</a></p>
         <?php } ?>
         
    	 <form action="" method="post">
    		<p>
    			<label for="charName">Nom :</label> <input type="text" name="charName" maxlength="25" /> 
    			<input type="radio" name="charType" value="Barbarian" /> <label for="charType">Barbare</label>
    			<input type="radio" name="charType" value="Wizard" /> <label for="charType">Magicien</label> 
    			
    			<input type="submit" value="Créer ce personnage" name="creer" />    			
    		</p>
    		<?php if(isset($_SESSION['errorMessage']) && $_SESSION['errorMessage'] != "") {
    		    echo "<p>". $_SESSION['errorMessage'] . "</p>";
    		}?>
    		
		</form>
    	
    	<fieldset>
    		<legend>Qui frapper ?</legend>        		
              	<?php foreach ($characters as $char) {?>
              		<fieldset>
                  		<legend><?= ucfirst($char['name'])?></legend> 
                  		<p>PV : <?= 100 - $char['damage']?> <br/>
                  		   Niveau : <?= $char['level']?>
                  		   Type : <?= $char['type']?>
                  		</p>
                  		
                  		
                  		<?php 
                  		if(!isset($_SESSION['userChar'])){ ?>
                  		    <p><a href="?use=<?= $char['id']?>">utiliser</a></p>
                  		<?php } 
                  		
                  		if(isset($_SESSION['userChar']) && $char['id'] != $_SESSION['userChar']->id() ) {?>
                  			<p><a href="?attack=<?= $char['id']?>">attaquer</a></p>
                  			<?php if($_SESSION['userChar']->type() == 'Wizard') {?>
                  				<p><a href="?enchant=<?= $char['id']?>">enchanter</a></p>
                  			<?php }
    		             } ?>
              		</fieldset>
              	<?php }?>  		
		</fieldset>
		
		<p>nombre de guerriers dans l'arène : <?= count($characters) ?></p>


    </body>   
            
    <footer>
        <p>Copyright © 2019 - 2020 Arena - Ltd - CAEN </p>
    </footer>
</html>