<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

$itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
WHERE itemId = inventoryItemId
AND itemType = 'Item'
AND inventoryCharacterId = ?");
$itemQuery->execute([$characterId]);
$itemRow = $itemQuery->rowCount();

//Si un ou plusieurs objets ont été trouvé
if ($itemRow > 0)
{
    ?>
    <form method="POST" action="viewItem.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des objets</label>
            <select class="form-control" id="itemId" name="itemId">
            <?php
            //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
            while ($item = $itemQuery->fetch())
            {
                $itemId = stripslashes($item['itemId']); 
                $itemName = stripslashes($item['itemName']);
                ?>
                    <option value="<?php echo $itemId ?>"><?php echo $itemName ?></option>
                <?php
            }
            $itemQuery->closeCursor();
            ?>
            </select>
        </div>
        <center><input type="submit" name="viewItem" class="btn btn-default form-control" value="Plus d'information"></center>
    </form>
    <?php
}
//Si aucun objet n'a été trouvé
else
{
    ?>
    Vous ne possédez aucun objets.
    <?php
}

require_once("../../html/footer.php"); ?>