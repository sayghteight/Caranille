<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['leave']))
{
    //On met le personnage à jour
    $updatecharacter = $bdd->prepare("UPDATE car_characters SET
    characterTownId = 0
    WHERE characterId = :characterId");
    $updatecharacter->execute(array(
    'characterId' => $characterId));
    $updatecharacter->closeCursor();

    //On redirige le joueur vers la carte du monde
    header("Location: ../../modules/map/index.php");
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>