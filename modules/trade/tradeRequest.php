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
    
    <form method="POST" action="viewTradeRequest.php">
        Liste des offres : <select name="tradeRequestId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($tradeRequest = $tradeRequestQuery->fetch())
            {
                //On récupère les valeurs de chaque objets
                $tradeRequestId = stripslashes($tradeRequest['tradeRequestId']);
                $tradeRequestCharacterOneId = stripslashes($tradeRequest['tradeRequestCharacterOneId']);
                $tradeRequestCharacterTwoId = stripslashes($tradeRequest['tradeRequestCharacterTwoId']);
                
                //Si la première personne de la demande d'échange est le joueur on cherche à savoir qui est l'autre personne
                if ($tradeRequestCharacterOneId == $characterId)
                {
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
                }
                //Si la seconde personne de la demande d'échange est le joueur on cherche à savoir qui est l'autre personne
                else
                {
                    //On fait une requête pour récupérer le nom du personnage dans la base de donnée
                    $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                    WHERE characterId = ?");

                    $characterQuery->execute([$tradeRequestCharacterTwoId]);
                    
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($character = $characterQuery->fetch())
                    {
                        //On récupère les informations du personnage
                        $tradeRequestCharacterName = stripslashes($character['characterName']);
                    }
                    $characterQuery->closeCursor();
                }

                ?>
                <option value="<?php echo $tradeRequestId ?>"><?php echo "Demande d'échange entre $characterName et $tradeRequestCharacterName" ?></option>
                <?php
            }
            ?>

        </select>
        <input type="submit" name="viewTradeRequest" class="btn btn-default form-control" value="Afficher la demande d'échange">
    </form>

    <?php
}
//S'il n'y a aucune offre de disponible on prévient le joueur
else
{
    echo "Il n'y a aucune demande d'échange en attente.";
}
$tradeRequestQuery->closeCursor();
?>

<?php require_once("../../html/footer.php"); ?>