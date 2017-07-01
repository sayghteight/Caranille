<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminAccountId']) 
&& isset($_POST['adminAccountPseudo']) 
&& isset($_POST['adminAccountEmail']) 
&& isset($_POST['adminAccountAccess'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si l'id du compte récupéré dans le formulaire est en entier positif
    if (ctype_digit($_POST['adminAccountId'])
    && ctype_digit($_POST['adminAccountAccess'])
    && $_POST['adminAccountId'] >= 1)
    {
        //On récupère l'id du compte
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
            //On récupère les informations du formulaire
            $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));
            $adminAccountPseudo = htmlspecialchars(addslashes($_POST['adminAccountPseudo']));
            $adminAccountEmail = htmlspecialchars(addslashes($_POST['adminAccountEmail']));
            $adminAccountAccess =  htmlspecialchars(addslashes($_POST['adminAccountAccess']));

            //On met à jour le compte dans la base de donnée
            $updateAccount = $bdd->prepare('UPDATE car_accounts 
            SET accountPseudo = :adminAccountPseudo, 
            accountEmail = :adminAccountEmail, 
            accountAccess = :adminAccountAccess
            WHERE accountId = :adminAccountId');

            $updateAccount->execute([
            'adminAccountPseudo' => $adminAccountPseudo,
            'adminAccountEmail' => $adminAccountEmail,
            'adminAccountAccess' => $adminAccountAccess,
            'adminAccountId' => $adminAccountId]);
            $updateAccount->closeCursor();
            ?>

            Le compte a bien été mit à jour

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
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");