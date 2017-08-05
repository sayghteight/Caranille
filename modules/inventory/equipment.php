<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une requête pour avoir la liste des équipements du personnage
$equipmentQuery = $bdd->prepare("SELECT * FROM  car_items, car_items_types, car_inventory 
WHERE itemItemTypeId = itemTypeId
AND itemId = inventoryItemId
AND (itemTypeName = 'Armor' 
OR itemTypeName = 'Boots' 
OR itemTypeName = 'Gloves' 
OR itemTypeName = 'Helmet' 
OR itemTypeName = 'Weapon')
AND inventoryCharacterId = ?");
$equipmentQuery->execute([$characterId]);
$equipmentRow = $equipmentQuery->rowCount();

//Si un ou plusieurs équipements ont été trouvé
if ($equipmentRow > 0)
{
    ?>
    
    <form method="POST" action="viewEquipment.php">
        Liste des équipements : <select name="itemId" class="form-control">
                
            <?php
            //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
            while ($equipment = $equipmentQuery->fetch())
            {
                //On récupère les informations de l'équippement
                $equipmentId = stripslashes($equipment['itemId']); 
                $equipmentName = stripslashes($equipment['itemName']);
                $equipmentQuantity = stripslashes($equipment['inventoryQuantity']);
                ?>
                <option value="<?php echo $equipmentId ?>"><?php echo "$equipmentName (Quantité: $equipmentQuantity)" ?></option>
                <?php
            }
            ?>
                
        </select>
        <center><input type="submit" name="viewEquipment" class="btn btn-default form-control" value="Plus d'information"></center>
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Vous ne possédez aucun équipements.";
}
$equipmentQuery->closeCursor();

require_once("../../html/footer.php"); ?>