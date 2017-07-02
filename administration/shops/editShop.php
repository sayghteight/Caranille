<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopId'])
    && $_POST['adminShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminShopId = htmlspecialchars(addslashes($_POST['adminShopId']));

        //On fait une requête pour vérifier si le magasin choisi existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$adminShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe
        if ($shopRow == 1) 
        {
            //On fait une boucle pour récupérer toutes les information
            while ($shop = $shopQuery->fetch())
            {
                //On récupère les informations du magasin
                $adminShopPicture = stripslashes($shop['shopPicture']);
                $adminShopName = stripslashes($shop['shopName']);
                $adminShopDescription = stripslashes($shop['shopDescription']);
            }
            ?>

            <p>Informations du magasin</p>
            <form method="POST" action="editShopEnd.php">
                Image : <br> <input type="text" name="adminShopPicture" class="form-control" placeholder="Image" value= "<?php echo $adminShopPicture; ?>" required><br /><br />
                Nom : <br> <input type="text" name="adminShopName" class="form-control" placeholder="Nom" value= "<?php echo $adminShopName; ?>" required><br /><br />
                Description : <br> <textarea class="form-control" name="adminShopDescription" id="adminShopDescription" rows="3" required><?php echo $adminShopDescription; ?></textarea><br /><br />
                <input type="hidden" name="adminShopId" value="<?= $adminShopId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si le magasin n'exite pas
        else
        {
            echo "Erreur: Magasin indisponible";
        }
        $shopQuery->closeCursor();
    }
    //Si le magasin choisi n'est pas un nombre
    else
    {
        echo "Erreur: Magasin invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");