<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si l'utilisateur à cliqué sur le bouton annuler l'échange
if (isset($_POST['tradeRequestId'])
&& isset($_POST['cancelTradeRequest']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeRequestId'])
    && $_POST['tradeRequestId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $tradeRequestId = htmlspecialchars(addslashes($_POST['tradeRequestId']));
        
        //On fait une requête pour vérifier si cette demande existe et est bien attribué au joueur
        $tradeRequestQuery = $bdd->prepare("SELECT * FROM car_trades_requests
        WHERE tradeRequestCharacterOneId = ?
        AND tradeRequestId = ?");
        $tradeRequestQuery->execute([$characterId, $tradeRequestId]);
        $tradeRequestRow = $tradeRequestQuery->rowCount();
        
        //Si cette demande existe et est attribuée au joueur
        if ($tradeRequestRow > 0) 
        {
            //On supprime la demande d'échange
            $tradeRequestDeleteQuery = $bdd->prepare("DELETE FROM car_trades_requests
            WHERE tradeRequestId = ?");
            $tradeRequestDeleteQuery->execute([$tradeRequestId]);
            $tradeRequestDeleteQuery->closeCursor();
            ?>
            
            Votre demande d'échange a bien été annulée
            
            <hr>
                
            <form method="POST" action="tradeRequestSent.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        $tradeRequestQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

//Si l'utilisateur à cliqué sur le bouton annuler accepter l'échange
if (isset($_POST['tradeRequestId'])
&& isset($_POST['cancelTradeRequest']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeRequestId'])
    && $_POST['tradeRequestId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $tradeRequestId = htmlspecialchars(addslashes($_POST['tradeRequestId']));
        
        //On fait une requête pour vérifier si cette demande existe et est bien attribué au joueur
        $tradeRequestQuery = $bdd->prepare("SELECT * FROM car_trades_requests
        WHERE tradeRequestCharacterTwoId = ?
        AND tradeRequestId = ?");
        $tradeRequestQuery->execute([$characterId, $tradeRequestId]);
        $tradeRequestRow = $tradeRequestQuery->rowCount();
        
        //Si cette demande existe et est attribuée au joueur
        if ($tradeRequestRow > 0) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($tradeRequest = $tradeRequestQuery->fetch())
            {
                //On récupère les valeurs de la demande d'échange
                $tradeRequestId = stripslashes($tradeRequest['tradeRequestId']);
                $tradeRequestCharacterOneId = stripslashes($tradeRequest['tradeRequestCharacterOneId']);
                $tradeRequestCharacterTwoId = stripslashes($tradeRequest['tradeRequestCharacterTwoId']);
                $tradeRequestMessage = stripslashes($tradeRequest['tradeRequestMessage']);
            }
            
            //On ajoute l'échange dans la base de donnée
            $addTrade = $bdd->prepare("INSERT INTO car_trades VALUES(
            '',
            :tradeRequestCharacterOneId,
            :tradeRequestCharacterTwoId,
            :tradeRequestMessage,
            'No',
            'No')");
            $addTrade->execute([
            'tradeRequestCharacterOneId' => $tradeRequestCharacterOneId,
            'tradeRequestCharacterTwoId' => $tradeRequestCharacterTwoId,
            'tradeRequestMessage' => $tradeRequestMessage]);
            $addTrade->closeCursor();
            ?>
            
            La demande d'échange a bien été acceptée
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

//Si l'utilisateur à cliqué sur le bouton refuser l'échange
if (isset($_POST['tradeRequestId'])
&& isset($_POST['cancelTradeRequest']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeRequestId'])
    && $_POST['tradeRequestId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $tradeRequestId = htmlspecialchars(addslashes($_POST['tradeRequestId']));
        
        //On fait une requête pour vérifier si cette demande existe et est bien attribué au joueur
        $tradeRequestQuery = $bdd->prepare("SELECT * FROM car_trades_requests
        WHERE tradeRequestCharacterTwoId = ?
        AND tradeRequestId = ?");
        $tradeRequestQuery->execute([$characterId, $tradeRequestId]);
        $tradeRequestRow = $tradeRequestQuery->rowCount();
        
        //Si cette demande existe et est attribuée au joueur
        if ($tradeRequestRow > 0) 
        {
            //On supprime la demande d'échange
            $tradeRequestDeleteQuery = $bdd->prepare("DELETE FROM car_trades_requests
            WHERE tradeRequestId = ?");
            $tradeRequestDeleteQuery->execute([$tradeRequestId]);
            $tradeRequestDeleteQuery->closeCursor();
            ?>
            
            La demande d'échange a bien été refusée
            
            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        $tradeRequestQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>