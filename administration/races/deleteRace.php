<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRaceId'])
&& isset($_POST['delete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminRaceId'])
    && $_POST['adminRaceId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminRaceId = htmlspecialchars(addslashes($_POST['adminRaceId']));

        //On fait une requête pour vérifier si la race choisit existe
        $raceQuery = $bdd->prepare('SELECT * FROM car_races 
        WHERE raceId = ?');
        $raceQuery->execute([$adminRaceId]);
        $raceRow = $raceQuery->rowCount();

        //Si la classe existe
        if ($raceRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($race = $raceQuery->fetch())
            {
                //On récupère les informations de la classe
                $adminRacePicture = stripslashes($race['racePicture']);
                $adminRaceName = stripslashes($race['raceName']);
            }
            $raceQuery->closeCursor();
            
            //On cherche les joueurs qui utilise cette classe et qui sont au dessus du niveau 1
            $characterRaceQuery = $bdd->prepare('SELECT * FROM car_characters 
            WHERE characterRaceId = ?');
            $characterRaceQuery->execute([$adminRaceId]);
            $characterRaceRow = $characterRaceQuery->rowCount();
    
            //S'il y a aucun joueur qui utilise cette classe
            if ($characterRaceRow == 0)
            {
                ?>

                <p><img src="<?php echo $adminRacePicture ?>" height="100" width="100"></p>
                
                <p>ATTENTION</p>

                Vous êtes sur le point de supprimer la classe <em><?php echo $adminRaceName ?></em>.<br />
                Confirmez-vous la suppression ?
    
                <hr>
                    
                <form method="POST" action="deleteRaceEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="adminRaceId" value="<?= $adminRaceId ?>">
                    <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
                </form>
                
                <hr>
    
                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php  
            }
            else
            {
                ?>
                
                Impossible de supprimer cette classe car elle est actuellement utilisé par un ou plusieurs joueurs
                                
                <hr>
    
                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            $characterRaceQuery->closeCursor();
        }
        //Si la classe n'existe pas
        else
        {
            echo "Erreur: Cette classe n'existe pas";
        }
        $raceQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");