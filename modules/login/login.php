<?php 
require_once("../../html/header.php");

//Si tous les champs ont bien été rempli
if (isset($_POST['accountPseudo']) && ($_POST['accountPassword'])) 
{
    //Récupération des valeurs des deux champs dans une variable
    $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
    $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));

    //On fait une requête pour vérifier si le pseudo et le mot de passe concorde bien
    $verifyaccountLogin = $bdd->prepare("SELECT * FROM car_accounts 
    WHERE accountPseudo = ?
    AND accountPassword = ?");
    $verifyaccountLogin->execute([$accountPseudo, $accountPassword]);
    $Result = $verifyaccountLogin->rowCount();

    //Si il y a un résultat de trouvé c'est que la combinaison pseudo/mot de passe est bonne
    if ($Result == 1)
    {
        //Dans ce cas on boucle pour récupérer le tableau retourné par la base de donnée pour faire la session account
        while ($accountLogin = $verifyaccountLogin->fetch())
        {
            $_SESSION['account']['id'] = stripslashes($accountLogin['accountId']);
            header("Location: $url/modules/main/index.php");
        }
    }
    //Si il n'y a aucun résultat de trouvé c'est que la combinaison pseudo/mot de passe est mauvaise
    else
    {
        echo "Mauvais Pseudo/Mot de passe";
    }
    //On ferme le flux de sortie de la base de donnée
    $verifyaccountLogin->closeCursor();
}
//Si tous les champs n'ont pas été rempli
else
{
	echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>