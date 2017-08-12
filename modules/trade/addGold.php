<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['tradeId'])
&& isset($_POST['addGold']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeId'])
    && $_POST['tradeId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $tradeId = htmlspecialchars(addslashes($_POST['tradeId']));
        
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
            
            //On fait une requête pour récupérer le montant de l'argent que l'autre joueur à proposé
            $tradeGoldQuery = $bdd->prepare("SELECT * FROM car_trades_golds
            WHERE tradeGoldCharacterId = ?
            AND tradeGoldTradeId = ?");
            $tradeGoldQuery->execute([$characterId, $tradeId]);
            $tradeGoldRow = $tradeGoldQuery->rowCount();

            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($tradeGold = $tradeGoldQuery->fetch())
            {
                $tradeGoldQuantity = stripslashes($tradeGold['tradeGoldQuantity']);
            }
            ?>
            
            <form method="POST" action="addGoldEnd.php">
                Pièces d'or : <input type="number" name="tradeGold" class="form-control" placeholder="Pièces d'or" value="<?php echo $tradeGoldQuantity ?>" required>
                <input type="hidden" name="tradeId" value="<?php echo $tradeId ?>">
                <input type="submit" class="btn btn-default form-control" name="addGoldEnd" value="Modifier le nombre de pièces d'or">
            </form>
            
            <?php
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