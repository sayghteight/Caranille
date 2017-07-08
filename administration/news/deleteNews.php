<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminNewsId'])
&& isset($_POST['delete']))
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
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($news = $newsQuery->fetch())
            {
                $adminNewsPicture = stripslashes($news['newsPicture']);
                $adminNewstitle = stripslashes($news['newsTitle']);
            }
            ?>

            <p><img src="<?php echo $adminNewsPicture; ?>" height="100" width="100"></p>
            
            <p>ATTENTION</p>

            Vous êtes sur le point de retirer la news <em><?php echo $adminNewstitle ?></em><br />
            confirmez-vous ?

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