<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopItemShopId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopItemShopId'])
    && $_POST['adminShopItemShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminShopItemShopId = htmlspecialchars(addslashes($_POST['adminShopItemShopId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId = ?');
        $shopQuery->execute([$adminShopItemShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe
        if ($shopRow == 1)
        {
            //On fait une requête pour vérifier la liste des objets/équipement dans le magasin
            $townShopQuery = $bdd->prepare("SELECT * FROM car_shops, car_items, car_shops_items
            WHERE shopItemShopId = shopId
            AND shopItemItemId = itemId
            AND shopId = ?
            ORDER BY itemName");
            $townShopQuery->execute([$adminShopItemShopId]);
            $townShopRow = $townShopQuery->rowCount();

            //S'il existe un ou plusieurs objet dans le magasin on affiche le menu déroulant
            if ($townShopRow > 0) 
            {
                ?>

                <form method="POST" action="editDeleteShopItem.php">
                    Articles en vente : <select name="adminShopItemItemId" class="form-control">
                        
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($townShop = $townShopQuery->fetch())
                        {
                            //On récupère les informations des objets
                            $adminShopItemItemId = stripslashes($townShop['itemId']);
                            $adminShopItemItemName = stripslashes($townShop['itemName']);
                            $adminShopItemDiscount = stripslashes($townShop['shopItemDiscount']);
                            ?>
                            <option value="<?php echo $adminShopItemItemId ?>"><?php echo "$adminShopItemItemName (Réduction: $adminShopItemDiscount%)"; ?></option>
                            <?php
                        }
                        ?>
                        
                    </select>
                    <input type="hidden" name="adminShopItemShopId" value="<?php echo $adminShopItemShopId ?>">
                    <input type="submit" name="edit" class="btn btn-default form-control" value="Modifier la réduction">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer l'article">
                </form>
                
                <hr>

                <?php
            }
            $townShopQuery->closeCursor();

            //On fait une requête pour afficher la liste des objets du jeu qui ne sont pas dans le magasin
            $itemQuery = $bdd->prepare("SELECT * FROM car_items
            WHERE (SELECT COUNT(*) FROM car_shops_items
            WHERE shopItemShopId = ?
            AND shopItemItemId = itemId) = 0
            ORDER BY itemName");
            $itemQuery->execute([$adminShopItemShopId]);
            $itemRow = $itemQuery->rowCount();
            //S'il existe un ou plusieurs objets on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($itemRow > 0) 
            {
                ?>
                
                <form method="POST" action="addShopItem.php">
                    Articles existant : <select name="adminShopItemItemId" class="form-control">
                            
                        <?php
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($item = $itemQuery->fetch())
                        {
                            //On récupère les informations des objets
                            $adminShopItemItemId = stripslashes($item['itemId']);
                            $adminShopItemItemName = stripslashes($item['itemName']);
                            ?>
                            <option value="<?php echo $adminShopItemItemId ?>"><?php echo "$adminShopItemItemName"; ?></option>
                            <?php
                        }
                        ?>
                        
                    </select>
                    Réduction (De 0 à 100%) <input type="number" name="adminShopItemDiscount" class="form-control" placeholder="Réduction (De 0 à 100%)" value="0" required>
                    <input type="hidden" name="adminShopItemShopId" value="<?php echo $adminShopItemShopId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter l'article">
                </form>
                
                <?php
            }
            else
            {
                echo "Il n'y a actuellement aucun article";
            }
            $itemQuery->closeCursor();
            ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le magasin n'exite pas
        else
        {
            echo "Erreur: Ce magasin n'existe pas";
        }
        $shopQuery->closeCursor();
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