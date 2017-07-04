<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRaceId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminRaceId'])
    && $_POST['adminRaceId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminRaceId = htmlspecialchars(addslashes($_POST['adminRaceId']));

        //On fait une requête pour vérifier si la race choisi existe
        $raceQuery = $bdd->prepare('SELECT * FROM car_races 
        WHERE raceId = ?');
        $raceQuery->execute([$adminRaceId]);
        $raceRow = $raceQuery->rowCount();

        //Si la race existe
        if ($raceRow == 1) 
        {
            //On récupère les informations de la race
            while ($race = $raceQuery->fetch())
            {
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
                //On supprime la classe de la base de donnée
                $raceDeleteQuery = $bdd->prepare("DELETE FROM car_races
                WHERE raceId = ?");
                $raceDeleteQuery->execute([$adminRaceId]);
                $raceDeleteQuery->closeCursor();
                
                //Tous les équippements et objets lié à cette classe vont devenir disponible pour toutes les classes
                $itemQuery = $bdd->prepare('SELECT * FROM car_items 
                WHERE itemRaceId = ?');
                $itemQuery->execute([$adminRaceId]);

                //On récupère les informations de la race
                while ($item = $itemQuery->fetch())
                {
                    $adminItemId = stripslashes($item['itemId']);
                    $adminItemName = stripslashes($item['itemName']);
                      
                    //On met à jour l'équipement dans la base de donnée
                    $updateItems = $bdd->prepare('UPDATE car_items 
                    SET itemRaceId = 0
                    WHERE itemId = :adminItemId');
    
                    $updateItems->execute([
                    'adminItemId' => $adminItemId]);
                    $updateItems->closeCursor();
                    ?>
                    
                    L'équipement <?php echo $adminItemName; ?> est maintenant disponible pour toutes les classes<br />
                    
                    <?php
                }
                $itemQuery->closeCursor();
                ?>
                
                <br >La classe a été correctement supprimée
                
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
            echo "Erreur: Classe indisponible";
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