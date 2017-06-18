<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId'])
    && $_POST['adminItemId'] >= 1)
    {
        //On récupère l'id de l'équipement
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'équipement choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'équipement existe
        if ($itemRow == 1) 
        {
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'équipement
                $adminItemPicture = stripslashes($item['itemPicture']);
                $adminItemType = stripslashes($item['itemType']);
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
            <form method="POST" action="editEquipmentEnd.php">
                Image : <br> <input type="mail" name="adminItemPicture" class="form-control" placeholder="Image" value="<?php echo $adminItemPicture; ?>" required><br /><br />
                Type <br> <select name="adminItemType" class="form-control">
                <?php
                switch ($adminItemType)
                {
                    //S'il s'agit d'une armure
                    case "Armor":
                        ?>
                        <option selected="selected" value="Armor">Armure</option>
                        <option value="Boots">Bottes</option>
                        <option value="Gloves">Gants</option>
                        <option value="Helmet">Casque</option>
                        <option value="Weapon">Arme</option>
                        <?php
                    break;

                    //S'il s'agit de bottes
                    case "Boots":
                        ?>
                        <option selected="selected" value="Boots">Bottes</option>
                        <option value="Armor">Armure</option>
                        <option value="Gloves">Gants</option>
                        <option value="Helmet">Casque</option>
                        <option value="Weapon">Arme</option>
                        <?php
                    break;

                    //S'il s'agit de gants
                    case "Gloves":
                        ?>
                        <option selected="selected" value="Gloves">Gants</option>
                        <option value="Armor">Armure</option>
                        <option value="Boots">Bottes</option>
                        <option value="Helmet">Casque</option>
                        <option value="Weapon">Arme</option>
                        <?php
                    break;

                    //S'il s'agit d'un casque
                    case "Helmet":
                        ?>
                        <option selected="selected" value="Helmet">Helmet</option>
                        <option value="Armor">Armure</option>
                        <option value="Boots">Bottes</option>
                        <option value="Gloves">Gants</option>
                        <option value="Weapon">Arme</option>
                        <?php
                    break;

                    //S'il s'agit d'une arme
                    case "Weapon":
                        ?>
                        <option selected="selected" value="Weapon">Arme</option>
                        <option value="Armor">Armure</option>
                        <option value="Boots">Bottes</option>
                        <option value="Gloves">Gants</option>
                        <option value="Helmet">Casque</option>
                    
                        <?php
                    break;
                }
                ?>                    
                </select><br /><br />
                Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" value="<?php echo $adminItemName; ?>" required><br /><br />
                Description : <br> <textarea class="form-control"name="adminItemDescription" id="adminItemDescription" rows="3" required><?php echo $adminItemDescription; ?></textarea><br /><br />
                HP Bonus : <br> <input type="text" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" value="<?php echo $adminItemHpEffects; ?>" required><br /><br />
                MP Bonus : <br> <input type="text" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" value="<?php echo $adminItemMpEffect; ?>" required><br /><br />
                Force Bonus : <br> <input type="text" name="adminItemStrengthEffect" class="form-control" placeholder="Force Bonus" value="<?php echo $adminItemStrengthEffect; ?>" required><br /><br />
                Magie Bonus : <br> <input type="text" name="adminItemMagicEffect" class="form-control" placeholder="Magie Bonus" value="<?php echo $adminItemMagicEffect; ?>" required><br /><br />
                Agilité Bonus : <br> <input type="text" name="adminItemAgilityEffect" class="form-control" placeholder="Agilité Bonus" value="<?php echo $adminItemAgilityEffect; ?>" required><br /><br />
                Défense Bonus : <br> <input type="text" name="adminItemDefenseEffect" class="form-control" placeholder="Défense Bonus" value="<?php echo $adminItemDefenseEffect; ?>" required><br /><br />
                Défense Magique Bonus : <br> <input type="text" name="adminItemDefenseMagicEffect" class="form-control" placeholder="Défense Magique Bonus" value="<?php echo $adminItemDefenseMagicEffect; ?>" required><br /><br />
                Sagesse Bonus : <br> <input type="text" name="adminItemWisdomEffect" class="form-control" placeholder="Sagesse Bonus" value="<?php echo $adminItemWisdomEffect; ?>" required><br /><br />
                Prix d'achat : <br> <input type="text" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" value="<?php echo $adminItemPurchasePrice; ?>" required><br /><br />
                Prix de vente : <br> <input type="text" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" value="<?php echo $adminItemSalePrice; ?>" required><br /><br />
                <input type="hidden" name="adminItemId" value="<?= $adminItemId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si l'équipement n'existe pas
        else
        {
            echo "Erreur: cet équipement n'existe pas";
        }
        $itemQuery->closeCursor();
    }
    //Si l'équipement choisit n'est pas un nombre
    else
    {
        echo "Erreur: équipement invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");