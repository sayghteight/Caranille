<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['shopId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['shopId'])
    && $_POST['shopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $shopId = htmlspecialchars(addslashes($_POST['shopId']));

        //On fait une requête pour vérifier si le magasin est bien disponible dans la ville du joueur
        $shopQueryList = $bdd->prepare("SELECT * FROM car_shops, car_towns, car_towns_shops
        WHERE townShopShopId = shopId
        AND townShopTownId = townId
        AND townId = ?");
        $shopQueryList->execute([$shopId]);
        $shopRow = $shopQueryList->rowCount();

        //Si plusieurs magasins ont été trouvé
        if ($shopRow > 0)
        {
           //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($shop = $shopQueryList->fetch())
            {
                //On récupère les informations du magasin
                $shopPicture = stripslashes($shop['shopPicture']);
                $shopName = stripslashes($shop['shopName']);
                $shopDescription = stripslashes($shop['shopDescription']);
            }

            //On cherche à savoir S'il y a un ou plusieurs objets en vente
            $townShopQuery = $bdd->prepare("SELECT * FROM car_shops, car_items, car_shops_items
            WHERE shopItemShopId = shopId
            AND shopItemItemId = itemId
            AND shopId = ?
            ORDER BY itemName");
            $townShopQuery->execute([$shopId]);
            $townShopRow = $townShopQuery->rowCount();

            //S'il existe un ou plusieurs objet dans le magasin on affiche le menu déroulant
            if ($townShopRow > 0) 
            {
                ?>
                
                <p><img src="<?php echo $shopPicture; ?>" height="100" width="100"></p>
                
                <h4><?php echo $shopName; ?></h4><br />
                <?php echo $shopDescription; ?>

                <hr>

                <form method="POST" action="viewItem.php">
                    Articles en vente : <select name="itemId" class="form-control">
                    
                    <?php
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($townShop = $townShopQuery->fetch())
                    {
                        //On récupère les informations de l'objet
                        $itemId = stripslashes($townShop['itemId']);
                        $itemName = stripslashes($townShop['itemName']);
                        $itemPurchasePrice = stripslashes($townShop['itemPurchasePrice']);
                        $itemDiscount = stripslashes($townShop['shopItemDiscount']);

                        //On calcule la réduction de l'objet/équipement
                        $discount = $itemPurchasePrice * $itemDiscount / 100;
                        //On applique la réduction
                        $itemPurchasePrice = $itemPurchasePrice - $discount?>
                        ?>
                        <option value="<?php echo $itemId ?>"><?php echo "$itemName ($itemPurchasePrice Pièce d'or)"; ?></option>
                        <?php
                    }
                    ?>
                    
                    </select>
                    <input type="hidden" name="shopId" value="<?= $shopId ?>">
                    <input type="submit" name="view" class="btn btn-default form-control" value="Détail/Achat">
                </form>
                
                <?php
            }
            else
            {
                echo "Il n'y a rien en vente";
            }
            $townShopQuery->closeCursor();
        }
        //Si le magasin n'exite pas
        else
        {
            echo "Erreur: Ce magasin n'existe pas";
        }
        $shopQueryList->closeCursor();
    }
    //Si le magasin n'est pas un nombre
    else
    {
        echo "Erreur: Magasin invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>