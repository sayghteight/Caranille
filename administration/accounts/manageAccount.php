<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminAccountId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminAccountId'])
    && $_POST['adminAccountId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

        //On fait une requête pour vérifier si le compte choisi existe
        $accountQuery = $bdd->prepare('SELECT * FROM car_accounts 
        WHERE accountId= ?');
        $accountQuery->execute([$adminAccountId]);
        $account = $accountQuery->rowCount();

        //Si le compte existe
        if ($account == 1) 
        {
            //On récupère le pseudo du compte
            while ($account = $accountQuery->fetch())
            {
                $adminAccountPseudo = stripslashes($account['accountPseudo']);
            }
            ?>
            
            Que souhaitez-vous faire du compte <em><?php echo $adminAccountPseudo ?></em> ?

            <hr>
                
            <form method="POST" action="editAccount.php">
                <input type="hidden" class="btn btn-default form-control" name="adminAccountId" value="<?= $adminAccountId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le compte">
            </form>
            <form method="POST" action="deleteAccount.php">
                <input type="hidden" class="btn btn-default form-control" name="adminAccountId" value="<?= $adminAccountId ?>">
                <input type="submit" class="btn btn-default form-control" name="delete" value="Supprimer le compte">
            </form>

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