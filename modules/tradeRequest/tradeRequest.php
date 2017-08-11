<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une recherche dans la base de donnée de tous les personnage avec qui on a pas de demande d'échange ou d'échange en cours
$characterTradeQuery = $bdd->prepare("SELECT * FROM car_characters
WHERE characterId != ?

AND (SELECT COUNT(*) FROM car_trades_requests
WHERE tradeRequestCharacterOneId = ?
AND tradeRequestCharacterTwoId = characterId
OR tradeRequestCharacterTwoId = characterId
AND tradeRequestCharacterOneId = ?) = 0

AND (SELECT COUNT(*) FROM car_trades
WHERE tradeCharacterOneId = ?
AND tradeCharacterTwoId = characterId
OR tradeCharacterTwoId = characterId
AND tradeCharacterOneId = ?) = 0
ORDER by characterName");
$characterTradeQuery->execute([$characterId, $characterId, $characterId, $characterId, $characterId]);
$characterTradeRow = $characterTradeQuery->rowCount();

//S'il y a au moins un autre joueur on affiche le menu
if ($characterTradeRow > 0)
{
    ?>
    
    <form method="POST" action="tradeRequestEnd.php">
        Liste des joueurs <select name="tradeCharacterId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($characterTrade = $characterTradeQuery->fetch())
            {
                //On récupère les informations du personnage
                $tradeCharacterId = stripslashes($characterTrade['characterId']);
                $tradeCharacterName = stripslashes($characterTrade['characterName']);
                ?>
                <option value="<?php echo $tradeCharacterId ?>"><?php echo $tradeCharacterName ?></option>
                <?php
            }
            ?>
        
        </select>
        Message : <input type="text" name="tradeMessage" class="form-control" required>
        <input type="submit" name="addTrade" class="btn btn-default form-control" value="Envoyer la demande">
    </form>
    
    <?php
}
//Si il y a aucun autre joueur de disponible
else
{
    echo "Il n'y a actuellement aucun joueur de disponible pour faire un échange";
}
$characterTradeQuery->closeCursor();
?>

<hr>

<form method="POST" action="index.php">
    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
</form>

<?php require_once("../../html/footer.php"); ?>