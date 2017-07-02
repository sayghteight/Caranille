<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownShopTownId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownShopTownId'])
    && $_POST['adminTownShopTownId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownShopTownId = htmlspecialchars(addslashes($_POST['adminTownShopTownId']));

        //On fait une requête pour vérifier si la ville choisi existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $townQuery->execute([$adminTownShopTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1)
        {
            $townShopQuery = $bdd->prepare("SELECT * FROM car_shops, car_towns, car_towns_shops
            WHERE townShopShopId = shopId
            AND townShopTownId = townId
            AND townId = ?");
            $townShopQuery->execute([$adminTownShopTownId]);
            $townShopRow = $townShopQuery->rowCount();

            //S'il existe un ou plusieurs magasins dans la ville on affiche le menu déroulant
            if ($townShopRow > 0) 
            {
                ?>
                
                <form method="POST" action="deleteTownShop.php">
                    <div class="form-group row">
                        <label for="townMonsterMonsterId" class="col-2 col-form-label">Magasins présent dans la ville</label>
                        <select class="form-control" id="adminTownShopShopId" name="adminTownShopShopId">
                            
                            <?php
                            while ($townShop = $townShopQuery->fetch())
                            {
                                $adminTownShopShopId = stripslashes($townShop['shopId']);
                                $adminTownShopShopName = stripslashes($townShop['shopName']);?>
                                ?>
                                    <option value="<?php echo $adminTownShopShopId ?>"><?php echo "$adminTownShopShopName"; ?></option>
                                <?php
                            }
                            $townShopQuery->closeCursor();
                            ?>
                            
                        </select>
                    </div>
                    <input type="hidden" name="adminTownShopTownId" value="<?= $adminTownShopTownId ?>">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer le magasin">
                </form>
                
                <hr>

                <?php
            }
            $townShopQuery->closeCursor();

            $shopQuery = $bdd->query("SELECT * FROM car_shops");
            $shopRow = $shopQuery->rowCount();
            //S'il existe un ou plusieurs magasin on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($shopRow > 0) 
            {
                ?>
                
                <form method="POST" action="addTownShop.php">
                    <div class="form-group row">
                        <label for="townMonsterMonsterId" class="col-2 col-form-label">Magasins disponible</label>
                        <select class="form-control" id="adminTownShopShopId" name="adminTownShopShopId">
                            
                            <?php
                            while ($shop = $shopQuery->fetch())
                            {
                                $adminTownShopShopId = stripslashes($shop['shopId']);
                                $adminTownShopShopName = stripslashes($shop['shopName']);?>
                                ?>
                                    <option value="<?php echo $adminTownShopShopId ?>"><?php echo "$adminTownShopShopName"; ?></option>
                                <?php
                            }
                            ?>
                            
                        </select>
                    </div>
                    <input type="hidden" name="adminTownShopTownId" value="<?= $adminTownShopTownId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter le magasin">
                </form>
                
                <?php
            }
            else
            {
                ?>
                
                Il n'y a actuellement aucun magasin
                
                <?php
            }
            $shopQuery->closeCursor();
            ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le monstre n'exite pas
        else
        {
            echo "Erreur: Monstre indisponible";
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");