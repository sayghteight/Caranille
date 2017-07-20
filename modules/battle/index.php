<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

//On calcul les MP nécessaire au lancement d'une attaque magique
$mpNeed = $characterLevel * 2;
?>

<p><img src="<?php echo $opponentPicture ?>" height="100" width="100"></p>

Combat de <?php echo $characterName ?> contre <?php echo $opponentName ?><br />

HP de <?php echo $characterName ?> : <?php echo "$characterHpMin/$characterHpTotal" ?><br />
MP de <?php echo $characterName ?> : <?php echo "$characterMpMin/$characterMpTotal" ?><br /><br />

HP de <?php echo $opponentName ?> : <?php echo "$battleOpponentHpRemaining/$opponentHp" ?><br />
MP de <?php echo $opponentName ?> : <?php echo "$battleOpponentMpRemaining/$opponentMp" ?><br /><br />

<hr>

<form method="POST" action="physicalAttack.php">
    <input type="submit" name="attack" class="btn btn-default form-control" value="Attaque physique"><br>
</form>

<hr>
    
<form method="POST" action="magicAttack.php">
    <input type="submit" name="magic" class="btn btn-default form-control" value="Attaque magique (<?php echo $mpNeed; ?> MP)"><br>
</form>

<hr>

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
        
            <select class="form-control" id="itemId" name="itemId">
                
                <?php
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($item = $itemQuery->fetch())
                {
                    //On récupère les informations de l'objet
                    $itemId = stripslashes($item['itemId']); 
                    $itemName = stripslashes($item['itemName']);
                    $itemQuantity = stripslashes($item['inventoryQuantity']);
                    ?>
                    <option value="<?php echo $itemId ?>"><?php echo "$itemName ($itemQuantity disponible)"; ?></option>
                    <?php
                }
                ?>
                
            </select>
        
        <input type="submit" name="useItem" class="btn btn-default form-control" value="Utiliser">
    </form>
    
    <?php
}
else 
{
    echo "Vous ne possédez aucun objets";
}
$itemQuery->closeCursor();
?>

<hr >

<form method="POST" action="escape.php">
    <input type="submit" name="escape" class="btn btn-default form-control" value="Abandonner le combat"><br />
</form>

<?php require_once("../../html/footer.php"); ?>