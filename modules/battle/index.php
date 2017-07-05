<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

echo "Combat de $characterName contre $opponentName<br />";
echo "HP de $characterName: $characterHpMin/$characterHpTotal<br />";
echo "MP de $characterName: $characterMpMin/$characterMpTotal";

$mpNeed = $characterLevel * 2;
?>
            
<hr>

<form method="POST" action="physicalAttack.php">
    <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
</form>

<hr >
    
<form method="POST" action="magicAttack.php">
    <input type="submit" name="magic" class="btn btn-default form-control" value="Attaque magique (<?php echo $mpNeed; ?> MP)"><br>
</form>

<hr >

<?php
//On cherche tous les objets que possède le joueur pour les utiliser en combat
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
    
    <form method="POST" action="useItem.php">
        <div class="form-group row">
            <select class="form-control" id="itemId" name="itemId">
                
                <?php
                //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
                while ($item = $itemQuery->fetch())
                {
                    $itemId = stripslashes($item['itemId']); 
                    $itemName = stripslashes($item['itemName']);
                    $itemQuantity = stripslashes($item['inventoryQuantity']);
                    ?>
                        <option value="<?php echo $itemId ?>"><?php echo "$itemName ($itemQuantity disponible)"; ?></option>
                    <?php
                }
                $itemQuery->closeCursor();
                ?>
                
            </select>
        </div>
        <input type="submit" name="useItem" class="btn btn-default form-control" value="Utiliser">
    </form>
    
    <?php
}
else 
{
    echo "Vous ne possédez aucun objets";
}
?>

<hr >

<form method="POST" action="escape.php">
    <input type="submit" name="escape" class="btn btn-default form-control" value="Abandonner le combat"><br />
</form>

<?php require_once("../../html/footer.php"); ?>