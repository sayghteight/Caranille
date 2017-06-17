<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['shopId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['shopId'])
    && $_POST['shopId'] >= 1)
    {
        //On récupère l'Id du magasin
        $shopId = htmlspecialchars(addslashes($_POST['shopId']));

        //On fait une requête pour vérifier si le magasin est bien disponible dans la ville du joueur
        /*
        SELECT * FROM car_shops, car_towns, car_towns_shops //On prend les trois tables car_shops, car_towns, car_towns_shops afin de les lier
        WHERE townShopShopId = shopId //On fait une liaison entre la table car_towns_shops et car_shops avec l'Id du magasin
        AND townShopTownId = townId //On fait une liaison entre la table car_towns_shops et car_towns avec l'Id de la ville
        AND monsterId = ? //On ajoute l'Id du magasin obtenu par le formulaire
        AND townId = ? //On ajoute l'Id de la ville dans laquel on est
        */
        //On fait une jointure entre les 3 tables car_shops, car_towns, car_towns_shops pour récupérer les magasin lié à la ville
        $shopQueryList = $bdd->prepare("SELECT * FROM car_shops, car_towns, car_towns_shops
        WHERE townShopShopId = shopId
        AND townShopTownId = townId
        AND townId = ?");
        $shopQueryList->execute([$shopId]);
        $shopRow = $shopQueryList->rowCount();

        //Si plusieurs magasins ont été trouvé
        if ($shopRow > 0)
        {
            //On récupère les informations du magasin
            while ($shop = $shopQueryList->fetch())
            {
                $shopPicture = stripslashes($shop['shopPicture']);
                $shopName = stripslashes($shop['shopName']);
                $shopDescription = stripslashes($shop['shopDescription']);
            }

            //On cherche à savoir si il y a un ou plusieurs objets en vente
            $townShopQuery = $bdd->prepare("SELECT * FROM car_shops, car_items, car_shops_items
            WHERE shopItemShopId = shopId
            AND shopItemItemId = itemId
            AND shopId = ?
            ORDER BY itemName");
            $townShopQuery->execute([$shopId]);
            $townShopRow = $townShopQuery->rowCount();

            //Si il existe un ou plusieurs objet dans le magasin on affiche le menu déroulant
            if ($townShopRow > 0) 
            {
                ?>
                <h4><?php echo $shopName; ?></h4><br />
                <?php echo $shopDescription; ?><br /><br />

                <hr>

                <form method="POST" action="buyItem.php">
                    <div class="form-group row">
                        <label for="adminShopItemItemId" class="col-2 col-form-label">Objets/équippements en vente</label>
                        <select class="form-control" id="itemId" name="itemId">
                        <?php
                        while ($townShop = $townShopQuery->fetch())
                        {
                            $itemId = stripslashes($townShop['itemId']);
                            $itemName = stripslashes($townShop['itemName']);
                            $itemDiscount = stripslashes($townShop['shopItemDiscount']);?>
                            ?>
                                <option value="<?php echo $itemId ?>"><?php echo "$itemName (Réduction: $itemDiscount%)"; ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <input type="hidden" name="shopId" value="<?= $shopId ?>">
                    <input type="submit" name="buy" class="btn btn-default form-control" value="Acheter">
                </form>
                <?php
            }
            else
            {
                echo "Il n'y a rien en vente";
            }
        }
        //Si le magasin n'est pas disponible
        else
        {
            echo "Erreur: Magasin indisponible";
        }
    }
    //Si le magasin n'est pas un nombre
    else
    {
        echo "Erreur: Magasin invalide";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>