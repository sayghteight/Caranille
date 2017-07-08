<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
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
        //On récupère l'id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'objet choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet existe
        if ($itemRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
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

            <p><img src="<?php echo $adminItemPicture; ?>" height="100" width="100"></p>

            <p>Informations de l'objet</p>
            
            <form method="POST" action="editItemEnd.php">
                Image : <input type="mail" name="adminItemPicture" class="form-control" placeholder="Image" value="<?php echo $adminItemPicture; ?>" required>
                Nom : <input type="text" name="adminItemName" class="form-control" placeholder="Nom" value="<?php echo $adminItemName; ?>" required>
                Description : <br> <textarea class="form-control" name="adminItemDescription" id="adminItemDescription" rows="3" required><?php echo $adminItemDescription; ?></textarea>
                HP : <input type="number" name="adminItemHpEffects" class="form-control" placeholder="HP Bonus" value="<?php echo $adminItemHpEffects; ?>" required>
                MP : <input type="number" name="adminItemMpEffect" class="form-control" placeholder="MP Bonus" value="<?php echo $adminItemMpEffect; ?>" required>
                Prix d'achat : <input type="number" name="adminItemPurchasePrice" class="form-control" placeholder="Prix d'achat" value="<?php echo $adminItemPurchasePrice; ?>" required>
                Prix de vente : <input type="number" name="adminItemSalePrice" class="form-control" placeholder="Prix de vente" value="<?php echo $adminItemSalePrice; ?>" required>
                <input type="hidden" name="adminItemId" value="<?= $adminItemId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si l'objet n'exite pas
        else
        {
            echo "Erreur: Objet indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");