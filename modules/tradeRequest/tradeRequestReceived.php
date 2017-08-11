<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une requête pour vérifier toutes les demandes d'échange en cours
$tradeRequestQuery = $bdd->prepare("SELECT * FROM car_trades_requests
WHERE tradeRequestCharacterTwoId = ?");
$tradeRequestQuery->execute([$characterId]);
$tradeRequestRow = $tradeRequestQuery->rowCount();

//S'il existe un ou plusieurs demande d'échange en attente
if ($tradeRequestRow > 0) 
{
    ?>
    
    <form method="POST" action="manageTradeRequest.php">
        Demande d'échange reçues en attente : <select name="tradeRequestId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($tradeRequest = $tradeRequestQuery->fetch())
            {
                //On récupère les valeurs de la demande d'échange
                $tradeRequestId = stripslashes($tradeRequest['tradeRequestId']);
                $tradeRequestCharacterOneId = stripslashes($tradeRequest['tradeRequestCharacterOneId']);
                $tradeRequestCharacterTwoId = stripslashes($tradeRequest['tradeRequestCharacterTwoId']);
                $tradeRequestMessage = stripslashes($tradeRequest['tradeRequestMessage']);
                
                //On fait une requête pour récupérer le nom du personnage dans la base de donnée
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");
                $characterQuery->execute([$tradeRequestCharacterOneId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $tradeRequestCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor();
                ?>
                <option value="<?php echo $tradeRequestId ?>"><?php echo "$tradeRequestCharacterName ($tradeRequestMessage)" ?></option>
                <?php
            }
            ?>

        </select>
        <input type="submit" name="acceptTradeRequest" class="btn btn-default form-control" value="Accepter la demande">
        <input type="submit" name="declineTradeRequest" class="btn btn-default form-control" value="Refuser la demande">
    </form>

    <?php
}
//S'il n'y a aucune offre de disponible on prévient le joueur
else
{
    ?>
    
    Il n'y a aucune demande d'échange en attente.
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
    </form>
    
    <?php
}
$tradeRequestQuery->closeCursor();
?>

<?php require_once("../../html/footer.php"); ?>