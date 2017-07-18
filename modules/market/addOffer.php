<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['marketItemId'])
&& isset($_POST['marketSalePrice'])
&& isset($_POST['addOffer']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['marketItemId'])
    && ctype_digit($_POST['marketSalePrice'])
    && $_POST['marketItemId'] >= 1
    && $_POST['marketSalePrice'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $marketItemId = htmlspecialchars(addslashes($_POST['marketItemId']));
        $marketSalePrice = htmlspecialchars(addslashes($_POST['marketSalePrice']));

        //On fait une requête pour vérifier si l'objet ou équippement choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId = ?');
        $itemQuery->execute([$marketItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet ou équipement existe
        if ($itemRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'équipement
                $marketItemName = stripslashes($item['itemName']);
            }

            //On vérifit si le joueur possède bien cet objet ou équipement
            $inventoryQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
            WHERE itemId = inventoryItemId
            AND inventoryCharacterId = ?
            AND itemId = ?");
            $inventoryQuery->execute([$characterId, $marketItemId]);
            $inventoryRow = $inventoryQuery->rowCount();

            //Si l'objet ou équipement est bien dans l'inventaire du joueur
            if ($inventoryRow == 1) 
            {
                ?>

                <p>ATTENTION</p> 
                
                Vous êtes sur le point de mettre en ligne l'offre concernant l'article <em><?php echo $marketItemName ?></em> pour <em><?php echo $marketSalePrice ?> Pièce(s) d'or</em>.<br />
                Une fois l'offre mise en ligne vous ne pourrez plus modifier le prix de vente ni annuler l'offre<br />
                Confirmez-vous la mise en ligne ?

                <hr>

                <form method="POST" action="addOfferEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="marketItemId" value="<?= $marketItemId ?>">
                    <input type="hidden" class="btn btn-default form-control" name="marketSalePrice" value="<?= $marketSalePrice ?>">
                    <input type="submit" class="btn btn-default form-control" name="addOfferEnd" value="Je confirme">
                </form>

                <hr>

                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            //Si le joueur ne possède pas cet objet ou équippement
            else
            {
                echo "Erreur: Vous ne pouvez pas mettre en vente un objet ou équippement que vous ne possedez pas";
            }
            $inventoryQuery->closeCursor();
        }
        //Si l'objet ou l'équippement n'exite pas
        else
        {
            echo "Erreur: Cette objet ou équippement n'existe pas";
        }
        $itemQuery->closeCursor();
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
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>