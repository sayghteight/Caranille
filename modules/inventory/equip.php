<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['itemId'])
&& isset($_POST['equip']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['itemId'])
    && $_POST['itemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $itemId = htmlspecialchars(addslashes($_POST['itemId']));

        //On cherche à savoir si l'équipement que l'on va équipper appartient bien au joueur
        $itemQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
        WHERE itemId = inventoryItemId
        AND inventoryCharacterId = ?
        AND itemId = ?");
        $itemQuery->execute([$characterId, $itemId]);
        $itemRow = $itemQuery->rowCount();

        //Si le joueur possède cet équipement
        if ($itemRow == 1) 
        {
            //On récupère les informations de l'équipement
            while ($item = $itemQuery->fetch())
            {
                //On récupère les informations de l'équippement
                $inventoryId = stripslashes($item['inventoryId']);
                $itemRaceId = stripslashes($item['itemRaceId']);
                $itemType = stripslashes($item['itemType']);
                $itemName = stripslashes($item['itemName']);
            }
            $itemQuery->closeCursor();

            //On vérifie si la classe du joueur lui permet de s'équiper de cet équipement, ou si celui-ci est pour toutes les classes
            if ($characterRaceId == $itemRaceId || $itemRaceId == 0)
            {
                ?>
                
                <p>ATTENTION</p> 
                Vous êtes sur le point de vous équipper de l'équipement <em><?php echo $itemName ?></em>.<br />
                Confirmez-vous ?
                
                <hr>
    
                <form method="POST" action="equipEnd.php">
                    <input type="hidden" class="btn btn-default form-control" name="itemId" value="<?php echo $itemId ?>">
                    <input type="submit" class="btn btn-default form-control" name="finalEquip" value="Je confirme">
                </form>
    
                <hr>
    
                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
                
                <?php
            }
            //Si la classe de l'objet est incompatible avec celle du joueur
            else
            {
                ?>
                
                Votre classe ne vous permet pas de vous équiper de cet équipement";
                
                <hr>

                <form method="POST" action="equipment.php">
                    <input type="submit" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
            }
        }
        //Si le joueur ne possèdep pas cet équipement
        else
        {
            echo "Erreur: Vous ne possedez pas cet équipement";
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