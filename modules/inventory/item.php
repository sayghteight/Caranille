<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une requête pour vérifier tous les objets qui sont dans l'inventaire du joueur
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
        Liste des objets : <select name="itemId" class="form-control">
                
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
        <input type="submit" name="viewItem" class="btn btn-default form-control" value="Plus d'information">
    </form>
    
    <?php
}
//Si aucun objet n'a été trouvé
else
{
    echo "Vous ne possédez aucun objets.";
}

require_once("../../html/footer.php"); ?>