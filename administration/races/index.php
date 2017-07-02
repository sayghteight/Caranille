<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }
?>

<form method="POST" action="manageRace.php">
    <div class="form-group row">
        <label for="adminRaceId" class="col-2 col-form-label">Liste des classes</label>
        <select class="form-control" id="adminRaceId" name="adminRaceId">
            
            <?php
            //on récupère les valeurs de chaque race qu'on va ensuite mettre dans le menu déroulant
            //On fait une recherche dans la base de donnée de toutes les races
            $raceQuery = $bdd->query("SELECT * FROM car_races");
            while ($race = $raceQuery->fetch())
            {
                $adminRaceId = stripslashes($race['raceId']);
                $adminRaceName = stripslashes($race['raceName']);
                ?>
                    <option value="<?php echo $adminRaceId ?>"><?php echo "$adminRaceName"; ?></option>
                <?php
            }
            $raceQuery->closeCursor();
            ?>
            
        </select>
    </div>
    <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer la classe">
</form>

<form method="POST" action="addRace.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Créer une classe">
</form>

<?php require_once("../html/footer.php");