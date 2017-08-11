<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['newOffer']))
{
    //On fait une requête pour vérifier tous les objets et équippement qui sont dans l'inventaire du joueur
    $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
    WHERE itemId = inventoryItemId
    AND inventoryCharacterId = ?
    ORDER BY itemName");
    $itemQuery->execute([$characterId]);
    $itemRow = $itemQuery->rowCount();
    
    //S'il existe un ou plusieurs objets on affiche le menu déroulant pour proposer au joueur d'en ajouter
    if ($itemRow > 0) 
    {
        ?>
        
        <form method="POST" action="addOffer.php">
            Liste de vos objets/équippement : <select name="marketItemId" class="form-control">
                    
                <?php
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($item = $itemQuery->fetch())
                {
                    //On récupère les informations des objets
                    $marketItemId = stripslashes($item['itemId']);
                    $marketItemName = stripslashes($item['itemName']);
                    $marketItemQuantity = stripslashes($item['inventoryQuantity']);
                    ?>
                    <option value="<?php echo $marketItemId ?>"><?php echo "$marketItemName (Quantité: $marketItemQuantity)"; ?></option>
                    <?php
                }
                ?>
                
            </select>
            Prix de vente:  <input type="number" name="marketSalePrice" class="form-control" placeholder="Prix de vente" value="0" required>
            <input type="submit" name="addOffer" class="btn btn-default form-control" value="Créer une offre">
        </form>
        
        <?php
    }
    else
    {
        echo "Vous n'avez actuellement aucun objet pouvant être mit en vente";
    }
    $itemQuery->closeCursor();
    ?>
    
    <hr>
    
    <form method="POST" action="../../index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>