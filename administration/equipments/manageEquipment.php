<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId'])
    && $_POST['adminItemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
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
            echo "Erreur: équipements indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'équippement choisit n'est pas un nombre
    else
    {
        echo "Erreur: équipements invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");