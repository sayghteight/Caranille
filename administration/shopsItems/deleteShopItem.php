<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopItemShopId'])
&& isset($_POST['adminShopItemItemId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopItemShopId'])
    && ctype_digit($_POST['adminShopItemItemId'])
    && $_POST['adminShopItemShopId'] >= 1
    && $_POST['adminShopItemItemId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminShopItemShopId = htmlspecialchars(addslashes($_POST['adminShopItemShopId']));
        $adminShopItemItemId = htmlspecialchars(addslashes($_POST['adminShopItemItemId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$adminShopItemShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin est disponible
        if ($shopRow == 1) 
        {
            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$adminShopItemItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet est disponible
            if ($itemRow == 1) 
            {
                //On fait une requête pour vérifier si le monstre n'est pas déjà dans cette ville
                $shopItemQuery = $bdd->prepare('SELECT * FROM car_shops_items
                WHERE shopItemShopId = ?
                AND shopItemItemId = ?');
                $shopItemQuery->execute([$adminShopItemShopId, $adminShopItemItemId]);
                $shopItemRow = $shopItemQuery->rowCount();

                //Si l'objet n'est pas dans ce magasin
                if ($shopItemRow == 1) 
                {
                    //On supprime l'équippement de la base de donnée
                    $shopItemDeleteQuery = $bdd->prepare("DELETE FROM car_shops_items
                    WHERE shopItemItemId = ?");
                    $shopItemDeleteQuery->execute([$adminShopItemItemId]);
                    $shopItemDeleteQuery->closeCursor();
                    ?>

                    L'article a bien été retiré du magasin

                    <hr>
                        
                    <form method="POST" action="manageShopItem.php">
                        <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                    </form>
                    <?php
                }
                //Si l'objet n'est pas disponible
                else
                {
                    echo "Erreur: Objet indisponible";
                }
                $shopItemQuery->closeCursor();
            }
            //Si l'objet existe pas
            else
            {
                echo "Erreur: Objet indisponible";
            }
            $itemQuery->closeCursor();
        }
        //Si le magasin existe pas
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