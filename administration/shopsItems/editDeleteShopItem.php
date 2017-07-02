<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopItemShopId'])
&& isset($_POST['adminShopItemItemId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopItemShopId'])
    && ctype_digit($_POST['adminShopItemItemId'])
    && $_POST['adminShopItemShopId'] >= 1
    && $_POST['adminShopItemItemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminShopItemShopId = htmlspecialchars(addslashes($_POST['adminShopItemShopId']));
        $adminShopItemItemId = htmlspecialchars(addslashes($_POST['adminShopItemItemId']));

        //On fait une requête pour vérifier si le magasin choisi existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId= ?');
        $shopQuery->execute([$adminShopItemShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe
        if ($shopRow == 1) 
        {
            //On récupère les informations du magasin
            while ($shop = $shopQuery->fetch())
            {
                $adminShopItemShopName = stripslashes($shop['shopName']);
            }

            //On fait une requête pour vérifier si l'objet choisi existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$adminShopItemItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet existe
            if ($itemRow == 1) 
            {
                while ($item = $itemQuery->fetch())
                {
                    $adminShopItemItemName = stripslashes($item['itemName']);
                }

                //On fait une requête pour vérifier si l'objet n'est pas déjà dans ce magasin
                $shopItemQuery = $bdd->prepare('SELECT * FROM car_shops_items
                WHERE shopItemShopId = ?
                AND shopItemItemId = ?');
                $shopItemQuery->execute([$adminShopItemShopId, $adminShopItemItemId]);
                $shopItemRow = $shopItemQuery->rowCount();

                //Si l'objet est dans ce magasin
                if ($shopItemRow == 1) 
                {
                    //Si l'utilisateur à cliqué sur le bouton edit
                    if (isset($_POST['edit']))
                    {
                        //On récupère le taux de réduction de l'objet/équipement
                        while ($shopItem = $shopItemQuery->fetch())
                        {
                            $adminShopItemDiscount = stripslashes($shopItem['shopItemDiscount']);
                        }
                        ?>
                        <form method="POST" action="editShopItem.php">
                            Réduction (de 0 à 100%) : <br> <input type="number" name="adminShopItemDiscount" class="form-control" placeholder="Réduction (de 0 à 100%)" value="<?php echo $adminShopItemDiscount; ?>" required><br /><br />
                            <input type="hidden" class="btn btn-default form-control" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminShopItemItemId" value="<?= $adminShopItemItemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalEdit" value="Mettre à jour">
                        </form>
                        <?php
                    }
                    //Si l'utilisateur à cliqué sur le bouton delete
                    elseif (isset($_POST['delete']))
                    {
                        ?>
                        <p>ATTENTION</p> 
                        Vous êtes sur le point de retirer l'article <em><?php echo $adminShopItemItemName ?></em> du magasin <em><?php echo $adminShopItemShopName ?></em><br />
                        confirmez-vous ?

                        <hr>
                            
                        <form method="POST" action="deleteShopItem.php">
                            <input type="hidden" class="btn btn-default form-control" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminShopItemItemId" value="<?= $adminShopItemItemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme">
                        </form>
                
                        <hr>

                        <form method="POST" action="index.php">
                            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                        </form>
                        <?php
                    }
                    //Si l'utilisateur n'a pas cliqué sur le bouton edit ou delete
                    else 
                    {
                        echo "Erreur: Aucun choix effectué";
                    }
                }
                //Si l'objet n'exite pas
                else
                {
                    echo "Erreur: L'objet n'est pas dans ce magasin";
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