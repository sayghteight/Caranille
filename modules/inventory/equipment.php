<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une requête pour avoir la liste des équipements du personnage
$equipmentQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
WHERE itemId = inventoryItemId
AND (itemType = 'Armor' 
OR itemType = 'Boots' 
OR itemType = 'Gloves' 
OR itemType = 'Helmet' 
OR itemType = 'Weapon')
AND inventoryCharacterId = ?");
$equipmentQuery->execute([$characterId]);
$equipmentRow = $equipmentQuery->rowCount();

//Si un ou plusieurs équipements ont été trouvé
if ($equipmentRow > 0)
{
    ?>
    <form method="POST" action="viewEquipment.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des équipements</label>
            <select class="form-control" id="itemId" name="itemId">
            <?php
            //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
            while ($equipment = $equipmentQuery->fetch())
            {
                $equipmentId = stripslashes($equipment['itemId']); 
                $equipmentName = stripslashes($equipment['itemName']);
                ?>
                    <option value="<?php echo $equipmentId ?>"><?php echo $equipmentName ?></option>
                <?php
            }
            ?>
            </select>
        </div>
        <center><input type="submit" name="viewEquipment" class="btn btn-default form-control" value="Plus d'information"></center>
    </form>
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    ?>
    Vous ne possédez aucun équipements.
    <?php
}
$equipmentQuery->closeCursor();

require_once("../../html/footer.php"); ?>