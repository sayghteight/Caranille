<?php
//On déclare les variables nécessaire pour la requête SQL
$accountId = $_SESSION['account']['id'];

$accountQuery = $bdd->prepare("SELECT * FROM car_accounts 
WHERE accountId = ?");
$accountQuery->execute([$accountId]);

//On fait une boucle pour récupérer les résultats
while ($account = $accountQuery->fetch())
{
    $accountId = stripslashes($account['accountId']);
    $accountPseudo = stripslashes($account['accountPseudo']);
    $accountEmail = stripslashes($account['accountEmail']);
    $accountAccess = stripslashes($account['accountAccess']);
    $accountStatus = stripslashes($account['accountStatus']);
    $accountReason = stripslashes($account['accountReason']);
    $accountLastConnection = stripslashes($account['accountLastConnection']);        
    $accountLastIp = stripslashes($account['accountLastIp']);
}
?>