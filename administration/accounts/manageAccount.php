<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton manage
if (isset($_POST['manage']))
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

        //Si le compte est disponible
        if ($account == 1) 
        {
            //On fait une recherche dans la base de donnée de tous les comptes
            $accountQuery = $bdd->prepare("SELECT * FROM car_accounts
            WHERE accountId = ?");
            $accountQuery->execute([$adminAccountId]);
            while ($account = $accountQuery->fetch())
            {
                $adminAccountPseudo = stripslashes($account['accountPseudo']);
            }
            $accountQuery->closeCursor();

            ?>
            Que souhaitez-vous faire du joueur <em><?php echo $adminAccountPseudo ?></em><br />

            <hr>
                
            <form method="POST" action="editAccount.php">
                <input type="hidden" class="btn btn-default form-control" name="adminAccountId" value="<?= $adminAccountId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier">
            </form>
            <form method="POST" action="deleteAccount.php">
                <input type="hidden" class="btn btn-default form-control" name="adminAccountId" value="<?= $adminAccountId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer">
            </form>

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
    //Si le compte choisit n'est pas un nombre
    else
    {
        echo "Erreur: Compte invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton manage
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");