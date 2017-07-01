<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownId'])
&& isset($_POST['adminTownPicture'])
&& isset($_POST['adminTownName'])
&& isset($_POST['adminTownDescription'])
&& isset($_POST['adminTownPriceInn'])
&& isset($_POST['adminTownChapter'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownId'])
    && ctype_digit($_POST['adminTownPriceInn'])
    && ctype_digit($_POST['adminTownChapter'])
    && $_POST['adminTownId'] >= 1
    && $_POST['adminTownPriceInn'] >= 0
    && $_POST['adminTownChapter'] >= 1)
    {
        //On récupère les informations du formulaire
        $adminTownId = htmlspecialchars(addslashes($_POST['adminTownId']));
        $adminTownPicture = htmlspecialchars(addslashes($_POST['adminTownPicture']));
        $adminTownName = htmlspecialchars(addslashes($_POST['adminTownName']));
        $adminItemDescription = htmlspecialchars(addslashes($_POST['adminTownDescription']));
        $adminTownPriceInn = htmlspecialchars(addslashes($_POST['adminTownPriceInn']));
        $adminTownChapter = htmlspecialchars(addslashes($_POST['adminTownChapter']));

        //On fait une requête pour vérifier si la ville choisit existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId = ?');
        $townQuery->execute([$adminTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1) 
        {
            //On met à jour l'objet dans la base de donnée
            $updateTown = $bdd->prepare('UPDATE car_towns 
            SET townPicture = :adminTownPicture,
            townName = :adminTownName,
            townDescription = :adminItemDescription,
            townPriceInn = :adminTownPriceInn,
            townChapter = :adminTownChapter
            WHERE townId = :adminTownId');

            $updateTown->execute([
            'adminTownPicture' => $adminTownPicture,
            'adminTownName' => $adminTownName,
            'adminItemDescription' => $adminItemDescription,
            'adminTownPriceInn' => $adminTownPriceInn,
            'adminTownChapter' => $adminTownChapter,
            'adminTownId' => $adminTownId]);
            $updateTown->closeCursor();
            ?>

            La ville a bien été mit à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si la ville n'est pas disponible
        else
        {
            echo "Erreur: Ville indisponible";
        }
        $townQuery->closeCursor();
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
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");