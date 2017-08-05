<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemTypeId'])
&& isset($_POST['adminItemTypeNameShow'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemTypeId'])
    && $_POST['adminItemTypeId'] >= 1)
    {
        //On récupère les informations du formulaire
        $adminItemTypeId = htmlspecialchars(addslashes($_POST['adminItemTypeId']));
        $adminItemTypeNameShow = htmlspecialchars(addslashes($_POST['adminItemTypeNameShow']));
        
        //On fait une requête pour vérifier si le type d'objet choisit existe
        $itemTypeQuery = $bdd->prepare('SELECT * FROM car_items_types
        WHERE itemTypeId = ?');
        $itemTypeQuery->execute([$adminItemTypeId]);
        $itemTypeRow = $itemTypeQuery->rowCount();

        //Si le type d'objet existe
        if ($itemTypeRow == 1) 
        {
            //On met à jour le type d'objet dans la base de donnée
            $updateItemType = $bdd->prepare('UPDATE car_items_types
            SET itemTypeNameShow = :adminItemTypeNameShow
            WHERE itemTypeId = :adminItemTypeId');
            $updateItemType->execute([
            'adminItemTypeNameShow' => $adminItemTypeNameShow,
            'adminItemTypeId' => $adminItemTypeId]);
            $updateItemType->closeCursor();
            ?>

            Le type d'objet a bien été mit à jour

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
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");