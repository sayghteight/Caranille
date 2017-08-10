<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

Que souhaitez-vous faire ?<br />

<hr>

<form method="POST" action="tradeRequestSent.php">
    <input type="submit" class="btn btn-default form-control" name="viewTrade" value="Demandes d'échange envoyée">
</form>

<form method="POST" action="tradeRequestReceived.php">
    <input type="submit" class="btn btn-default form-control" name="viewTrade" value="Demandes d'échange reçue">
</form>

<form method="POST" action="trades.php">
    <input type="submit" class="btn btn-default form-control" name="manage" value="Echange en cours">
</form>

<form method="POST" action="newTradeRequest.php">
    <input type="submit" class="btn btn-default form-control" name="newTrade" value="Nouvel échange">
</form>

<?php require_once("../../html/footer.php"); ?>