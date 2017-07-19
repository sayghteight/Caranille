<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['itemId'])
&& isset($_POST['unEquip']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));

        //On cherche à savoir si l'objet qui va se vendre appartient bien au joueur
        $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
        WHERE itemId = inventoryItemId
        AND inventoryCharacterId = ?
        AND itemId = ?");
        $itemQuery->execute([$characterId, $itemId]);
        $itemRow = $itemQuery->rowCount();

        //Si le personne possède cet objet
        if ($itemRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'objet
                $inventoryId = stripslashes($item['inventoryId']);
                $itemQuantity = stripslashes($item['inventoryQuantity']);
                $itemName = stripslashes($item['itemName']);
                $itemSalePrice = stripslashes($item['itemSalePrice']);
                $inventoryEquipped = stripslashes($item['inventoryEquipped']);
            }
            $itemQuery->closeCursor();
            ?>

            <p>ATTENTION</p> 
            Vous êtes sur le point de déséquiper l'équipement <em><?php echo $itemName ?></em>.<br />
            Confirmez-vous ?

            <form method="POST" action="unEquipEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="itemId" value="<?php echo $itemId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalUnEquip" value="Je confirme">
            </form>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        else
        {
            echo "Erreur: Impossible de vendre un objet/équipement que vous ne possédez pas.";
        }
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