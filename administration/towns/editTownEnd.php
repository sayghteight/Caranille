<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
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
            //On met à jour la ville dans la base de donnée
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
            
            //On recherche tous les joueurs qui sont dans cette ville et ont les met dans la carte du monde
            $characterTownQuery = $bdd->prepare('SELECT * FROM car_characters 
            WHERE characterTownId = ?');
            $characterTownQuery->execute([$adminTownId]);

            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($characterTown = $characterTownQuery->fetch())
            {
                //On récupère les informations des personnages
                $adminCharacterId = stripslashes($characterTown['characterId']);
                $adminCharacterName = stripslashes($characterTown['characterName']);
                $adminCharacterChapter = stripslashes($characterTown['characterChapter']);
                
                //Si le joueur à un niveau de chapitre supérieur ou égal à celui requis pour être dans la ville on ne fait rien
                if ($adminCharacterChapter >= $adminTownChapter)
                {
                    echo "Le joueur $adminCharacterName est au chapitre $adminCharacterChapter il peut donc rester dans cette ville<br />"; 
                }
                else
                {
                    echo "Le joueur $adminCharacterName est au chapitre $adminCharacterChapter il ne peut donc pas rester dans cette ville<br />"; 
                    
                    //On met à jour les personnages
                    $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                    characterTownId = 0
                    WHERE characterId = :adminCharacterId");
                    
                    $updateCharacter->execute(array(
                    'adminCharacterId' => $adminCharacterId));
                    $updateCharacter->closeCursor();
                }
            }
            ?>

            La ville a bien été mit à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si la ville n'exite pas
        else
        {
            echo "Erreur: Cette ville n'existe pas";
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