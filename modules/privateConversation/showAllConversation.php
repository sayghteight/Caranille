<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['privateConversationId'])
&& isset($_POST['showAllConversation']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['privateConversationId'])
    && $_POST['privateConversationId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $privateConversationId = htmlspecialchars(addslashes($_POST['privateConversationId']));
        
        //On vérifie si le joueur est bien dans cette conversation
        $privateConversationQuery = $bdd->prepare("SELECT * FROM car_private_conversation
        WHERE (privateConversationCharacterOneId = ?
        OR privateConversationCharacterTwoId = ?)
        AND privateConversationId = ?");
        $privateConversationQuery->execute([$characterId, $characterId, $privateConversationId]);
        $privateConversationRow = $privateConversationQuery->rowCount();

        //Si la conversation existe
        if ($privateConversationRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($privateConversation = $privateConversationQuery->fetch())
            {
                //On récupère les informations de la conversation
                $privateConversationId = stripslashes($privateConversation['privateConversationId']);
                $privateConversationCharacterOneId = stripslashes($privateConversation['privateConversationCharacterOneId']);
                $privateConversationCharacterTwoId = stripslashes($privateConversation['privateConversationCharacterTwoId']);
            }
            
            //Si la première personne de la conversation est le joueur on cherche à savoir qui est l'autre personnage
            if ($privateConversationCharacterOneId == $characterId)
            {
                //On fait une requête pour vérifier la liste des conversations dans la base de données
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");
                $characterQuery->execute([$privateConversationCharacterTwoId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $privateConversationCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor();
            }
            //Si la seconde personne de la conversation est le joueur on cherche à savoir qui est l'autre personne
            else
            {
                //On fait une requête pour vérifier la liste des conversations dans la base de données
                $characterQuery = $bdd->prepare("SELECT * FROM car_characters
                WHERE characterId = ?");
                $characterQuery->execute([$privateConversationCharacterOneId]);
                
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($character = $characterQuery->fetch())
                {
                    //On récupère les informations du personnage
                    $privateConversationCharacterName = stripslashes($character['characterName']);
                }
                $characterQuery->closeCursor();
            }
            ?>
            
            <p>Conversation avec <?php echo $privateConversationCharacterName ?> (Tous les messages)</p>

            <?php
            //On fait une recherche dans la base de donnée des 20 derniers message de la conversation
            $privateConversationMessageQuery = $bdd->prepare('SELECT * FROM car_private_conversation_message
            WHERE privateConversationMessagePrivateConversationId = ?');
            $privateConversationMessageQuery->execute([$privateConversationId]);
            $privateConversationMessageRow = $privateConversationMessageQuery->rowCount();
            
            //Si il y a déjà eu au moins un message
            if ($privateConversationMessageRow > 0)
            {
                ?>
                                    
                <table class="table">
    
                    <tr>
                        <td>
                            Date/Heure
                        </td>
                        
                        <td>
                            Pseudo
                        </td>
                        
                        <td>
                            Message
                        </td>
                    </tr>
                    
                    <?php
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($privateConversationMessage = $privateConversationMessageQuery->fetch())
                    {
                        //On récupère les informations du message de la discution
                        $privateConversationMessageCharacterId = stripslashes($privateConversationMessage['privateConversationMessageCharacterId']);
                        $privateConversationMessageDateTime = stripslashes($privateConversationMessage['privateConversationMessageDateTime']);
                        $privateConversationMessage = stripslashes($privateConversationMessage['privateConversationMessage']);
                        
                        //Si l'id de la personne qui a posté le message et celui du personnage sinon il s'agira de l'autre personnage
                        if ($privateConversationMessageCharacterId == $characterId)
                        {
                            $privateConversationCharacterName = $characterName;
                        }
                        ?>
                        
                        <tr>
                            
                            <td>
                                <?php echo strftime('%d-%m-%Y - %H:%M:%S',strtotime($privateConversationMessageDateTime)) ?> 
                            </td>
                            
                            <td>
                                <?php echo $privateConversationCharacterName ?>
                            </td>
                            
                            <td>
                                <?php echo $privateConversationMessage ?>
                            </td>
                            
                        </tr>
                        
                    <?php
                    }
                    ?>
                
                </table>
                
                <?php
            }
            $privateConversationMessageQuery->closeCursor();
            ?>
            
            <form method="POST" action="showConversation.php">
                <input type="hidden" name="privateConversationId" value="<?php echo $privateConversationId ?>">
                <input type="submit" class="btn btn-default form-control" name="showConversation" value="Retour">
            </form>
            
            <?php
        }
        //Si la conversation n'exite pas ou que le joueur n'y a pas accès
        else
        {
            echo "Erreur: Cette conversation n'existe pas ou vous n'en faite pas parti";
        }
        $privateConversationQuery->closeCursor();
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