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
    
    <p>Informations de l'objets</p>
    <form method="POST" action="addItemEnd.php">
        Image : <br> <input type="text" name="adminItemPicture" class="form-control" placeholder="Image" required><br /><br />
        Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" required><br /><br />
        Description : <br> <textarea class="form-control" name="adminItemDescription" id="adminItemDescription" rows="3" required></textarea><br /><br />
        HP : <br> <input type="number" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" required><br /><br />
        MP : <br> <input type="number" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" required><br /><br />
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");