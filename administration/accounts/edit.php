<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton edit
if (isset($_POST['edit']))
{
    $adminAccountId = htmlspecialchars(addslashes($_POST['adminAccountId']));

    //On fait une recherche dans la base de donnée de tous les comptes
    $accountListQuery = $bdd->prepare("SELECT * FROM car_accounts
    WHERE accountId = ?");
    $accountListQuery->execute([$adminAccountId]);
    while ($accountList = $accountListQuery->fetch())
    {
        $adminAccountId = stripslashes($accountList['accountId']);
        $adminAccountPseudo = stripslashes($accountList['accountPseudo']);
        $adminAccountEmail = stripslashes($accountList['accountEmail']);
        $adminAccountAccess = stripslashes($accountList['accountAccess']);
    }
    $accountListQuery->closeCursor();
    ?>

    <form method="POST" action="finalEdit.php">
        Pseudo : <br> <input type="text" name="adminAccountPseudo" class="form-control" placeholder="Pseudo" value="<?php echo $adminAccountPseudo; ?>" required autofocus><br /><br />
        Email : <br> <input type="mail" name="adminAccountEmail" class="form-control" placeholder="Email" value="<?php echo $adminAccountEmail; ?>" required><br /><br />
        Accès<br> <select name="adminAccountAccess" class="form-control">
            
        <?php
        switch ($accountAccess)
        {
            case 0:
            ?>
            <option selected="selected" value="0">Joueur</option>
            <option value="1">Modérateur</option>
            <option value="2">Administrateur</option>
            <?php
            break;

            case 1:
            ?>
            <option selected="selected" value="1">Modérateur</option>
            <option value="0">Joueur</option>
            <option value="2">Administrateur</option>
            <?php
            break;

            case 2:
            ?>
            <option selected="selected" value="2">Administrateur</option>
            <option value="0">Joueur</option>
            <option value="1">Modérateur</option>";
            <?php
            break;
        }
        ?>
        </select><br /><br />
        <input type="hidden" name="adminAccountId" value="<?= $adminAccountId ?>">
        <input name="finalEdit" class="btn btn-default form-control" type="submit" value="Modifier">
    </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton edit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");