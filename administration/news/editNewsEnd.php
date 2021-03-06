<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminNewsId'])
&& isset($_POST['adminNewsPicture'])
&& isset($_POST['adminNewsTitle'])
&& isset($_POST['adminNewsMessage'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminNewsId'])
    && $_POST['adminNewsId'] >= 1)
    {
        //On récupère les informations du formulaire
        $adminNewsId = htmlspecialchars(addslashes($_POST['adminNewsId']));
        $adminNewsPicture = htmlspecialchars(addslashes($_POST['adminNewsPicture']));
        $adminNewsTitle = htmlspecialchars(addslashes($_POST['adminNewsTitle']));
        $adminNewsMessage = htmlspecialchars(addslashes($_POST['adminNewsMessage']));

        //On fait une requête pour vérifier si la news choisie existe
        $newsQuery = $bdd->prepare('SELECT * FROM car_news 
        WHERE newsId = ?');
        $newsQuery->execute([$adminNewsId]);
        $newsRow = $newsQuery->rowCount();

        //Si la news existe
        if ($newsRow == 1) 
        {
            //On met à jour la news dans la base de donnée
            $updateNews = $bdd->prepare('UPDATE car_news
            SET newsPicture = :adminNewsPicture,
            newsTitle = :adminNewsTitle,
            newsMessage = :adminNewsMessage
            WHERE newsId = :adminNewsId');
            $updateNews->execute([
            'adminNewsPicture' => $adminNewsPicture,
            'adminNewsTitle' => $adminNewsTitle,
            'adminNewsMessage' => $adminNewsMessage,
            'adminNewsId' => $adminNewsId]);
            $updateNews->closeCursor();
            ?>

            La news a bien été mise à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si la news n'exite pas
        else
        {
            echo "Erreur: Cette news n'existe pas";
        }
        $newsQuery->closeCursor();
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