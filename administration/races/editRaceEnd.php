<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRaceId']) 
&& isset($_POST['adminRaceName']) 
&& isset($_POST['adminRaceDescription'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si l'id de la race récupéré dans le formulaire est en entier positif
    if (ctype_digit($_POST['adminRaceId'])
    && $_POST['adminRaceId'] >= 1)
    {
        //On récupère l'id de la race
        $adminRaceId = htmlspecialchars(addslashes($_POST['adminRaceId']));

        //On fait une requête pour vérifier si la race choisie existe
        $raceQuery = $bdd->prepare('SELECT * FROM car_races 
        WHERE raceId= ?');
        $raceQuery->execute([$adminRaceId]);
        $raceRow = $raceQuery->rowCount();

        //Si la race est disponible
        if ($raceRow == 1) 
        {
            //On récupère les informations du formulaire
            $adminRaceName = htmlspecialchars(addslashes($_POST['adminRaceName']));
            $adminRaceDescription = htmlspecialchars(addslashes($_POST['adminRaceDescription']));

            //On met à jour la race dans la base de donnée
            $updateRace = $bdd->prepare('UPDATE car_races 
            SET raceName = :adminRaceName, 
            raceDescription = :adminRaceDescription
            WHERE raceId = :adminRaceId');

            $updateRace->execute([
            'adminRaceName' => $adminRaceName,
            'adminRaceDescription' => $adminRaceDescription,
            'adminRaceId' => $adminRaceId]);
            $updateRace->closeCursor();
            ?>

            La classe a bien été mise à jour

            <hr>
                
            <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
            <?php
        }
        //Si la race n'existe pas
        else
        {
            echo "Erreur: Cette classe n'existe pas";
        }
        $raceQuery->closeCursor();
    }
    //Si la race choisit n'est pas un nombre
    else
    {
        echo "Erreur: La race choisie est incorrect";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");