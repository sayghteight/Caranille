<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton delete
if (isset($_POST['adminShopId'])
&& isset($_POST['delete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopId'])
    && $_POST['adminShopId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminShopId = htmlspecialchars(addslashes($_POST['adminShopId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$adminShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin est disponible
        if ($shopRow == 1) 
        {
            while ($shop = $shopQuery->fetch())
            {
                $adminShopName = stripslashes($shop['shopName']);
            }

            ?>
            <p>ATTENTION</p> 
            Vous êtes sur le point de supprimer le magasin <em><?php echo $adminShopName ?></em><br />
            confirmez-vous la suppression ?

            <hr>
                
            <form method="POST" action="deleteShopEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="adminShopId" value="<?= $adminShopId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
            </form>
    
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
        $shopRow->closeCursor();
    }
    //Si le magasin choisit n'est pas un nombre
    else
    {
        echo "Erreur: Magasin invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton delete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");