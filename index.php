<?php 


require_once 'Character.php';
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

$char = $charManager->charCreationCheck();
$characters = $charManager->getAllChar();

$uriParameters = $_SERVER['REQUEST_URI'];
$uri = explode("/", $uriParameters);

if(!isset($_SESSION['userChar'])) {
    $_SESSION['errorMessage'] = "veuillez créer un personnage pour pouvoir rentrer dans l'arêne!";
}

if(isset($_GET['attack']) && $charManager->checkCharExistsByInfo((int)$_GET['attack']) && isset($_SESSION['userChar'])) {
    $infosTarget = $charManager->getCharById((int)$_GET['attack']);
    $target = new Character($infosTarget);
    if($target->id() != $_SESSION['userChar']->id()) {
        $_SESSION['userChar']->attack($target);
        $charManager->updateChar($_SESSION['userChar'], $target);
        $_SESSION['errorMessage'] = "";
        header('Location: http://dev.Arena.com'); 
    }
    else {
        $_SESSION['errorMessage'] = "Impossible de se frapper soi même!";
    }
} ?>





<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Arena</title>
        <link href="/css/template.css" rel="stylesheet" />
    </head>
    
    <body>
    	 <form action="" method="post">
    		<p>
    			Nom : <input type="text" name="charName" maxlength="25" /> <input
    				type="submit" value="Créer ce personnage" name="creer" />    			
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
                  		</p>
                  		
                  		<p><a href="?attack=<?= $char['id']?>">attaquer</a></p>
              		</fieldset>
              	<?php }?>  		
		</fieldset>
		
		<p>nombre de guerriers dans l'arène : <?= count($characters) ?></p>


    </body>   
            
    <footer>
        <p>Copyright © 2019 - 2020 Arena - Ltd - CAEN </p>
    </footer>
</html>