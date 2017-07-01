<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque news qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de toutes les news
$newsQuery = $bdd->query("SELECT * FROM car_news");
$newsRow = $newsQuery->rowCount();

//S'il existe une ou plusieurs news on affiche le menu déroulant
if ($newsRow > 0) 
{
    ?>
    <form method="POST" action="manageNews.php">
        <div class="form-group row">
            <label for="adminNewsId" class="col-2 col-form-label">Liste des news</label>
            <select class="form-control" id="adminNewsId" name="adminNewsId">
            <?php
            while ($news = $newsQuery->fetch())
            {
                $adminNewsId = stripslashes($news['newsId']);
                $adminNewsTitle = stripslashes($news['newsTitle']);?>
                ?>
                    <option value="<?php echo $adminNewsId ?>"><?php echo "$adminNewsTitle"; ?></option>
                <?php
            }
            ?>
            </select>
        </div>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer">
    </form>
    <?php
}
//S'il n'y a actuellement aucune news on prévient le joueur
else
{
    ?>
    Il n'y a actuellement aucune news
        
    <hr>

    <?php
}
$newsQuery->closeCursor();
?>

<form method="POST" action="addNews.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Ajouter">
</form>

<?php require_once("../html/footer.php");