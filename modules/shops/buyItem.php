<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['shopId'])
&& isset($_POST['itemId'])
&& isset($_POST['buy']))
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
                    $itemName = stripslashes($item['itemName']);
                    $itemPurchasePrice = stripslashes($item['itemPurchasePrice']);
                }
                
                //On fait une requête pour récupérer les informations de l'objet du magasin
                $shopItemQuery = $bdd->prepare('SELECT * FROM car_shops_items
                WHERE shopItemShopId = ?
                AND shopItemItemId = ?');
                $shopItemQuery->execute([$shopId, $itemId]);
                $shopItemRow = $shopItemQuery->rowCount();

                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($shopItem = $shopItemQuery->fetch())
                {
                    //On récupère les informations du magasin
                    $itemDiscount = stripslashes($shopItem['shopItemDiscount']);
                }

                $discount = $itemPurchasePrice * $itemDiscount / 100;
                $itemPurchasePrice = $itemPurchasePrice - $discount; 
                ?>

                <p>ATTENTION</p> 
                
                Vous êtes sur le point d'acheter l'article <em><?php echo $itemName ?> pour <?php echo $itemPurchasePrice ?> Pièce(s) d'or.</em><br />
                Confirmez-vous l'achat ?
                

                <form method="POST" action="buyItemEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="shopId" value="<?= $shopId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="itemId" value="<?= $itemId ?>">
                    <input type="submit" class="btn btn-default form-control" name="finalBuy" value="Je confirme">
                </form>
    
                <hr>

                <form method="POST" action="index.php">
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