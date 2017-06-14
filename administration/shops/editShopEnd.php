<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['adminShopId'])
&& isset($_POST['adminShopPicture'])
&& isset($_POST['adminShopName'])
&& isset($_POST['adminShopDescription'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopId'])
    && $_POST['adminShopId'] >= 1)
    {
        //On récupère les informations du formulaire
        $adminShopId = htmlspecialchars(addslashes($_POST['adminShopId']));
        $adminShopPicture = htmlspecialchars(addslashes($_POST['adminShopPicture']));
        $adminShopName = htmlspecialchars(addslashes($_POST['adminShopName']));
        $adminShopDescription = htmlspecialchars(addslashes($_POST['adminShopDescription']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$adminShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin est disponible
        if ($shopRow == 1) 
        {
            //On met à jour le magasin dans la base de donnée
            $updateShops = $bdd->prepare('UPDATE car_shops 
            SET shopPicture = :adminShopPicture,
            shopName = :adminShopName,
            shopDescription = :adminShopDescription
            WHERE shopId = :adminShopId');

            $updateShops->execute([
            'adminShopPicture' => $adminShopPicture,
            'adminShopName' => $adminShopName,
            'adminShopDescription' => $adminShopDescription,
            'adminShopId' => $adminShopId]);
            $updateShops->closeCursor();
            ?>

            Le magasin a bien été mit à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si le magasin n'est pas disponible
        else
        {
            echo "Erreur: Magasin indisponible";
        }
        $shopQuery->closeCursor();
    }
    //Si le magasin choisit n'est pas un nombre
    else
    {
        echo "Erreur: Objet invalid";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");