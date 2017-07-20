<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['oldPassword']) 
&& isset($_POST['newPassword'])
&& isset($_POST['confirmNewPassword'])
&& isset($_POST['changePasswordEnd']))
{
    //On récupère les valeurs du formulaire dans une variable
    $oldPassword = sha1(htmlspecialchars(addslashes($_POST['oldPassword'])));
    $newPassword = sha1(htmlspecialchars(addslashes($_POST['newPassword'])));
    $confirmNewPassword = sha1(htmlspecialchars(addslashes($_POST['confirmNewPassword'])));

    //On vérifie si les deux mots de passes sont identiques
    if ($newPassword == $confirmNewPassword) 
    {
        //On fait une requête pour vérifier si l'ancien mot de passe est correct
        $accountQuery = $bdd->prepare("SELECT * FROM car_accounts 
        WHERE accountPseudo = ?
        AND accountPassword = ?");
        $accountQuery->execute([$accountPseudo, $oldPassword]);
        $accountRow = $accountQuery->rowCount();

        //S'il y a un résultat de trouvé c'est que la combinaison pseudo/mot de passe est bonne
        if ($accountRow == 1)
        {
            //On met à jour le mot de passe
            $updateAccount = $bdd->prepare('UPDATE car_accounts 
            SET accountPassword = :newPassword
            WHERE accountId = :accountId');
            $updateAccount->execute(array(
            'newPassword' => $newPassword,
            'accountId' => $accountId));
            $updateAccount->closeCursor();
            ?>
            
            Le mot de passe a bien été mit à jour
                 
            <hr>
        
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //S'il n'y a aucun résultat de trouvé c'est que la combinaison pseudo/mot de passe est mauvaise
        else
        {
            echo "Mauvais Pseudo/Mot de passe";
        }
    }
    //Si les deux mots de passe ne sont pas identique
    else 
    {
        ?>
        
        Les deux mots de passe ne sont pas identiques
        
        <hr>
        
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
        
        <?php
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Vous ne pouvez pas changer votre mot de passe";
}

require_once("../../html/footer.php"); ?>