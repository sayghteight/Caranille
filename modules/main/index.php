<?php require_once("../../html/header.php");

//On recherche la liste des news dans la base de donnée
$newsQuery = $bdd->query('SELECT * FROM car_news ORDER BY newsId desc LIMIT 0,4');
$newsRow = $newsQuery->rowCount();

//Si il existe des news on les affiche
if ($newsRow > 0)
{
    //Pour chaque news trouvées on l'affiche
    while ($news = $newsQuery->fetch()) 
    {
        $dateFr = strftime('%d-%m-%Y',strtotime($news['newsDate']));
        ?>
        <h4><?php echo stripslashes($news['newsTitle']); ?> (Par <?php echo stripslashes($news['newsAccountPseudo']); ?> le <?php echo stripslashes($dateFr); ?>)</h4>
        <?php echo stripslashes(nl2br($news['newsMessage'])); ?>
        <hr>
        <?php
    }
    $newsQuery->closeCursor();
}
//Si il n'y a aucune news on prévient le joueur
else
{
    echo "Il n'y a actuellement aucune news ";
}

require_once("../../html/footer.php"); ?>