<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['privateConversationCharacterId'])
&& isset($_POST['launchConversation']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['privateConversationCharacterId'])
    && $_POST['privateConversationCharacterId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $privateConversationCharacterId = htmlspecialchars(addslashes($_POST['privateConversationCharacterId']));
        
        //On fait une requête pour vérifier si le personnage choisit existe
        $characterQuery = $bdd->prepare('SELECT * FROM car_characters 
        WHERE characterId = ?');
        $characterQuery->execute([$privateConversationCharacterId]);
        $characterRow = $characterQuery->rowCount();

        //Si le compte existe
        if ($characterRow == 1) 
        {
            //On vérifie si il n'y a pas déjà une conversation avec ce joueur
            
            //On vérifie en prenant en compte que nous somme le conversationCharacterOneId
            $privateConversationOneQuery = $bdd->prepare("SELECT * FROM car_private_conversation
            WHERE privateConversationCharacterOneId = ?
            AND privateConversationCharacterTwoId = ?");
            $privateConversationOneQuery->execute([$characterId, $privateConversationCharacterId]);
            $privateConversationOneRow = $privateConversationOneQuery->rowCount();
            
            //On vérifie en prenant en compte que nous somme le conversationCharacterTwoId
            $privateConversationTwoQuery = $bdd->prepare("SELECT * FROM car_private_conversation
            WHERE privateConversationCharacterOneId = ?
            AND privateConversationCharacterTwoId = ?");
            $privateConversationTwoQuery->execute([$privateConversationCharacterId, $characterId]);
            $privateConversationTwoRow = $privateConversationTwoQuery->rowCount();
            
            //Si aucune conversation n'existe
            if ($privateConversationOneRow == 0 && $privateConversationTwoRow == 0)
            {
                //On crée la conversation
                $addPrivateConversation = $bdd->prepare("INSERT INTO car_private_conversation VALUES(
                '',
                :characterId,
                :conversationCharacterTwoId)");
        
                $addPrivateConversation->execute([
                'characterId' => $characterId,
                'conversationCharacterTwoId' => $privateConversationCharacterId]);
                $addPrivateConversation->closeCursor();
                ?>
                
                La conversation a bien été crée
                
                <form method="POST" action="index.php">
                    <input type="submit" name="edit" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
            }
            //Si une conversation exixte déjà avec ce joueur
            else
            {
                ?>
                
                Une conversation existe déjà avec ce joueur
                
                <hr>
                
                <form method="POST" action="index.php">
                    <input type="submit" name="back" class="btn btn-default form-control" value="Retour">
                </form>
                
                <?php
                
            }
        }
        //Si le personnage n'existe pas
        else
        {
            echo "Erreur: Ce personnage n'existe pas";
        }
        $accountQuery->closeCursor();
        
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