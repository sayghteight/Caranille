<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['tradeId'])
&& isset($_POST['tradeGold'])
&& isset($_POST['addGoldEnd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeId'])
    && ctype_digit($_POST['tradeGold'])
    && $_POST['tradeId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $tradeId = htmlspecialchars(addslashes($_POST['tradeId']));
        $tradeGold = htmlspecialchars(addslashes($_POST['tradeGold']));
        
        //On fait une requête pour vérifier si cette demande existe et est bien attribué au joueur
        $tradeQuery = $bdd->prepare("SELECT * FROM car_trades
        WHERE (tradeCharacterOneId = ?
        OR tradeCharacterTwoId = ?)
        AND tradeId = ?");
        $tradeQuery->execute([$characterId, $characterId, $tradeId]);
        $tradeRow = $tradeQuery->rowCount();
        
        //Si cette échange existe et est attribuée au joueur
        if ($tradeRow > 0) 
        {
            if ($characterGold >= $tradeGold)
            {
                //On met l'argent de l'échange à jour
                $updateGoldTrade = $bdd->prepare("UPDATE car_trades_golds SET
                tradeGoldQuantity = :tradeGold
                WHERE tradeGoldTradeId = :tradeId
                AND tradeGoldCharacterId = :characterId");
                $updateGoldTrade->execute(array(
                'tradeGold' => $tradeGold,
                'tradeId' => $tradeId,
                'characterId' => $characterId));
                $updateGoldTrade->closeCursor();
                
                //On met l'échange à jour
                $updateTrade = $bdd->prepare("UPDATE car_trades SET
                tradeCharacterOneTradeAccepted = 'No',
                tradeCharacterTwoTradeAccepted = 'No'
                WHERE tradeId = :tradeId");
                $updateTrade->execute(array(
                'tradeId' => $tradeId));
                $updateTrade->closeCursor(); 
                ?>
                
                Vous avez mit <?php echo $tradeGold ?> pièce(s) d'or dans l'échange
                
                <hr>
            
                <form method="POST" action="index.php">
                    <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                </form>
                
                <?php
            }
            else
            {
                echo "vous n'avez pas assez d'argent";
            }
        }
        //Si la demande d'échange n'existe pas ou n'est pas attribué au joueur
        else
        {
            echo "Erreur: Cette demande d'échange n'existe pas où ne vous est pas attribuée";
        }
        $tradeQuery->closeCursor(); 
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>