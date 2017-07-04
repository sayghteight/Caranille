<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
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

        //On fait une requête pour vérifier si le compte choisi existe
        $raceQuery = $bdd->prepare('SELECT * FROM car_races 
        WHERE raceId = ?');
        $raceQuery->execute([$adminRaceId]);
        $raceRow = $raceQuery->rowCount();

        //Si la race existe
        if ($raceRow == 1) 
        {
            //On récupères les informations de la race pour le formulaire ci-dessous
            while ($race = $raceQuery->fetch())
            {
                //On récupère les informations du compte
                $adminRacePicture = stripslashes($race['racePicture']);
                $adminRaceName = stripslashes($race['raceName']);
                $adminRaceDescription = stripslashes($race['raceDescription']);
                $adminRaceHpBonus = stripslashes($race['raceHpBonus']);
                $adminRaceMpBonus = stripslashes($race['raceMpBonus']);
                $adminRaceStrengthBonus = stripslashes($race['raceStrengthBonus']);
                $adminRaceMagicBonus = stripslashes($race['raceMagicBonus']);
                $adminRaceAgilityBonus = stripslashes($race['raceAgilityBonus']);
                $adminRaceDefenseBonus = stripslashes($race['raceDefenseBonus']);
                $adminRaceDefenseMagicBonus = stripslashes($race['raceDefenseMagicBonus']);
                $adminRaceWisdomBonus = stripslashes($race['raceWisdomBonus']);
            }
            ?>

            <p>Informations de la classe</p>
            <form method="POST" action="editRaceEnd.php">
                Image : <br> <input type="text" name="adminRacePicture" class="form-control" placeholder="Nom" value="<?php echo $adminRacePicture; ?>" required autofocus><br /><br />
                Nom : <br> <input type="text" name="adminRaceName" class="form-control" placeholder="Nom" value="<?php echo $adminRaceName; ?>" required autofocus><br /><br />
                Description : <br> <textarea class="form-control" name="adminRaceDescription" id="adminRaceDescription" rows="3" required><?php echo $adminRaceDescription; ?></textarea><br /><br />
                HP par niveau : <br> <input type="number" name="adminRaceHpBonus" class="form-control" placeholder="HP par niveau" value="<?php echo $adminRaceHpBonus; ?>" required autofocus><br /><br />
                MP par niveau : <br> <input type="number" name="adminRaceMpBonus" class="form-control" placeholder="MP par niveau" value="<?php echo $adminRaceMpBonus; ?>" required autofocus><br /><br />
                Force par niveau : <br> <input type="number" name="adminRaceStrengthBonus" class="form-control" placeholder="Force par niveau" value="<?php echo $adminRaceStrengthBonus; ?>" required autofocus><br /><br />
                Magie par niveau : <br> <input type="number" name="adminRaceMagicBonus" class="form-control" placeholder="Magie par niveau" value="<?php echo $adminRaceMagicBonus; ?>" required autofocus><br /><br />
                Agilité par niveau : <br> <input type="number" name="adminRaceAgilityBonus" class="form-control" placeholder="Agilité par niveau" value="<?php echo $adminRaceAgilityBonus; ?>" required autofocus><br /><br />
                Défense par niveau : <br> <input type="number" name="adminRaceDefenseBonus" class="form-control" placeholder="Défense par niveau" value="<?php echo $adminRaceDefenseBonus; ?>" required autofocus><br /><br />
                Défense Magique par niveau : <br> <input type="number" name="adminRaceDefenseMagicBonus" class="form-control" placeholder="Défense Magique par niveau" value="<?php echo $adminRaceDefenseMagicBonus; ?>" required autofocus><br /><br />
                Sagesse par niveau : <br> <input type="number" name="adminRaceWisdomBonus" class="form-control" placeholder="Sagesse par niveau" value="<?php echo $adminRaceWisdomBonus; ?>" required autofocus><br /><br />
                <input type="hidden" name="adminRaceId" value="<?= $adminRaceId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
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