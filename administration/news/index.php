<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque news qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de toutes les news
$newsQuery = $bdd->query("SELECT * FROM car_news
ORDER BY newsId");
$newsRow = $newsQuery->rowCount();

//S'il existe une ou plusieurs news on affiche le menu déroulant
if ($newsRow > 0) 
{
    ?>
    
    <form method="POST" action="manageNews.php">.
        Liste des news : <select name="adminNewsId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($news = $newsQuery->fetch())
            {
                //On récupère les informations de la news
                $adminNewsId = stripslashes($news['newsId']);
                $adminNewsTitle = stripslashes($news['newsTitle']);
                ?>
                <option value="<?php echo $adminNewsId ?>"><?php echo "$adminNewsTitle"; ?></option>
                <?php
            }
            ?>
            
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer la news">
    </form>
    
    <?php
}
//S'il n'y a actuellement aucune news on prévient le joueur
else
{
    echo "Il n'y a actuellement aucune news";
}
$newsQuery->closeCursor();
?>

<hr>

<form method="POST" action="addNews.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Publier une news">
</form>

<?php require_once("../html/footer.php");