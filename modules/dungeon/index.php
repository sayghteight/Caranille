<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une jointure entre les 3 tables car_monsters, car_towns, car_towns_monsters pour récupérer les monstres lié à la ville
$monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
WHERE townMonsterMonsterId = monsterId
AND townMonsterTownId = townId
AND townId = ?");
$monsterQueryList->execute([$townId]);
$monsterRow = $monsterQueryList->rowCount();

//Si plusieurs monstres ont été trouvé
if ($monsterRow > 0)
{
    ?>
    <form method="POST" action="selectedMonster.php">
        <div class="form-group row">
            <label for="raceList" class="col-2 col-form-label">Liste des monstres</label>
            <select class="form-control" id="townList" name="battleMonsterId">
            <?php
            //On fait une boucle sur tous les résultats
            while ($monster = $monsterQueryList->fetch())
            {
                //on récupère les valeurs de chaque monstres qu'on va ensuite mettre dans le menu déroulant
                $monsterId = stripslashes($monster['monsterId']); 
                $monsterName = stripslashes($monster['monsterName']);
                ?>
                    <option value="<?php echo $monsterId ?>"><?php echo $monsterName ?></option>
                <?php
            }
            $monsterQueryList->closeCursor();
            ?>
            </select>
        </div>
        <input type="submit" name="enter" class="btn btn-default form-control" value="Lancer le combat">
    </form>
    <?php
}
//S'il n'y a aucun monstre de disponible on prévient le joueur
else
{
    ?>
    Il n'y a aucun monstre de disponible.
    <?php
}

require_once("../../html/footer.php"); ?>