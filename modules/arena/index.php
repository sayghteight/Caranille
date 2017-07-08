<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On fait une recherche de tous les joueurs dans la base de donnée qui ont un Id différent du notre et qui sont dans la même ville
$opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
WHERE characterId != ?
AND characterTownId = ?");
$opponentQuery->execute([$characterId, $townId]);
$opponentRow = $opponentQuery->rowCount();

//Si un ou plusieurs personnages ont été trouvé
if ($opponentRow > 0)
{
    ?>
    
    <form method="POST" action="selectedOpponent.php">
        Liste des joueurs : <select name="opponentCharacterId" class="form-control">
                
            <?php
            //On fait une boucle sur tous les résultats
            while ($opponent = $opponentQuery->fetch())
            {
                //on récupère les valeurs de chaque joueurs qu'on va ensuite mettre dans le menu déroulant
                $characterId = stripslashes($opponent['characterId']); 
                $characterName = stripslashes($opponent['characterName']);
                ?>

                    <option value="<?php echo $characterId ?>"><?php echo $characterName ?></option>
                    
                <?php
            }
            ?>
            
        </select>
        <input type="submit" name="enter" class="btn btn-default form-control" value="Lancer le combat">
    </form>
    
    <?php
}
//Si aucun joueur n'a été trouvé
else
{
    ?>
    
    Il n'y a aucun autre joueur.
    
    <?php
}
$opponentQuery->closeCursor();

require_once("../../html/footer.php"); ?>
