<?php require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['accountPseudo']) 
&& isset($_POST['accountPassword']))
{
    //Récupération des valeurs des deux champs dans une variable
    $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
    $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));

    //On fait une requête pour vérifier si le pseudo et le mot de passe concorde bien
    $accountQuery = $bdd->prepare("SELECT * FROM car_accounts 
    WHERE accountPseudo = ?
    AND accountPassword = ?");
    $accountQuery->execute([$accountPseudo, $accountPassword]);
    $accountRow = $accountQuery->rowCount();

    //Si il y a un résultat de trouvé c'est que la combinaison pseudo/mot de passe est bonne
    if ($accountRow == 1)
    {
        //Dans ce cas on boucle pour récupérer le tableau retourné par la base de donnée pour faire la session account
        while ($account = $accountQuery->fetch())
        {
            $_SESSION['account']['id'] = stripslashes($account['accountId']);
            header("Location: ../../index.php");
        }
        $accountQuery->closeCursor();
    }
    //Si il n'y a aucun résultat de trouvé c'est que la combinaison pseudo/mot de passe est mauvaise
    else
    {
        echo "Mauvais Pseudo/Mot de passe";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
	echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>