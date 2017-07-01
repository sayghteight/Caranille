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
        $shopQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $shopQuery->execute([$adminShopItemShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe existe
        if ($shopRow == 1)
        {
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
                    <div class="form-group row">
                        <label for="adminShopItemItemId" class="col-2 col-form-label">Articles en vente</label>
                        <select class="form-control" id="adminShopItemItemId" name="adminShopItemItemId">
                        <?php
                        while ($townShop = $townShopQuery->fetch())
                        {
                            $adminShopItemItemId = stripslashes($townShop['itemId']);
                            $adminShopItemItemName = stripslashes($townShop['itemName']);
                            $adminShopItemDiscount = stripslashes($townShop['shopItemDiscount']);?>
                            ?>
                                <option value="<?php echo $adminShopItemItemId ?>"><?php echo "$adminShopItemItemName (Réduction: $adminShopItemDiscount%)"; ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                    <input type="submit" name="edit" class="btn btn-default form-control" value="Modifier la réduction">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer l'objet">
                </form>
                
                <hr>

                <?php
            }
            $townShopQuery->closeCursor();

            $itemQuery = $bdd->query("SELECT * FROM car_items
            ORDER BY itemName");
            $itemRow = $itemQuery->rowCount();
            //S'il existe un ou plusieurs objets on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($itemRow > 0) 
            {
                ?>
                <form method="POST" action="addShopItem.php">
                    <div class="form-group row">
                        <label for="adminShopItemItemId" class="col-2 col-form-label">Articles existant</label>
                        <select class="form-control" id="adminShopItemItemId" name="adminShopItemItemId">
                        <?php
                        while ($item = $itemQuery->fetch())
                        {
                            $adminShopItemItemId = stripslashes($item['itemId']);
                            $adminShopItemItemName = stripslashes($item['itemName']);?>
                            ?>
                                <option value="<?php echo $adminShopItemItemId ?>"><?php echo "$adminShopItemItemName"; ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    Réduction (De 0 à 100%) <br> <input type="number" name="adminShopItemDiscount" class="form-control" placeholder="Réduction (De 0 à 100%)" required><br /><br />
                    <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter l'objet">
                </form>
                <?php
            }
            else
            {
                ?>
                Il n'y a actuellement aucun article
                <?php
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