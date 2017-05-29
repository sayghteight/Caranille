<?php require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//On fait une recherche de tous les comptes dans la base de donnée qui ont un Id différent du notre
$opponentQueryList = $bdd->prepare("SELECT * FROM car_characters 
WHERE characterId != ?
AND characterTownId = ?");
$opponentQueryList->execute([$characterId, $townId]);
$opponentQuery = $opponentQueryList->rowCount();

//Si plusieurs personnages ont été trouvé
if ($opponentQuery > 0)
{
    ?>
    <form method="POST" action="arena.php">
        <div class="form-group row">
            <label for="characterList" class="col-2 col-form-label">Liste des joueurs</label>
            <select class="form-control" id="townList" name="opponentCharacterId">
            <?php
            //On fait une boucle sur tous les résultats
            while ($opponent = $opponentQueryList->fetch())
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
        </div>
        <center><input type="submit" name="enter" class="btn btn-default form-control" value="Lancer le combat"></center>
    </form>
    <?php
}
//Si aucun personnage n'a été trouvé
else
{
    ?>
    Il n'y a aucun joueur à proximité, essayez de vous approcher d'un autre joueur.
    <?php
}
$opponentQueryList->closeCursor();

require_once("../../html/footer.php"); ?>
