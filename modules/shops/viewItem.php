<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['shopId'])
&& isset($_POST['itemId'])
&& isset($_POST['view']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['shopId'])
    && ctype_digit($_POST['itemId'])
    && $_POST['shopId'] >= 1
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $shopId = htmlspecialchars(addslashes($_POST['shopId']));
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId = ?');
        $shopQuery->execute([$shopId]);
        $shopRow = $shopQuery->rowCount();

        //Si le magasin existe
        if ($shopRow == 1) 
        {
            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$itemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet existe
            if ($itemRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($item = $itemQuery->fetch())
                {
                    //On récupère les informations de l'objet
                    $itemRaceId = stripslashes($item['itemRaceId']);
                    $itemType = stripslashes($item['itemType']);
                    $itemLevel = stripslashes($item['itemLevel']);
                    $itemLevelRequired = stripslashes($item['itemLevelRequired']);
                    $itemName = stripslashes($item['itemName']);
                    $itemDescription = stripslashes($item['itemDescription']);
                    $itemHpEffect = stripslashes($item['itemHpEffect']);
                    $itemMpEffect = stripslashes($item['itemMpEffect']);
                    $itemStrengthEffect = stripslashes($item['itemStrengthEffect']);
                    $itemMagicEffect = stripslashes($item['itemMagicEffect']);
                    $itemAgilityEffect = stripslashes($item['itemAgilityEffect']);
                    $itemDefenseEffect = stripslashes($item['itemDefenseEffect']);
                    $itemDefenseMagicEffect = stripslashes($item['itemDefenseMagicEffect']);
                    $itemWisdomEffect = stripslashes($item['itemWisdomEffect']);
                    $itemSalePrice = stripslashes($item['itemSalePrice']);
                    $itemPurchasePrice = stripslashes($item['itemPurchasePrice']);
                }

                //Si la race de l'équipement est supérieur à 1 c'est qu'il est attitré à une classe
                if ($itemRaceId >= 1)
                {
                    //On récupère la classe de l'équipement
                    $raceQuery = $bdd->prepare("SELECT * FROM car_races
                    WHERE raceId = ?");
                    $raceQuery->execute([$itemRaceId]);
                    while ($race = $raceQuery->fetch())
                    {
                        //On récupère le nom de la classe
                        $itemRaceName = stripslashes($race['raceName']);
                    }
                    $raceQuery->closeCursor();
                }
                else
                {
                    $itemRaceName = "Toutes les classes";
                }
                
                //On fait une requête pour récupérer les informations de l'objet du magasin
                $shopItemQuery = $bdd->prepare('SELECT * FROM car_shops_items
                WHERE shopItemShopId = ?
                AND shopItemItemId = ?');
                $shopItemQuery->execute([$shopId, $itemId]);
                $shopItemRow = $shopItemQuery->rowCount();

                //On récupère le taux de réduction de l'objet/équipement
                while ($shopItem = $shopItemQuery->fetch())
                {
                    //On récupère les informations du magasin
                    $itemDiscount = stripslashes($shopItem['shopItemDiscount']);
                }

                $discount = $itemPurchasePrice * $itemDiscount / 100;
                $itemPurchasePrice = $itemPurchasePrice - $discount; 
                ?>
                
                <table class="table">
                    
                    <?php
                    //S'il s'agit d'un équipement on affiche la race de celui-ci ainsi que son niveu requis
                    if ($itemType != "Item")
                    {
                        ?>
                        <tr>
                            <td>
                                Classe requise
                            </td>
                        
                            <td>
                                <?php echo $itemRaceName; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Niveau de l'objet
                            </td>

                            <td>
                                <?php echo $itemLevel; ?>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Niveau requis
                            </td>
                            
                            <td>
                                <?php echo $itemLevelRequired; ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td>
                            Nom
                        </td>
                        
                        <td>
                            <?php echo $itemName; ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Description
                        </td>
                        
                        <td>
                            <?php echo nl2br($itemDescription); ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Effet(s)
                        </td>
                        
                        <td>
                            <?php
                            //S'il s'agit d'un équipement on affiche toutes les stats concernée
                            if ($itemType != "Item")
                            {
                                ?>
                                <?php echo '+' .$itemHpEffect. ' HP'; ?><br />
                                <?php echo '+' .$itemMpEffect. ' MP'; ?><br />
                                <?php echo '+' .$itemStrengthEffect. ' Force'; ?><br />
                                <?php echo '+' .$itemMagicEffect. ' Magie'; ?><br />
                                <?php echo '+' .$itemAgilityEffect. ' Agilité'; ?><br />
                                <?php echo '+' .$itemDefenseEffect. ' Défense'; ?><br />
                                <?php echo '+' .$itemDefenseMagicEffect. ' Défense magique'; ?><br />
                                <?php echo '+' .$itemWisdomEffect. ' Sagesse'; ?>
                                
                                <?php
                            }
                            //S'il s'agit d'un objet on affiche que les stats HP et MP qui sont concernée
                            else
                            {
                                ?>
                                <?php echo '+' .$itemHpEffect. ' HP'; ?><br />
                                <?php echo '+' .$itemMpEffect. ' MP'; ?><br />
                                <?php
                            }
                            ?>                            
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Prix de vente
                        </td>
                        
                        <td>
                            <?php echo $itemSalePrice; ?>
                        </td>
                    </tr>
                        
                    <tr>
                        <td>
                            Actions
                        </td>
                        
                        <td>
                            <form method="POST" action="buyItem.php">
                                <input type="hidden" class="btn btn-default form-control" name="shopId" value="<?= $shopId ?>">
                                <input type="hidden" class="btn btn-default form-control" name="itemId" value="<?= $itemId ?>">
                                <input type="submit" class="btn btn-default form-control" name="buy" value="Acheter">
                            </form>
                        </td>
                    </tr>
                </table>
    
                <hr>

                <form method="POST" action="selectedShop.php">
                    <input type="hidden" class="btn btn-default form-control" name="shopId" value="<?= $shopId ?>">         
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            //Si l'article n'exite pas
            else
            {
                echo "Erreur: Cet article n'existe pas";
            }
            $itemQuery->closeCursor();
        }
        //Si le magasin n'exite pas
        else
        {
            echo "Erreur: Ce magasin n'existe pas";
        }
        $shopQuery->closeCursor();
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
         echo "L'équipment choisit est invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>