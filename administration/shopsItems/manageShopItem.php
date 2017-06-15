<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton manage
if (isset($_POST['adminShopItemShopId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopItemShopId'])
    && $_POST['adminShopItemShopId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminShopItemShopId = htmlspecialchars(addslashes($_POST['adminShopItemShopId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $shopQuery->execute([$adminShopItemShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe est disponible
        if ($shopRow == 1)
        {
            $townShopQuery = $bdd->prepare("SELECT * FROM car_shops, car_items, car_shops_items
            WHERE shopItemShopId = shopId
            AND shopItemItemId = itemId
            AND shopId = ?
            ORDER BY itemName");
            $townShopQuery->execute([$adminShopItemShopId]);
            $townShopRow = $townShopQuery->rowCount();

            //Si il existe un ou plusieurs magasins dans la ville on affiche le menu déroulant
            if ($townShopRow > 0) 
            {
                ?>
                <form method="POST" action="deleteShopItem.php">
                    <div class="form-group row">
                        <label for="townMonsterMonsterId" class="col-2 col-form-label">Objets/équippements présent dans le magasin</label>
                        <select class="form-control" id="adminTownShopItemId" name="adminTownShopItemId">
                        <?php
                        while ($townShop = $townShopQuery->fetch())
                        {
                            $adminTownShopItemId = stripslashes($townShop['itemId']);
                            $adminTownShopItemName = stripslashes($townShop['itemName']);
                            $adminTownShopDiscount = stripslashes($townShop['shopItemDiscount']);?>
                            ?>
                                <option value="<?php echo $adminTownShopItemId ?>"><?php echo "$adminTownShopItemName (Réduction: $adminTownShopDiscount%)"; ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer">
                </form>
                
                <hr>

                <?php
            }
            $townShopQuery->closeCursor();

            $itemQuery = $bdd->query("SELECT * FROM car_items
            ORDER BY itemName");
            $itemRow = $itemQuery->rowCount();
            //Si il existe un ou plusieurs monstres on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($itemRow > 0) 
            {
                ?>
                <form method="POST" action="addShopItem.php">
                    <div class="form-group row">
                        <label for="adminMonsterDropItemId" class="col-2 col-form-label">Objets/équippements existant</label>
                        <select class="form-control" id="adminMonsterDropItemId" name="adminMonsterDropItemId">
                        <?php
                        while ($item = $itemQuery->fetch())
                        {
                            $adminTownShopItemId = stripslashes($townShop['itemId']);
                            $adminTownShopItemName = stripslashes($townShop['itemName']);?>
                            ?>
                                <option value="<?php echo $adminTownShopItemId ?>"><?php echo "$adminTownShopItemName"; ?></option>
                            <?php
                        }
                        $itemQuery->closeCursor();
                        ?>
                        </select>
                    </div>
                    Réduction (De 0 à 100%) <br> <input type="number" name="adminShopItemDiscount" class="form-control" placeholder="Réduction (De 0 à 100%)" required><br /><br />
                    <input type="hidden" name="adminShopItemShopId" value="<?= $adminShopItemShopId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter cet objet/équippement">
                </form>
                <?php
            }
            else
            {
                ?>
                Il n'y a actuellement aucun objet
                <?php
            }
            ?>

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
        echo "Erreur: Magasin invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton manage
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");