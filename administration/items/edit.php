<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id de l'objet
if (isset($_POST['adminItemId'])
&& isset($_POST['edit']))
{
    //On vérifie si l'id de l'objet choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminItemId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'objet choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet est disponible
        if ($itemRow == 1) 
        {
            //On fait une boucle pour récupérer toutes les information
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'objet
                $adminItemId = stripslashes($item['itemId']);
                $adminItemPicture = stripslashes($item['itemPicture']);
                $adminItemName = stripslashes($item['itemName']);
                $adminItemDescription = stripslashes($item['itemDescription']);
                $adminItemHpEffects = stripslashes($item['itemHpEffect']);
                $adminItemMpEffect = stripslashes($item['itemMpEffect']);
                $adminItemPurchasePrice = stripslashes($item['itemPurchasePrice']);
                $adminItemSalePrice = stripslashes($item['itemSalePrice']);
            }
            ?>

            <p>Informations de l'équipement</p>
            <form method="POST" action="finalEdit.php">
                Image : <br> <input type="mail" name="adminItemPicture" class="form-control" placeholder="Image" value="<?php echo $adminItemPicture; ?>" required><br /><br />
                Nom : <br> <input type="text" name="adminItemName" class="form-control" placeholder="Nom" value="<?php echo $adminItemName; ?>" required><br /><br />
                Description : <br> <input type="mail" name="adminItemDescription" class="form-control" placeholder="Description" value="<?php echo $adminItemDescription; ?>" required><br /><br />
                HP Bonus : <br> <input type="mail" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" value="<?php echo $adminItemHpEffects; ?>" required><br /><br />
                MP Bonus : <br> <input type="mail" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" value="<?php echo $adminItemMpEffect; ?>" required><br /><br />
                Prix d'achat : <br> <input type="mail" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" value="<?php echo $adminItemPurchasePrice; ?>" required><br /><br />
                Prix de vente : <br> <input type="mail" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" value="<?php echo $adminItemSalePrice; ?>" required><br /><br />
                <input type="hidden" name="adminItemId" value="<?= $adminItemId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>

            Autres options
            <form method="POST" action="delete.php">
                <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?= $adminItemId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer l'objet">
            </form>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si l'objet n'est pas disponible
        else
        {
            echo "Erreur: Objet indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
        echo "Erreur: Objet invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");