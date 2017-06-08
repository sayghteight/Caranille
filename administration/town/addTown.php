<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id de compte
if (isset($_POST['add']))
{
    ?>
    <p>Informations de l'objets</p>
    <form method="POST" action="addItemEnd.php">
        Image : <br> <input type="mail" name="adminItemPicture" class="form-control" placeholder="Image" required><br /><br />
        Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" required><br /><br />
        Description : <br> <input type="mail" name="adminItemDescription" class="form-control" placeholder="Description" required><br /><br />
        HP Bonus : <br> <input type="mail" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" required><br /><br />
        MP Bonus : <br> <input type="mail" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" required><br /><br />
        Prix d'achat : <br> <input type="mail" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" required><br /><br />
        Prix de vente : <br> <input type="mail" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" required><br /><br />
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");