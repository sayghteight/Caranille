<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton delete
if (isset($_POST['adminItemId'])
&& isset($_POST['delete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemId'])
    && $_POST['adminItemId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));

        //On fait une requête pour vérifier si l'objet choisit existe
        $itemQuery = $bdd->prepare('SELECT * FROM car_items 
        WHERE itemId= ?');
        $itemQuery->execute([$adminItemId]);
        $itemRow = $itemQuery->rowCount();

        //Si l'objet est disponible
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
            <p>ATTENTION</p> 
            Vous êtes sur le point de supprimer l'équipement <em><?php echo $adminItemName ?></em><br />
            confirmez-vous la suppression ?

            <hr>
                
            <form method="POST" action="deleteItemEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="adminItemId" value="<?= $adminItemId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
            </form>
    
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si l'objet n'est pas disponible
        else
        {
            echo "Erreur: Objet indisponible";
        }
        $itemQuery->closeCursor();
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
        echo "Erreur: Objet invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton delete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");