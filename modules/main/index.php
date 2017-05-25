<?php require_once("../../html/header.php");

//On recherche la liste des news dans la base de donnée
$newsListQuery = $bdd->query('SELECT * FROM car_news ORDER BY newsId desc LIMIT 0,4');
$newsList = $newsListQuery->rowCount();

//Si il existe des news on les affiche
if ($newsList > 0)
{
    //Pour chaque news trouvées on l'affiche
    while ($news = $newsListQuery->fetch()) 
    {
        ?>
        <h4><?php echo stripslashes($news['newsTitle']); ?> (Par <?php echo stripslashes($news['newsAccountPseudo']); ?> le <?php echo stripslashes($news['newsDate']); ?>)</h4>
        <?php echo stripslashes(nl2br($news['newsMessage'])); ?>
        <hr>
        <?php
    }
}
//Si il n'y a aucune news on prévient le joueur
else
{
    echo "Il n'y a actuellement aucune news ";
}

$newsListQuery->closeCursor();

require_once("../../html/footer.php"); ?>