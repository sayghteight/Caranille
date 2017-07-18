<?php
require_once("../../kernel/config.php");

//On fait une requête pour savoir combien il y a actuellement d'offre sur le market
$marketOfferQuantityQuery = $bdd->query("SELECT * FROM car_market");
$marketOfferQuantityRow = $marketOfferQuantityQuery->rowCount();
$marketOfferQuantityQuery->closeCursor();
?>