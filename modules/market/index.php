<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une requête pour récupérer tous les objets du jeu qui ont une ou plusieurs offre en cours
$marketQuery = $bdd->query("SELECT * FROM car_items
WHERE (SELECT COUNT(*) FROM car_market
WHERE marketItemId = itemId) > 0
ORDER BY itemName");

$marketRow = $marketQuery->rowCount();

//Si il y a au moins une offre de disponible
if ($marketRow > 0)
{
    ?>
    <form method="POST" action="viewAllOffers.php">
        Liste des offres : <select name="itemId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($market = $marketQuery->fetch())
            {
                //On récupère les valeurs de chaque objets
                $itemId = stripslashes($market['itemId']);

                //On fait un requête pour savoir combien d'offres il y a sur cet objet
                $marketItemQuantityQuery = $bdd->prepare("SELECT * FROM car_market
                WHERE marketItemId = ?");
                $marketItemQuantityQuery->execute([$itemId]);
                $marketItemQuantityRow = $marketItemQuantityQuery->rowCount();

                //Si plusieurs offre ont été trouvée
                if ($marketItemQuantityRow > 0)
                {
                    //On récupère les informations de l'objet
                    $itemId = stripslashes($market['itemId']);
                    $itemName = stripslashes($market['itemName']);
                    ?>
                    <option value="<?php echo $itemId ?>"><?php echo "$itemName ($marketItemQuantityRow offres)" ?></option>
                    <?php
                }
                $marketItemQuantityQuery->closeCursor(); 
            }
            ?>

        </select>
        <input type="submit" name="viewAllOffers" class="btn btn-default form-control" value="Afficher les offres">
    </form>

    <?php
}
//S'il n'y a aucune offre de disponible on prévient le joueur
else
{
    echo "Il n'y a aucune offre de disponible.";
}
$marketQuery->closeCursor();
?>

<hr>

<form method="POST" action="newOffer.php">
    <input type="submit" class="btn btn-default form-control" name="newOffer" value="Nouvelle offre">
</form>

<hr>

<form method="POST" action="../../index.php">
    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
</form>

<?php require_once("../../html/footer.php"); ?>