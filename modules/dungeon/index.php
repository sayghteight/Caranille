<?php require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
?>

<h4>Liste des monstres disponible</h4> 

<?php
//On fait une recherche de tous les comptes dans la base de donnée qui ont un Id différent du notre
$monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters, car_towns_monsters
WHERE townMonsterId = townId
AND townId = ?");
$monsterQueryList->execute([$townId]);
$monsterQuery = $monsterQueryList->rowCount();

//On boucle la liste des résultats
if ($monsterQuery > 0)
{
    ?>
    <form method="POST" action="dungeon.php">
        <div class="form-group row">
            <label for="raceList" class="col-2 col-form-label">Liste des monstres</label>
            <select class="form-control" id="townList" name="monsterId">
            <?php
            //On fait une boucle sur tous les résultats
            while ($monsterList = $townListQuery->fetch())
            {
                //on récupère les valeurs de chaque villes qu'on va ensuite mettre dans le menu déroulant
                $monsterId = stripslashes($monsterList['monsterId']); 
                $monsterName = stripslashes($monsterList['monsterName']);
                ?>
                    <option value="<?php echo $monsterId ?>"><?php echo $monsterName ?></option>
                <?php
            }
            $monsterQueryList->closeCursor();
            ?>
            </select>
        </div>
        <center><input type="submit" name="enter" class="btn btn-default form-control" value="Lancer le combat"></center>
    </form>
    <?php
}
else
{
    ?>
    Il n'y a aucun monstre de disponible.
    <?php
}

$monsterQueryList->closeCursor();

require_once("../../html/footer.php"); ?>