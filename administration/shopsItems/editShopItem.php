<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopItemShopId'])
&& isset($_POST['adminShopItemItemId'])
&& isset($_POST['adminShopItemDiscount'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopItemShopId'])
    && ctype_digit($_POST['adminShopItemItemId'])
    && ctype_digit($_POST['adminShopItemDiscount'])
    && $_POST['adminShopItemShopId'] >= 1
    && $_POST['adminShopItemItemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminShopItemShopId = htmlspecialchars(addslashes($_POST['adminShopItemShopId']));
        $adminShopItemItemId = htmlspecialchars(addslashes($_POST['adminShopItemItemId']));
        $adminShopItemDiscount = htmlspecialchars(addslashes($_POST['adminShopItemDiscount']));

        //Si la réduction est entre 0 et 100 on ajoute l'objet
        if ($adminShopItemDiscount >= 0 && $adminShopItemDiscount <= 100)
        {
            //On fait une requête pour vérifier si le magasin choisi existe
            $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
            WHERE shopId= ?');
            $shopQuery->execute([$adminShopItemShopId]);
            $shopRow = $shopQuery->rowCount();

            //Si le magasin existe
            if ($shopRow == 1) 
            {
                //On fait une requête pour vérifier si l'objet choisi existe
                $itemQuery = $bdd->prepare('SELECT * FROM car_items 
                WHERE itemId = ?');
                $itemQuery->execute([$adminShopItemItemId]);
                $itemRow = $itemQuery->rowCount();

                //Si l'objet existe
                if ($itemRow == 1) 
                {
                    //On fait une requête pour vérifier si le monstre n'est pas déjà dans cette ville
                    $shopItemQuery = $bdd->prepare('SELECT * FROM car_shops_items
                    WHERE shopItemShopId = ?
                    AND shopItemItemId = ?');
                    $shopItemQuery->execute([$adminShopItemShopId, $adminShopItemItemId]);
                    $shopItemRow = $shopItemQuery->rowCount();

                    //Si l'objet est dans ce magasin
                    if ($shopItemRow == 1) 
                    {
                        //On met à jour le magasin dans la base de donnée
                        $updateShopItem = $bdd->prepare("UPDATE car_shops_items
                        SET shopItemDiscount = :adminShopItemDiscount
                        WHERE shopItemItemId = :adminShopItemItemId");

                        $updateShopItem->execute([
                        'adminShopItemDiscount' => $adminShopItemDiscount,
                        'adminShopItemItemId' => $adminShopItemItemId]);
                        $updateShopItem->closeCursor();
                        ?>

                        L'article a bien été mit à jour

                        <hr>
                            
                        <form method="POST" action="manageShopItem.php">
                            <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                            <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                        </form>
                        <?php
                    }
                    //Si l'objet est déjà dans ce magasin
                    else
                    {
                        //Si le joueur a essayé de mettre un objet qui est déjà dans ce magasin on lui donne la possibilité de revenir en arrière
                        ?>
                        Erreur: Cet objet est déjà dans ce magasin
                        <form method="POST" action="manageShopItem.php">
                            <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                            <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                        </form>
                        <?php
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
        //Si la réduction de l'objet est inférieur à 0 ou est supérieur à 100
        else
        {
            ?>
            Erreur: Le taux d'obtention doit être de 0 à 100
            <form method="POST" action="manageShopItem.php">
                <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
            </form>
            <?php
        }
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
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");