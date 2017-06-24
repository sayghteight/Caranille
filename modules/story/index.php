<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On recherche le chapitre de l'histoire du joueur
$chapterQuery = $bdd->prepare("SELECT * FROM car_chapters
WHERE chapterId = ?");
$chapterQuery->execute([$characterChapter]);
$chapterRow = $chapterQuery->rowCount();

//Si le chapitre du joueur est disponible
if ($chapterRow == 1)
{
	while ($chapter = $chapterQuery->fetch())
	{
		$chapterId = stripslashes($chapter['chapterId']);
		$chapterTitle = stripslashes($chapter['chapterTitle']);
		$chapterOpening = stripslashes(nl2br($chapter['chapterOpening']));
		$chapterEnding = stripslashes(nl2br($chapter['chapterEnding']));
	}
	$chapterQuery->closeCursor();
	?>
	Chapitre <?php echo $chapterId; ?> - <?php echo $chapterTitle; ?>
	
	<hr>
	
	<?php echo $chapterOpening; ?>
		
	<hr>
	
    <form method="POST" action="story.php">
        <input type="hidden" class="btn btn-default form-control" name="chapterId" value="<?= $chapterId ?>">
        <input type="submit" class="btn btn-default form-control" name="continue" value="Continuer">
    </form>
	<?php
}
else 
{
	echo "Il n'y a actuellement aucun nouveau chapitre";
}
?>


<?php require_once("../../html/footer.php"); ?>