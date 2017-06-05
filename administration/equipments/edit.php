<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id d'équippement
if (isset($_POST['adminItemId']))
{
    //On vérifie si l'id de l'équippement choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminItemId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'équippement choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'équippement est disponible
        if ($itemRow == 1) 
        {
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'équippement
                $adminItemId = stripslashes($item['itemId']);
                $adminItemRaceId = stripslashes($item['itemRaceId']);
                $adminItemPicture = stripslashes($item['itemPicture']);
                $adminItemType = stripslashes($item['itemType']);
                $adminItemLevel = stripslashes($item['itemLevel']);
                $adminItemLevelRequired = stripslashes($item['itemLevelRequired']);
                $adminItemName = stripslashes($item['itemName']);
                $adminItemDescription = stripslashes($item['itemDescription']);
                $adminItemHpEffects = stripslashes($item['itemHpEffect']);
                $adminItemMpEffect = stripslashes($item['itemMpEffect']);
                $adminItemStrengthEffect = stripslashes($item['itemStrengthEffect']);
                $adminItemMagicEffect = stripslashes($item['itemMagicEffect']);
                $adminItemAgilityEffect = stripslashes($item['itemAgilityEffect']);
                $adminItemDefenseEffect = stripslashes($item['itemDefenseEffect']);
                $adminItemDefenseMagicEffect = stripslashes($item['itemDefenseMagicEffect']);
                $adminItemWisdomEffect = stripslashes($item['itemWisdomEffect']);
                $adminItemPurchasePrice = stripslashes($item['itemPurchasePrice']);
                $adminItemSalePrice = stripslashes($item['itemSalePrice']);
            }
            ?>

            <p>Informations de l'équipement</p>
            <form method="POST" action="finalEdit.php">
                RaceId : <br> <input type="mail" name="adminItemRaceId" class="form-control" placeholder="RaceId" value="<?php echo $adminItemRaceId; ?>" required><br /><br />
                Image : <br> <input type="mail" name="adminItemPicture" class="form-control" placeholder="Image" value="<?php echo $adminItemPicture; ?>" required><br /><br />
                Type : <br> <input type="mail" name="adminItemType" class="form-control" placeholder="Type" value="<?php echo $adminItemType; ?>" required><br /><br />
                Niveau : <br> <input type="mail" name="adminItemLevel" class="form-control" placeholder="Email" value="<?php echo $adminItemLevel; ?>" required><br /><br />
                Niveau requis : <br> <input type="mail" name="adminItemLevelRequired" class="form-control" placeholder="Niveau requis" value="<?php echo $adminItemLevelRequired; ?>" required><br /><br />
                Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" value="<?php echo $adminItemName; ?>" required><br /><br />
                Description : <br> <input type="mail" name="adminItemDescription" class="form-control" placeholder="Description" value="<?php echo $adminItemDescription; ?>" required><br /><br />
                HP Bonus : <br> <input type="mail" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" value="<?php echo $adminItemHpEffects; ?>" required><br /><br />
                MP Bonus : <br> <input type="mail" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" value="<?php echo $adminItemMpEffect; ?>" required><br /><br />
                Force Bonus : <br> <input type="mail" name="adminItemStrengthEffect" class="form-control" placeholder="Force Bonus" value="<?php echo $adminItemStrengthEffect; ?>" required><br /><br />
                Magie Bonus : <br> <input type="mail" name="adminItemMagicEffect" class="form-control" placeholder="Magie Bonus" value="<?php echo $adminItemMagicEffect; ?>" required><br /><br />
                Agilité Bonus : <br> <input type="mail" name="adminItemAgilityEffect" class="form-control" placeholder="Agilité Bonus" value="<?php echo $adminItemAgilityEffect; ?>" required><br /><br />
                Défense Bonus : <br> <input type="mail" name="adminItemDefenseEffect" class="form-control" placeholder="Défense Bonus" value="<?php echo $adminItemDefenseEffect; ?>" required><br /><br />
                Défense Magique Bonus : <br> <input type="mail" name="adminItemDefenseMagicEffect" class="form-control" placeholder="Défense Magique Bonus" value="<?php echo $adminItemDefenseMagicEffect; ?>" required><br /><br />
                Sagesse Bonus : <br> <input type="mail" name="adminItemWisdomEffect" class="form-control" placeholder="Sagesse Bonus" value="<?php echo $adminItemWisdomEffect; ?>" required><br /><br />
                Prix d'achat : <br> <input type="mail" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" value="<?php echo $adminItemPurchasePrice; ?>" required><br /><br />
                Prix de vente : <br> <input type="mail" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" value="<?php echo $adminItemSalePrice; ?>" required><br /><br />
                <input type="hidden" name="adminItemId" value="<?= $adminItemId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>

            <hr>

            Autres options
            <form method="POST" action="delete.php">
                <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?= $adminItemId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer l'équippement">
            </form>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si l'équipement n'est pas disponible
        else
        {
            echo "Erreur: Equippement indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'équippement choisit n'est pas un nombre
    else
    {
        echo "Erreur: Equippement invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");