<?php require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['accountPseudo']) 
&& isset($_POST['accountPassword'])
&& isset($_POST['token']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        $_SESSION['token'] = NULL;
        
        //Récupération des valeurs des deux champs dans une variable
        $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
        $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));
    
        //On fait une requête pour vérifier si le pseudo et le mot de passe concorde bien
        $accountQuery = $bdd->prepare("SELECT * FROM car_accounts 
        WHERE accountPseudo = ?
        AND accountPassword = ?");
        $accountQuery->execute([$accountPseudo, $accountPassword]);
        $accountRow = $accountQuery->rowCount();
    
        //S'il y a un résultat de trouvé c'est que la combinaison pseudo/mot de passe est bonne
        if ($accountRow == 1)
        {
            //Dans ce cas on boucle pour récupérer le tableau retourné par la base de donnée pour récupérer les informations du compte
            while ($account = $accountQuery->fetch())
            {
                //On récupère les informations du compte comme l'id et les accès (joueur, modérateur, administrateur)
                $accountId = stripslashes($account['accountId']);
                $accountAccess = stripslashes($account['accountAccess']);
                
                //Si le jeu est ouvert au public
                if ($gameAccess == "Opened")
                {
                    //On définit une date pour mettre à jour la dernière connexion du compte
                    $date = date('Y-m-d H:i:s');
                    
                    //On créer une session qui ne contiendra que l'id du compte
                    $_SESSION['account']['id'] = stripslashes($account['accountId']);
                    $accountId = $_SESSION['account']['id'];
                    
                    //On met la date de connexion à jour
                    $updateAccount = $bdd->prepare("UPDATE car_accounts SET 
                    accountLastConnection = :accountLastConnection
                    WHERE accountId = :accountId");
                    $updateAccount->execute(array(
                    'accountLastConnection' => $date,   
                    'accountId' => $accountId));
                    $updateAccount->closeCursor();
                    
                    header("Location: ../../index.php");
                }
                //Si le jeu est fermé au public
                else
                {
                    //Si le joueur est administrateur il peut se connecter
                    if ($accountAccess == 2)
                    {
                        //On définit une date pour mettre à jour la dernière connexion du compte
                        $date = date('Y-m-d H:i:s');
                        
                        //On créer une session qui ne contiendra que l'id du compte
                        $_SESSION['account']['id'] = stripslashes($account['accountId']);
                        $accountId = $_SESSION['account']['id'];
                        
                        //On met la date de connexion à jour
                        $updateAccount = $bdd->prepare("UPDATE car_accounts SET 
                        accountLastConnection = :accountLastConnection
                        WHERE accountId = :accountId");
                        $updateAccount->execute(array(
                        'accountLastConnection' => $date,   
                        'accountId' => $accountId));
                        $updateAccount->closeCursor();
                        
                        header("Location: ../../index.php");
                    }
                    //Si le joueur n'est pas administrateur on lui refuse l'accès
                    else
                    {
                        echo "Une maintenance est actuellement en cours, merci de réessayer plus tard.";
                    }
                }
            }
        }
        //S'il n'y a aucun résultat de trouvé c'est que la combinaison pseudo/mot de passe est mauvaise
        else
        {
            echo "Mauvais Pseudo/Mot de passe";
        }
        $accountQuery->closeCursor();
    }
    //Si le token de sécurité n'est pas correct
    else
    {
        echo "Erreur: Token invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
	echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>