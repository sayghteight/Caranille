<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque chapitres qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les chapitres
$chapterQuery = $bdd->query("SELECT * FROM car_chapters");
$chapterRow = $chapterQuery->rowCount();

//Si il existe un ou plusieurs chapitre(s) on affiche le menu déroulant
if ($chapterRow > 0) 
{
    ?>
    <form method="POST" action="manageChapter.php">
        <div class="form-group row">
            <label for="adminChapterId" class="col-2 col-form-label">Liste des chapitres</label>
            <select class="form-control" id="adminChapterId" name="adminChapterId">
            <?php

            while ($chapter = $chapterQuery->fetch())
            {
                $adminChapterId = stripslashes($chapter['chapterId']);
                $adminChapterTitle = stripslashes($chapter['chapterTitle']);
                ?>
                    <option value="<?php echo $adminChapterId ?>"><?php echo "Chapitre $adminChapterId - $adminChapterTitle"; ?></option>
                <?php
            }
            $chapterQuery->closeCursor();
            ?>
            </select>
        </div>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer">
    </form>
    <?php
}
//Si il n'y a actuellement aucun objet on prévient le joueur
else
{
    ?>
    Il n'y a actuellement aucun chapitre
    <?php
}
?>

<form method="POST" action="addChapter.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Nouveau">
</form>
<?php require_once("../html/footer.php");