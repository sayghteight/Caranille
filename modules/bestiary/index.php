<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une recherche de tous les monstres dans le bestiaire du joueur
$monsterBestiaryQuery = $bdd->prepare("SELECT * FROM car_monsters, car_bestiary 
WHERE monsterId = bestiaryMonsterId
AND bestiaryCharacterId = ?");
$monsterBestiaryQuery->execute([$characterId]);
$monsterBestiaryRow = $monsterBestiaryQuery->rowCount();

//Si un ou plusieurs équipements ont été trouvé
if ($monsterBestiaryRow > 0)
{
    ?>
    
    <form method="POST" action="viewMonster.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des monstres</label>
            <select class="form-control" id="monsterId" name="monsterId">
                
                <?php
                //on récupère les valeurs de chaque monstres qu'on va ensuite mettre dans le menu déroulant
                while ($monsterBestiary = $monsterBestiaryQuery->fetch())
                {
                    $monsterId = stripslashes($monsterBestiary['monsterId']); 
                    $monsterName = stripslashes($monsterBestiary['monsterName']);
                    ?>
                        <option value="<?php echo $monsterId ?>"><?php echo $monsterName ?></option>
                    <?php
                }
                ?>
                
            </select>
        </div>
        <center><input type="submit" name="viewMonster" class="btn btn-default form-control" value="Voir la fiche du monstre"></center>
    </form>
    
    <?php
}
//Si aucun monstre n'a été trouvé
else
{
    ?>
    
    Il y a actuellement aucun monstre dans votre bestiaire
        
    <hr>

    <?php
}
$monsterBestiaryQuery->closeCursor();

require_once("../../html/footer.php"); ?>