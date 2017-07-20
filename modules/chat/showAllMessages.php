<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['showAllMessages']))
{
    //On fait une recherche dans la base de donnée des 20 derniers message du chat
    $chatQuery = $bdd->query("SELECT * FROM car_chat, car_characters 
    WHERE chatCharacterId = characterId");
    
    $chatRow = $chatQuery->rowCount();
    
    //Si il y a des messages dans le chat on les affiches
    if ($chatRow > 0)
    {
        ?>
        
        <p>Affichage de tous les messages</p>
        
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
                
                <?php
                //Si le joueur est modérateur ou administrateur on lui donne la possibilité de vider entièrement le chat
                if ($accountAccess >= 1)
                {
                    ?>
                    
                    <td>
                        Action
                    </td>
                    
                    <?php
                }
                ?>
                
            </tr>
            
            <?php
            //On fait une boucle pour récupérer toutes les information
            while ($chat = $chatQuery->fetch())
            {
                //On récupère les informations du chat
                $chatMessageId = stripslashes($chat['chatMessageId']);
                $chatCharacterName = stripslashes($chat['characterName']);
                $chatDateTime = stripslashes($chat['chatDateTime']);
                $chatMessage = stripslashes($chat['chatMessage']);
                ?>
                
                <tr>
                    <td>
                        <?php echo strftime('%d-%m-%Y - %H:%M:%S',strtotime($chatDateTime)) ?> 
                    </td>
                    
                    <td>
                        <?php echo $chatCharacterName ?> 
                    </td>
                    
                    <td>
                        <?php echo $chatMessage ?> 
                    </td>
                    
                    <?php
                    //Si le joueur est modérateur ou administrateur on lui donne la possibilité de supprimer le message
                    if ($accountAccess >= 1)
                    {
                        ?>
                        
                        <td>
                            <form method="POST" action="deleteMessage.php">
                                <input type="hidden" name="chatMessageId" value="<?php echo $chatMessageId ?>">
                                <input type="submit" class="btn btn-default form-control" name="deleteMessage" value="X">
                            </form>
                        </td>
                        
                        <?php
                    }
                    ?>
                    
                </tr>
                
            <?php
            }
            ?>
            
        </table>
        
        <?php
    }
    $chatQuery->closeCursor();
    ?>
    
    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="showAllMessage" value="Retour">
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>