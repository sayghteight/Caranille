<?php require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }

//On fait une jointure entre les 3 tables car_monsters, car_towns, car_towns_monsters pour récupérer les monstres lié à la ville
$monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
WHERE townMonsterId = monsterId
AND townTownId = townId
AND townId = ?");
$monsterQueryList->execute([$townId]);
$monsterQuery = $monsterQueryList->rowCount();

//Si plusieurs monstres ont été trouvé
if ($monsterQuery > 0)
{
    ?>
    <form method="POST" action="dungeon.php">
        <div class="form-group row">
            <label for="raceList" class="col-2 col-form-label">Liste des monstres</label>
            <select class="form-control" id="townList" name="monsterId">
            <?php
            //On fait une boucle sur tous les résultats
            while ($monsterList = $monsterQueryList->fetch())
            {
                //on récupère les valeurs de chaque monstres qu'on va ensuite mettre dans le menu déroulant
                $monsterId = stripslashes($monsterList['monsterId']); 
                $monsterName = stripslashes($monsterList['monsterName']);
                ?>
                    <option value="<?php echo $monsterId ?>"><?php echo $monsterName ?></option>
                <?php
            }
            ?>
            </select>
        </div>
        <center><input type="submit" name="enter" class="btn btn-default form-control" value="Lancer le combat"></center>
    </form>
    <?php
}
//Si il n'y a aucun monstre de disponible on prévient le joueur
else
{
    ?>
    Il n'y a aucun monstre de disponible.
    <?php
}
$monsterQueryList->closeCursor();

require_once("../../html/footer.php"); ?>