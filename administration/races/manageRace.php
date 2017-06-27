<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRaceId'])
&& isset($_POST['manage']))
{
    //On vérifie si l'id de la race récupéré dans le formulaire est en entier positif
    if (ctype_digit($_POST['adminRaceId'])
    && $_POST['adminRaceId'] >= 1)
    {
        //On récupère l'id de la race
        $adminRaceId = htmlspecialchars(addslashes($_POST['adminRaceId']));

        //On fait une requête pour vérifier si le compte choisit existe
        $raceQuery = $bdd->prepare('SELECT * FROM car_races 
        WHERE raceId= ?');
        $raceQuery->execute([$adminRaceId]);
        $raceRow = $raceQuery->rowCount();

        //Si la race est disponible
        if ($raceRow == 1) 
        {
            //On Récupère le nom de la race
            while ($race = $raceQuery->fetch())
            {
                $adminRaceName = stripslashes($race['raceName']);
            }

            ?>
            Que souhaitez-vous faire de la classe <em><?php echo $adminRaceName ?></em><br />

            <hr>
                
            <form method="POST" action="editRace.php">
                <input type="hidden" class="btn btn-default form-control" name="adminRaceId" value="<?= $adminRaceId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier la classe">
            </form>
            <form method="POST" action="deleteRace.php">
                <input type="hidden" class="btn btn-default form-control" name="adminRaceId" value="<?= $adminRaceId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer la classe">
            </form>

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