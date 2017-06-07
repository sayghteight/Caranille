<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton manage
if (isset($_POST['adminItemId'])
&& isset($_POST['manage']))
{
    //On vérifie si l'id de l'équippement choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminItemId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'équippement choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'équippement est disponible
        if ($itemRow == 1) 
        {
            //On fait une recherche dans la base de donnée de tous les comptes
            $itemQuery = $bdd->prepare("SELECT * FROM car_items
            WHERE itemId = ?");
            $itemQuery->execute([$adminItemId]);
            while ($item = $itemQuery->fetch())
            {
                $adminItemName = stripslashes($item['itemName']);
            }
            $itemQuery->closeCursor();

            ?> 
            Que souhaitez-vous faire de l'équippement <em><?php echo $adminItemName ?></em><br />

            <hr>
                
            <form method="POST" action="editEquipment.php">
                <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?= $adminItemId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier l'équippement">
            </form>
            <form method="POST" action="deleteEquipment.php">
                <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?= $adminItemId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer l'équippement">
            </form>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
        <?php
        }
        //Si l'équipement n'est pas disponible
        else
        {
            echo "Erreur: Equippement indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'équippement choisit n'est pas un nombre
    else
    {
        echo "Erreur: Equippement invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton manage
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");