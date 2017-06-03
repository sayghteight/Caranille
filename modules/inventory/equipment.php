<?php 
require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
?>

Vos équipements

<hr>

<?php
//On fait une requête pour avoir la liste des équipements du personnage
/*
SELECT * FROM car_items, car_inventory //On fait une liaison entre la table car_items et car_inventory
WHERE itemId = inventoryItemItemId //On lie ses deux tables par l'Id de l'objet
AND (itemType = 'Armor' //Il faut que le type de l'objet soit soit une armure (Armor)
OR itemType = 'Boots' //Soit des bottes (Boots)
OR itemType = 'Gloves' //Soit des gants (Gloves)
OR itemType = 'Helmet' //Soit un casque (Helmet)
OR itemType = 'Weapon') //Ou soit une arme (Weapon)
AND inventoryItemCharacterId = ? //Ou le proprietaire et le personnage du joueur
ORDER BY itemType //Par ordre de type
*/
$equipmentQueryList = $bdd->prepare("SELECT * FROM car_items, car_inventory 
WHERE itemId = inventoryItemItemId
AND (itemType = 'Armor' 
OR itemType = 'Boots' 
OR itemType = 'Gloves' 
OR itemType = 'Helmet' 
OR itemType = 'Weapon')
AND inventoryItemCharacterId = ?");
$equipmentQueryList->execute([$characterId]);
$equipment = $equipmentQueryList->rowCount();

//Si un ou plusieurs équipements ont été trouvé
if ($equipment > 0)
{
    ?>
    <form method="POST" action="viewEquipment.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des équipements</label>
            <select class="form-control" id="equipmentId" name="equipmentId">
            <?php
            //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
            while ($equipment = $equipmentQueryList->fetch())
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
        <center><input type="submit" name="enter" class="btn btn-default form-control" value="Plus d'information"></center>
    </form>
    <?php
}
//Si aucun équipement n'a été trouvé
else
{
    ?>
    Vous ne possédez aucun équipements.
    <?php
}
$equipmentQueryList->closeCursor();

require_once("../../html/footer.php"); ?>