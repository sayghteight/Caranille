<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//On fait une recherche dans la base de donnée de tous les chapitres
$chapterQuery = $bdd->query("SELECT * FROM car_chapters");
$chapterRow = $chapterQuery->rowCount();

//Si il y a au moins un chapitre
if ($chapterRow > 0)
{
    ?>
    
    <form method="POST" action="manageChapter.php">
        Liste des chapitres : <select name="adminChapterId" class="form-control">
                
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($chapter = $chapterQuery->fetch())
            {
                //On récupère les informations du chapitre
                $adminChapterId = stripslashes($chapter['chapterId']);
                $adminChapterTitle = stripslashes($chapter['chapterTitle']);
                ?>
    
                    <option value="<?php echo $adminChapterId ?>"><?php echo "Chapitre $adminChapterId - $adminChapterTitle"; ?></option>
    
                <?php
            }
            $chapterQuery->closeCursor();
            ?>
    
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer le chapitre">
    </form>
<?php
}
//Si il n'y a aucun chapitre
else 
{
    echo "Il n'y a actuellement aucun chapitre";
}
?>

<hr>

<form method="POST" action="addChapter.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Nouveau chapitre">
</form>

<?php require_once("../html/footer.php");