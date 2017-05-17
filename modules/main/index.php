<?php require_once("../../html/header.php");

//On recherche la liste des news dans la base de donnée
$newsList = $bdd->query('SELECT * FROM car_news ORDER BY newsId desc LIMIT 0,4');

//Pour chaque news trouvées on l'affiche
while ($news = $newsList->fetch()) 
{
    $newsTitle = stripslashes($news['newsTitle']);
    $newsDate = stripslashes($news['newsDate']);
    echo "<a href=\"../../modules/news/index.php\">$newsTitle ($newsDate)</a><br />";
}
$newsList->closeCursor();

require_once("../../html/footer.php"); ?>