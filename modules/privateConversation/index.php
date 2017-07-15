<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une requête pour vérifier la liste des conversations dans la base de données
$privateConversationQuery = $bdd->prepare("SELECT * FROM car_private_conversation
WHERE (privateConversationCharacterOneId = ?
OR privateConversationCharacterTwoId = ?)");
$privateConversationQuery->execute([$characterId, $characterId]);
$privateConversationRow = $privateConversationQuery->rowCount();

//S'il existe une ou plusieurs conversation dans la messagerie privé
if ($privateConversationRow > 0) 
{
    ?>

    <form method="POST" action="showConversation.php">
        Liste de vos conversations : <select name="privateConversationId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($privateConversation = $privateConversationQuery->fetch())
            {
                //On récupère les informations de la conversation
                $privateConversationId = stripslashes($privateConversation['privateConversationId']);
                $privateConversationCharacterOneId = stripslashes($privateConversation['privateConversationCharacterOneId']);
                $privateConversationCharacterTwoId = stripslashes($privateConversation['privateConversationCharacterTwoId']);
                
                //Si la première personne de la conversation est le joueur on cherche à savoir qui est l'autre personne
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
                }
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
                }
                ?>
                <option value="<?php echo $privateConversationId ?>"><?php echo $privateConversationCharacterName ?></option>
                <?php
            }
            ?>
            
        </select>
        <input type="submit" name="showConversation" class="btn btn-default form-control" value="Afficher la conversation">
    </form>
    
    <hr>

    <?php
}
$privateConversationQuery->closeCursor();

//On fait une recherche dans la base de donnée de tous les comptes et personnages
$characterQuery = $bdd->prepare("SELECT * FROM car_characters
WHERE characterId != ?
ORDER by characterName");
$characterQuery->execute([$characterId]);
$characterRow = $characterQuery->rowCount();

//Si il y a au moins un autre joueur on affiche le menu

if ($characterRow > 0)
{
    
    ?>
    
    <form method="POST" action="launchConversation.php">
        Liste des joueurs <select name="privateConversationCharacterId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($character = $characterQuery->fetch())
            {
                $privateConversationCharacterId = stripslashes($character['characterId']);
                $privateConversationCharacterName = stripslashes($character['characterName']);
                ?>
                <option value="<?php echo $privateConversationCharacterId ?>"><?php echo "$privateConversationCharacterName"; ?></option>
                <?php
            }
            $characterQuery->closeCursor();
            ?>
        
        </select>
        <input type="submit" name="launchConversation" class="btn btn-default form-control" value="Démarrer une conversation">
    </form>
    
    <?php
}
else
{
    echo "Il n'y a actuellement aucun autre joueur";
}
?>

<hr>

<form method="POST" action="index.php">
    <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
</form>

<?php

require_once("../../html/footer.php"); ?>