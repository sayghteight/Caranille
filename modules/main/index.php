<?php require_once("../../html/header.php");

//On recherche la liste des news dans la base de donnée
$newsQuery = $bdd->query('SELECT * FROM car_news ORDER BY newsId desc LIMIT 0,4');
$newsRow = $newsQuery->rowCount();

//S'il existe des news on les affiche
if ($newsRow > 0)
{
    //Pour chaque news trouvées on l'affiche
    while ($news = $newsQuery->fetch()) 
    {
        ?>
        
        <p><img src="<?php echo stripslashes($news['newsPicture']); ?>" height="100" width="100"></p>
        
        <h4><?php echo stripslashes($news['newsTitle']); ?> (le <?php echo strftime('%d-%m-%Y',strtotime($news['newsDate'])); ?> par <?php echo stripslashes($news['newsAccountPseudo']); ?>)</h4>
        <?php echo stripslashes(nl2br($news['newsMessage'])); ?><br /><br />
        
        <hr>
        
        <?php
    }
    $newsQuery->closeCursor();
}
//S'il n'y a aucune news on prévient le joueur
else
{
    echo "Il n'y a actuellement aucune news ";
}

require_once("../../html/footer.php"); ?>