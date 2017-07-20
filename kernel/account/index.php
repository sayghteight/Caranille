<?php
//On déclare les variables nécessaire pour la requête SQL
$accountId = $_SESSION['account']['id'];

$accountQuery = $bdd->prepare("SELECT * FROM car_accounts 
WHERE accountId = ?");
$accountQuery->execute([$accountId]);

//On fait une boucle pour récupérer les résultats
while ($account = $accountQuery->fetch())
{
    //On récupère les informations du compte
    $accountId = stripslashes($account['accountId']);
    $accountPseudo = stripslashes($account['accountPseudo']);
    $accountEmail = stripslashes($account['accountEmail']);
    $accountAccess = stripslashes($account['accountAccess']);
    $accountStatus = stripslashes($account['accountStatus']);
    $accountReason = stripslashes($account['accountReason']);
    $accountLastAction = stripslashes($account['accountLastAction']);  
    $accountLastConnection = stripslashes($account['accountLastConnection']);        
    $accountLastIp = stripslashes($account['accountLastIp']);
}
$accountQuery->closeCursor();

$accountLastAction = $date = date('Y-m-d H:i:s');

//On met à jour la dernière action du compte dans la base de donnée
$updateAccount = $bdd->prepare("UPDATE car_accounts SET 
accountLastAction = :accountLastAction
WHERE accountId = :accountId");
$updateAccount->execute(array(
'accountLastAction' => $accountLastAction,   
'accountId' => $accountId));
?>