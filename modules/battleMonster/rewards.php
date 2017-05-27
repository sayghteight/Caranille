<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un monstre on redirige le joueur vers le module dungeon
if ($foundBattleMonster == 0) { exit(header("Location: ../../modules/dungeon/index.php")); }


//Si le monstre a moins ou a zéro HP
if ($battleMonsterHpRemaining <= 0)
{
    //On prévient le joueur qu'il a remporté le combat
    echo "<p>$characterName remporte le combat !</p>";
    echo "Vous obtenez:<br />";
    echo "-$monsterExperience point(s) d'experience<br />";
    echo "-$monsterGold pièce(s) d'or<br />";

    //On donne les récompenses au personnage et on le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterExperience = characterExperience + :monsterExperience,
    characterExperienceTotal = characterExperienceTotal + :monsterExperience,
    characterGold = characterGold + :monsterGold
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'monsterExperience' => $monsterExperience,
    'monsterGold' => $monsterGold,
    'characterId' => $characterId]);

    //On détruit le combat en cours
    $DeleteBattle = $bdd->prepare("DELETE FROM car_battles_monsters 
    WHERE battleMonsterId = :battleMonsterId");
    $DeleteBattle->execute(array('battleMonsterId' => $battleMonsterId));
    ?>
    <form method="POST" action="../../modules/dungeon/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le joueur a moins ou a zéro HP
if ($characterHpMin <= 0)
{
    //On prévient le joueur qu'il a perdu
    echo "<p>$monsterName remporte le combat !</p>";
    
    //On soigne le personnage et ont le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = characterHpTotal,
    SET characterMpMin = characterMpTotal,
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);

    //On détruit le combat en cours
    $DeleteBattle = $bdd->prepare("DELETE FROM car_battles_monsters 
    WHERE battleMonsterId = :battleMonsterId");
    $DeleteBattle->execute(array('battleMonsterId' => $battleMonsterId));
    ?>
    <form method="POST" action="../../modules/dungeon/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le monstre et le joueur ont plus de zéro HP le joueur n'a pas a être ici
if ($battleMonsterHpRemaining > 0 && $characterHpMin > 0 )
{
    header("Location: index.php");
}
require_once("../../html/footer.php"); ?>