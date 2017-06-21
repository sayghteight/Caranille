<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRaceId'])
&& isset($_POST['edit']))
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
            //On récupères les informations de la race pour le formulaire ci-dessous
            while ($race = $raceQuery->fetch())
            {
                //On récupère les informations du compte
                $adminRaceName = stripslashes($race['raceName']);
                $adminRaceDescription = stripslashes($race['raceDescription']);
                $adminRaceHpBonus = stripslashes($race['raceHpBonus']);
                $adminRaceMpBonus = stripslashes($race['raceMpBonus']);
                $adminRaceStrengthBonus = stripslashes($race['raceStrengthBonus']);
                $adminRaceMagicBonus = stripslashes($race['raceMagicBonus']);
                $adminRaceAgilityBonus = stripslashes($race['raceAgilityBonus']);
                $adminRaceDefenseBonus = stripslashes($race['raceDefenseBonus']);
                $adminRaceDefenseMagicBonus = stripslashes($race['raceDefenseMagicBonus']);
                $adminRaceWidsomBonus = stripslashes($race['raceWidsomBonus']);
            }
            ?>

            <p>Informations de la classe</p>
            <form method="POST" action="editRaceEnd.php">
                Nom : <br> <input type="text" name="adminRaceName" class="form-control" placeholder="Nom" value="<?php echo $adminRaceName; ?>" required autofocus><br /><br />
                Description : <br> <textarea class="form-control" name="adminRaceDescription" id="adminRaceDescription" rows="3" required><?php echo $adminRaceDescription; ?></textarea><br /><br />
                <input type="hidden" name="adminRaceId" value="<?= $adminRaceId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            
            <p>Amélioration par niveau</p>
            +<?php echo $adminRaceHpBonus; ?> Hp<br />
            +<?php echo $adminRaceMpBonus; ?> Mp<br />
            +<?php echo $adminRaceStrengthBonus; ?> Force<br />
            +<?php echo $adminRaceMagicBonus; ?> Magie<br />
            +<?php echo $adminRaceAgilityBonus; ?> Agilité<br />
            +<?php echo $adminRaceDefenseBonus; ?> Défense<br />
            +<?php echo $adminRaceDefenseMagicBonus; ?> Défense magique<br />
            +<?php echo $adminRaceWidsomBonus; ?> Sagesse<br /><br />

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