<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminNewsId'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminNewsId'])
    && $_POST['adminNewsId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminNewsId = htmlspecialchars(addslashes($_POST['adminNewsId']));

        //On fait une requête pour vérifier si la news choisie existe
        $newsQuery = $bdd->prepare('SELECT * FROM car_news 
        WHERE newsId = ?');
        $newsQuery->execute([$adminNewsId]);
        $newsRow = $newsQuery->rowCount();

        //Si la news existe
        if ($newsRow == 1) 
        {
            //On fait une boucle pour récupérer toutes les informations
            while ($news = $newsQuery->fetch())
            {
                //On récupère les informations de la news
                $adminNewsId = stripslashes($news['newsId']);
                $adminNewsPicture = stripslashes($news['newsPicture']);
                $adminNewsTitle = stripslashes($news['newsTitle']);
                $adminNewsMessage = stripslashes($news['newsMessage']);
            }
            ?>

            <p>Informations de la news</p>
            <form method="POST" action="editNewsEnd.php">
                Image : <br> <input type="text" name="adminNewsPicture" class="form-control" placeholder="Image" value="<?php echo $adminNewsPicture ?>" required>
                Titre : <br> <input type="text" name="adminNewsTitle" class="form-control" placeholder="Titre" value="<?php echo $adminNewsTitle ?>"required>
                Message : <br> <textarea class="form-control" name="adminNewsMessage" id="adminNewsMessage" rows="3" required><?php echo $adminNewsMessage ?></textarea>
                <input type="hidden" name="adminNewsId" value="<?= $adminNewsId ?>">
                <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
            </form>
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si la news n'exite pas
        else
        {
            echo "Erreur: News indisponible";
        }
        $newsQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");