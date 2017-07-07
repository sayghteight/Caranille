<?php
//On fait une recherche dans la base de donnée pour récupérer la configuration du jeu
$configurationQuery = $bdd->query("SELECT * FROM car_configuration");

//On fait une boucle pour récupérer toutes les information
while ($configuration = $configurationQuery->fetch())
{
    //On récupère les informations du jeu
    $gameId = stripslashes($configuration['configurationId']);
    $gameName = stripslashes($configuration['configurationGameName']);
    $gamePresentation = stripslashes($configuration['configurationPresentation']);  
    $gameExperience = stripslashes($configuration['configurationExperience']);
    $gameSkillPoint = stripslashes($configuration['configurationSkillPoint']);
    $gameAccess = stripslashes($configuration['configurationAccess']);
}
$configurationQuery->closeCursor();
?>
