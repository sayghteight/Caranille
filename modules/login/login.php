<?php 
require_once("../../html/header.php");

//Si tous les champs ont bien été rempli
if (isset($_POST['accountPseudo']) && ($_POST['accountPassword'])) 
{
    //Récupération des valeurs des deux champs dans une variable
    $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
    $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));

    //On fait une requête pour vérifier si le pseudo et le mot de passe concorde bien
    $verifyAccountLogin = $bdd->prepare("SELECT * FROM car_accounts 
    WHERE accountPseudo = ?
    AND accountPassword = ?");
    $verifyAccountLogin->execute([$accountPseudo, $accountPassword]);
    $Result = $verifyAccountLogin->rowCount();

    //Si il y a un résultat de trouvé c'est que la combinaison pseudo/mot de passe est bonne
    if ($Result == 1)
    {
        //Dans ce cas on boucle pour récupérer le tableau retourné par la base de donnée pour faire la session account
        while ($accountLogin = $verifyAccountLogin->fetch())
        {
            $_SESSION['account']['id'] = stripslashes($accountLogin['accountId']);
            header("Location: ../../index.php");
        }
    }
    //Si il n'y a aucun résultat de trouvé c'est que la combinaison pseudo/mot de passe est mauvaise
    else
    {
        echo "Mauvais Pseudo/Mot de passe";
    }
    $verifyAccountLogin->closeCursor();
}
//Si tous les champs n'ont pas été rempli
else
{
	echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>