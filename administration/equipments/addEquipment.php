<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    <p>Informations de l'équipement</p>
    <form method="POST" action="addEquipmentEnd.php">
        Image : <br> <input type="text" name="adminItemPicture" class="form-control" placeholder="Image" required><br /><br />
        Type: <br> <select name="adminItemType" class="form-control">
            <option value="Armor">Armure</option>
            <option value="Boots">Bottes</option>
            <option value="Gloves">Gants</option>
            <option value="Helmet">Casque</option>
            <option value="Weapon">Arme</option>
        </select><br /><br />
        Niveau requis : <br> <input type="number" name="adminItemLevelRequired" class="form-control" placeholder="Niveau requis" required><br /><br />
        Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" required><br /><br />
        Description : <br> <textarea class="form-control" name="adminItemDescription" id="adminItemDescription" rows="3" required></textarea><br /><br />
        HP Bonus : <br> <input type="number" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" required><br /><br />
        MP Bonus : <br> <input type="number" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" required><br /><br />
        Force Bonus : <br> <input type="number" name="adminItemStrengthEffect" class="form-control" placeholder="Force Bonus" required><br /><br />
        Magie Bonus : <br> <input type="number" name="adminItemMagicEffect" class="form-control" placeholder="Magie Bonus" required><br /><br />
        Agilité Bonus : <br> <input type="number" name="adminItemAgilityEffect" class="form-control" placeholder="Agilité Bonus" required><br /><br />
        Défense Bonus : <br> <input type="number" name="adminItemDefenseEffect" class="form-control" placeholder="Défense Bonus" required><br /><br />
        Défense Magique Bonus : <br> <input type="number" name="adminItemDefenseMagicEffect" class="form-control" placeholder="Défense Magique Bonus" required><br /><br />
        Sagesse Bonus : <br> <input type="number" name="adminItemWisdomEffect" class="form-control" placeholder="Sagesse Bonus" required><br /><br />
        Prix d'achat : <br> <input type="number" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" required><br /><br />
        Prix de vente : <br> <input type="number" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" required><br /><br />
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
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");