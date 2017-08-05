<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemTypeId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemTypeId'])
    && $_POST['adminItemTypeId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminItemTypeId = htmlspecialchars(addslashes($_POST['adminItemTypeId']));

        //On fait une requête pour vérifier si le type d'objet choisit existe
        $itemTypeQuery = $bdd->prepare('SELECT * FROM car_items_types
        WHERE itemTypeId = ?');
        $itemTypeQuery->execute([$adminItemTypeId]);
        $itemTypeRow = $itemTypeQuery->rowCount();

        //Si le type d'objet existe
        if ($itemTypeRow == 1) 
        {
            //On fait une boucle pour récupérer toutes les information
            while ($itemType = $itemTypeQuery->fetch())
            {
                //On récupère les informations du chapitre
                $adminItemTypeId = stripslashes($itemType['itemTypeId']);
                $adminItemTypeNameShow = stripslashes($itemType['itemTypeNameShow']);
            }
            ?>

            <p>Informations du type d'objet</p>
            
            <form method="POST" action="editItemTypeEnd.php">
                Type d'objet : <input type="text" name="adminItemTypeNameShow" class="form-control" placeholder="Titre" value="<?php echo $adminItemTypeNameShow ?>" required>
                <input type="hidden" name="adminItemTypeId" value="<?php echo $adminItemTypeId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le type d'objet n'exite pas
        else
        {
            echo "Erreur: Ce type d'objet n'existe pas";
        }
        $itemTypeQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");