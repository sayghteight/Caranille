<?php require_once("../../html/header.php");
 
//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si l'utilisateur à cliqué sur le bouton gérer l'échange
if (isset($_POST['tradeId'])
&& isset($_POST['manageTrade']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['tradeId'])
    && $_POST['tradeId'] >= 1)
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
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($trade = $tradeQuery->fetch())
            {
                //On récupère les valeurs de la demande d'échange
                $tradeId = stripslashes($trade['tradeId']);
                $tradeCharacterOneId = stripslashes($trade['tradeCharacterOneId']);
                $tradeCharacterTwoId = stripslashes($trade['tradeCharacterTwoId']);
                $tradeMessage = stripslashes($trade['tradeMessage']);
                $tradeLastUpdate = stripslashes($trade['tradeLastUpdate']);
            }
            
            //Si la première personne de l'échange est le joueur on cherche à savoir qui est l'autre personnage
            if ($tradeCharacterOneId == $characterId)
            {
                //On fait une requête pour vérifier la liste des conversations dans la base de données
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");

                $characterQuery->execute([$tradeCharacterTwoId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $tradeCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor(); 
            }
            //Si la seconde personne de l'échange est le joueur on cherche à savoir qui est l'autre personne
            else
            {
                //On fait une requête pour vérifier la liste des conversations dans la base de données
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");

                $characterQuery->execute([$tradeCharacterOneId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $tradeCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor();
            }
            
            echo "Echange entre $characterName et $tradeCharacterName (Dernière mise à jour $tradeLastUpdate)";
            
            /*
            Récupérer les objets et l'or de l'autre joueur
            
            Les afficher dans un select
            
            <hr>
            
            Récupérer les objets et l'or du joueur
            
            Les afficher dans un select
            */
            ?>
            
            <form method="POST" action="acceptTrade.php">
                <input type="submit" class="btn btn-default form-control" name="acceptTrade" value="Accepter l'échange">
            </form>
            
            <form method="POST" action="declineTrade.php">
                <input type="submit" class="btn btn-default form-control" name="declineTrade" value="Refuser l'échange">
            </form>
            
            <hr>
            
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
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
?>

<?php require_once("../../html/footer.php"); ?>