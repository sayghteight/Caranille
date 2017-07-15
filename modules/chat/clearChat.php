<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
//Si le joueur n'a pas les droits modérateur ou administrateur (Accès 1 ou 2) on le redirige vers l'accueil du chat
if ($accountAccess < 1) { exit(header("Location: index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['clearChat']))
{
    //On vide le chat de la base de donnée
    $deleteChat = $bdd->query("DELETE FROM car_chat");
    
    //On redirige le joueur vers le chat
    header("Location: index.php");
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../../html/footer.php"); ?>