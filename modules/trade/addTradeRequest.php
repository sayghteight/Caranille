<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['tradeCharacterId'])
&& isset($_POST['addTrade']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeCharacterId'])
    && $_POST['tradeCharacterId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $tradeCharacterId = htmlspecialchars(addslashes($_POST['tradeCharacterId']));
        
        //On fait une requête pour vérifier si le personnage choisit existe
        $characterQuery = $bdd->prepare('SELECT * FROM car_characters 
        WHERE characterId = ?');
        $characterQuery->execute([$tradeCharacterId]);
        $characterRow = $characterQuery->rowCount();

        //Si le compte existe
        if ($characterRow == 1) 
        {
            //On vérifie si il n'y a aucune demande ou échange avec ce joueur
            $tradeRequestQuery = $bdd->prepare("SELECT * FROM car_characters
            WHERE characterId = ?
            
            AND (SELECT COUNT(*) FROM car_trades_requests
            WHERE tradeRequestCharacterOneId = ?
            AND tradeRequestCharacterTwoId = characterId
            OR tradeRequestCharacterTwoId = characterId
            AND tradeRequestCharacterOneId = ?) > 0
            
            OR (SELECT COUNT(*) FROM car_trades
            WHERE tradeCharacterOneId = ?
            AND tradeCharacterTwoId = characterId
            OR tradeCharacterTwoId = characterId
            AND tradeCharacterOneId = ?) > 0");
            $tradeRequestQuery->execute([$tradeCharacterId, $characterId, $characterId, $characterId, $characterId]); 
            $tradeRequestRow = $tradeRequestQuery->rowCount();
            
            //Si il n'y a aucune demande ou échange avec ce joueur
            if ($tradeRequestRow == 0)
            {
                //On crée la demande d'échange
                $addTrade = $bdd->prepare("INSERT INTO car_trades_requests VALUES(
                '',
                :characterId,
                :tradeCharacterId,
                'None')");
                $addTrade->execute([
                'characterId' => $characterId,
                'tradeCharacterId' => $tradeCharacterId]);
                $addTrade->closeCursor();
                ?>
                
                La demande d'échange a bien été crée
                
                <form method="POST" action="index.php">
                    <input type="submit" name="edit" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
            }
            //Si il y a une demande ou échange avec ce joueur
            else
            {
                ?>
                
                Une demande d'échange existe déjà avec ce joueur
                
                <hr>
                
                <form method="POST" action="index.php">
                    <input type="submit" name="back" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
                
            }
            $tradeRequestQuery->closeCursor();
        }
        //Si le personnage n'existe pas
        else
        {
            echo "Erreur: Ce personnage n'existe pas";
        }
        $characterQuery->closeCursor(); 
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