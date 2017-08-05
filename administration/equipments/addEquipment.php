<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    
    <p>Informations de l'équipement</p>

    <form method="POST" action="addEquipmentEnd.php">
        Classe <select name="adminItemRaceId" class="form-control">
        <option value="0">Toutes les classes</option>
        
            <?php
            //On rempli le menu déroulant avec la liste des classes disponible
            $raceListQuery = $bdd->query("SELECT * FROM car_races");
            $raceList = $raceListQuery->rowCount();
            
            //S'il y a au moins une classe de disponible on les affiches dans le menu déroulant
            if ($raceList >= 1)
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($raceList = $raceListQuery->fetch())
                {
                    //On récupère les informations de la classe
                    $raceId = stripslashes($raceList['raceId']); 
                    $raceName = stripslashes($raceList['raceName']);
                    ?>
                    <option value="<?php echo $raceId ?>"><?php echo $raceName ?></option>
                    <?php
                }
            }
            $raceListQuery->closeCursor();
            ?>
            
        </select>
        Image : <input type="text" name="adminItemPicture" class="form-control" placeholder="Image" value="../../img/empty.png" required>
        Type : <select name="adminItemItemTypeId" class="form-control">
            
            <?php
            //On rempli le menu déroulant avec la liste des classes disponible
            $itemTypeQuery = $bdd->query("SELECT * FROM car_items_types
            WHERE itemTypeName != 'Item'
            AND itemTypeName != 'Parchment'");
            
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($itemType = $itemTypeQuery->fetch())
            {
                //On récupère les informations de la classe
                $adminItemTypeId = stripslashes($itemType['itemTypeId']);
                $adminItemTypeName = stripslashes($itemType['itemTypeName']);
                $adminItemTypeNameShow = stripslashes($itemType['itemTypeNameShow']);
                ?>
                <option value="<?php echo $adminItemTypeId ?>"><?php echo $adminItemTypeNameShow ?></option>
                <?php
            }
            $itemTypeQuery->closeCursor();
            ?>
            
        </select>
        Nom : <input type="text" name="adminItemName" class="form-control" placeholder="Nom" required>
        Description : <br> <textarea class="form-control" name="adminItemDescription" id="adminItemDescription" rows="3" required></textarea>
        Niveau : <input type="number" name="adminItemLevel" class="form-control" placeholder="Niveau" value="1" required>
        Niveau requis : <input type="number" name="adminItemLevelRequired" class="form-control" placeholder="Niveau requis" value="1" required>
        HP Bonus : <input type="number" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" value="0" required>
        MP Bonus : <input type="number" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" value="0" required>
        Force Bonus : <input type="number" name="adminItemStrengthEffect" class="form-control" placeholder="Force Bonus" value="0" required>
        Magie Bonus : <input type="number" name="adminItemMagicEffect" class="form-control" placeholder="Magie Bonus" value="0" required>
        Agilité Bonus : <input type="number" name="adminItemAgilityEffect" class="form-control" placeholder="Agilité Bonus" value="0" required>
        Défense Bonus : <input type="number" name="adminItemDefenseEffect" class="form-control" placeholder="Défense Bonus" value="0" required>
        Défense Magique Bonus : <input type="number" name="adminItemDefenseMagicEffect" class="form-control" placeholder="Défense Magique Bonus" value="0" required>
        Sagesse Bonus : <input type="number" name="adminItemWisdomEffect" class="form-control" placeholder="Sagesse Bonus" value="0" required>
        Prospection Bonus : <input type="number" name="adminItemProspectingEffect" class="form-control" placeholder="Prospection Bonus" value="0" required>
        Prix d'achat : <input type="number" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" value="0" required>
        Prix de vente : <input type="number" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" value="0" required>
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");