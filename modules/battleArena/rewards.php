<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un personnage on redirige le joueur vers le module arena
if ($battleArenaRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

//Si le joueur adverse et le joueur on 0 HP
if ($battleArenaOpponentCharacterHpRemaining <= 0 && $characterHpMin <= 0)
{
    //On prévient le joueur qu'il y a un match nul
    echo "<p>Match Nul !</p>";
    
    //On soigne le personnage et ont le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = characterHpTotal,
    characterMpMin = characterMpTotal
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);
    $updateCharacter->closeCursor();

    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles_arenas 
    WHERE battleArenaId = :battleArenaId");
    $deleteBattle->execute(array('battleArenaId' => $battleArenaId));
    $deleteBattle->closeCursor();
    ?>
    
    <hr>

    <form method="POST" action="../../modules/arena/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le personnage adverse a moins ou a zéro HP
if ($battleArenaOpponentCharacterHpRemaining <= 0)
{
    //On prévient le joueur qu'il a remporté le combat
    echo "<p>$characterName remporte le combat !</p>";
    echo "Vous obtenez:<br />";
    echo "-1 point de victoire<br />";

    //On donne les récompenses au personnage et on le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterVictory = characterVictory + 1
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);
    $updateCharacter->closeCursor();

    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles_arenas 
    WHERE battleArenaId = :battleArenaId");
    $deleteBattle->execute(array('battleArenaId' => $battleArenaId));
    $deleteBattle->closeCursor();
    ?>
    
    <hr>

    <form method="POST" action="../../modules/arena/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le joueur a moins ou a zéro HP
if ($characterHpMin <= 0)
{
    //On prévient le joueur qu'il a perdu
    echo "<p>$opponentCharacterName remporte le combat !</p>";
    
    //On soigne le personnage et ont le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = characterHpTotal,
    characterMpMin = characterMpTotal,
    characterDefeate = characterDefeate + 1
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);

    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles_arenas 
    WHERE battleArenaId = :battleArenaId");
    $deleteBattle->execute(array('battleArenaId' => $battleArenaId));
    ?>
    
    <hr>

    <form method="POST" action="../../modules/arena/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le personnage adversaire et le joueur ont plus de zéro HP le joueur n'a pas a être ici
if ($battleArenaOpponentCharacterHpRemaining > 0 && $characterHpMin > 0 )
{
    header("Location: index.php");
}
require_once("../../html/footer.php"); ?>