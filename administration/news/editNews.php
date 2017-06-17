<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à choisit un id d'une news
if (isset($_POST['adminNewsId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminNewsId'])
    && $_POST['adminNewsId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminNewsId = htmlspecialchars(addslashes($_POST['adminNewsId']));

        //On fait une requête pour vérifier si la news choisie existe
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
            <form method="POST" action="editItemEnd.php">
                Image : <br> <input type="text" name="adminNewsPicture" class="form-control" placeholder="Image" value="<?php echo $adminNewsPicture ?>" required><br /><br />
                Titre : <br> <input type="text" name="adminNewsTitle" class="form-control" placeholder="Titre" value="<?php echo $adminNewsTitle ?>"required><br /><br />
                Message : <br> <textarea class="form-control" name="adminNewsMessage" id="adminNewsMessage" rows="3" required><?php echo $adminNewsMessage ?></textarea><br /><br />
                <input type="hidden" name="adminNewsId" value="<?= $adminNewsId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si la news n'est pas disponible
        else
        {
            echo "Erreur: News indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si la news choisie n'est pas un nombre
    else
    {
        echo "Erreur: News invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");