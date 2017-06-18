<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton delete
if (isset($_POST['adminNewsId'])
&& isset($_POST['delete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminNewsId'])
    && $_POST['adminNewsId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminNewsId = htmlspecialchars(addslashes($_POST['adminNewsId']));

        //On fait une requête pour vérifier si la news choisie existe
        $newsQuery = $bdd->prepare('SELECT * FROM car_news 
        WHERE newsId= ?');
        $newsQuery->execute([$adminNewsId]);
        $newsRow = $newsQuery->rowCount();

        //Si la news est disponible
        if ($newsRow == 1) 
        {
            //On récupère l'information de la news
            while ($news = $newsQuery->fetch())
            {
                $adminNewstitle = stripslashes($news['newsTitle']);
            }

            ?>
            <p>ATTENTION</p> 
            Vous êtes sur le point de supprimer la news <em><?php echo $adminNewstitle ?></em><br />
            confirmez-vous la suppression ?

            <hr>
                
            <form method="POST" action="deleteNewsEnd.php">
                <input type="hidden" class="btn btn-default form-control" name="adminNewsId" value="<?= $adminNewsId ?>">
                <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme">
            </form>
    
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si la news n'est pas disponible
        else
        {
            echo "Erreur: News indisponible";
        }
        $newsQuery->closeCursor();
    }
    //Si l'objet choisit n'est pas un nombre
    else
    {
        echo "Erreur: Objet invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton delete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");