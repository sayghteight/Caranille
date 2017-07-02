<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminAccountId'])
    && $_POST['adminAccountId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

        //On fait une requête pour vérifier si le compte choisit existe
        $accountQuery = $bdd->prepare('SELECT * FROM car_accounts 
        WHERE accountId= ?');
        $accountQuery->execute([$adminAccountId]);
        $account = $accountQuery->rowCount();
        $accountQuery->closeCursor();

        //Si le compte existe
        if ($account == 1) 
        {
            $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

            //On supprime le compte de la base de donnée
            $accountDeleteQuery = $bdd->prepare("DELETE FROM car_accounts
            WHERE accountId = ?");
            $accountDeleteQuery->execute([$adminAccountId]);
            $accountDeleteQuery->closeCursor();

            //On supprime aussi le personnage de la base de donnée
            $characterDeleteQuery = $bdd->prepare("DELETE FROM car_characters
            WHERE characterAccountId = ?");
            $characterDeleteQuery->execute([$adminAccountId]);
            $characterDeleteQuery->closeCursor();
            ?>

            Le compte a bien été supprimé

            <hr>
                
            <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                </form>
            <?php
        }
        //Si le compte n'existe pas
        else
        {
            echo "Erreur: Ce compte n'existe pas";
        }
        $accountQuery->closeCursor();
    }
    //Si le compte choisit n'est pas un nombre
    else
    {
        echo "Erreur: Le compte choisit est incorrect";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");