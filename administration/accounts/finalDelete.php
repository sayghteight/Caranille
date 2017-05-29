<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalDelete
if (isset($_POST['finalDelete']))
{
    $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

    //On fait une recherche dans la base de donnée de tous les comptes
    $accountDeleteQuery = $bdd->prepare("DELETE FROM car_accounts
    WHERE accountId = ?");
    $accountDeleteQuery->execute([$adminAccountId]);
    $accountDeleteQuery->closeCursor();
    ?>

    Le compte a bien été supprimé

    <hr>
        
    <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton finalDelete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");