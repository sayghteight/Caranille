<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['adminAccountId']) 
&& isset($_POST['adminAccountPseudo']) 
&& isset($_POST['adminAccountEmail']) 
&& isset($_POST['adminAccountAccess'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si l'id du compte choisit est correct et que le select retourne bien un nombre
    if (ctype_digit($_POST['adminAccountId']))
    {
        //On vérifie si le type d'accès choisit est correct et qu'il retourne bien un nombre
        if (ctype_digit($_POST['adminAccountAccess']))
        {
            //On récupère l'Id du formulaire précédent
            $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

            //On fait une requête pour vérifier si le compte choisit existe
            $accountQuery = $bdd->prepare('SELECT * FROM car_accounts 
            WHERE accountId= ?');
            $accountQuery->execute([$adminAccountId]);
            $account = $accountQuery->rowCount();
            $accountQuery->closeCursor();

            //Si le compte est disponible
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
            //Si le compte n'est pas disponible
            else
            {
                echo "Erreur: Compte indisponible";
            }
        }
        //Si le type d'accès choisit n'est pas un nombre
        else
        {
            echo "Erreur: Accès invalide";
        }
    }
    //Si le compte choisit n'est pas un nombre
    else
    {
        echo "Erreur: Compte invalide";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");