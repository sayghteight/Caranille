<?php 
require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
?>

Vos objets

<hr>

<?php
$itemQueryList = $bdd->prepare("SELECT * FROM car_items, car_inventory 
WHERE itemId = inventoryItemItemId
AND itemType = 'Item'
AND inventoryItemCharacterId = ?");
$itemQueryList->execute([$characterId]);
$item = $itemQueryList->rowCount();

//Si un ou plusieurs équipements ont été trouvé
if ($item > 0)
{
    ?>
    <form method="POST" action="viewItem.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des objets</label>
            <select class="form-control" id="itemId" name="itemId">
            <?php
            //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
            while ($item = $itemQueryList->fetch())
            {
                $itemId = stripslashes($item['itemId']); 
                $itemName = stripslashes($item['itemName']);
                ?>
                    <option value="<?php echo $itemId ?>"><?php echo $itemName ?></option>
                <?php
            }
            ?>
            </select>
        </div>
        <center><input type="submit" name="enter" class="btn btn-default form-control" value="Plus d'information"></center>
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
$itemQueryList->closeCursor();

require_once("../../html/footer.php"); ?>