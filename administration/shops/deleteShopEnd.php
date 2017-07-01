<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopId'])
    && $_POST['adminShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminShopId = htmlspecialchars(addslashes($_POST['adminShopId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$adminShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe
        if ($shopRow == 1) 
        {
            //On supprime l'objet de la base de donnée
            $shopDeleteQuery = $bdd->prepare("DELETE FROM car_shops
            WHERE shopId = ?");
            $shopDeleteQuery->execute([$adminShopId]);
            $shopDeleteQuery->closeCursor();

            //On supprime aussi les objets en vente du magasin
            $shopItemDeleteQuery = $bdd->prepare("DELETE FROM car_shops_items
            WHERE shopItemShopId = ?");
            $shopItemDeleteQuery->execute([$adminShopId]);
            $shopItemDeleteQuery->closeCursor();
            
            //On supprime aussi ce magasin des villes où il se trouve
            $townShopDeleteQuery = $bdd->prepare("DELETE FROM car_towns_shops
            WHERE townShopShopId = ?");
            $townShopDeleteQuery->execute([$adminShopId]);
            $townShopDeleteQuery->closeCursor();
            ?>

            Le magasin a bien été supprimé

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
    //Si le magasin choisit n'est pas un nombre
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