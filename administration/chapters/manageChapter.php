<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminChapterId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminChapterId'])
    && $_POST['adminChapterId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminChapterId = htmlspecialchars(addslashes($_POST['adminChapterId']));

        //On fait une requête pour vérifier si le chapitre choisi existe
        $chapterQuery = $bdd->prepare('SELECT * FROM car_chapters 
        WHERE chapterId = ?');
        $chapterQuery->execute([$adminChapterId]);
        $chapterRow = $chapterQuery->rowCount();

        //Si le chapitre existe
        if ($chapterRow == 1) 
        {
            //On fait une recherche dans la base de donnée de tous les objets
            while ($chapter = $chapterQuery->fetch())
            {
                $adminChapterId = stripslashes($chapter['chapterId']);
                $adminChapterNumber = stripslashes($chapter['chapterNumber']);
                $adminChapterTitle = stripslashes($chapter['chapterTitle']);
            }
            ?>

            Que souhaitez-vous faire du chapitre <em><?php echo "$adminChapterNumber - $adminChapterTitle"; ?></em> ?

            <hr>
                
            <form method="POST" action="editChapter.php">
                <input type="hidden" class="btn btn-default form-control" name="adminChapterId" value="<?= $adminChapterId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le chapitre">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>

            <?php
        }
        //Si le chapitre n'exite pas
        else
        {
            echo "Erreur: Chapitre indisponible";
        }
        $chapterQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton manage
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");