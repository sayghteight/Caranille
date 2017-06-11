<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a pas de combat contre un monstre on redirige le joueur vers le module dungeon
if ($battleMonsterRow == 0) { exit(header("Location: ../../modules/battleArena/index.php")); }

//Si le monstre et le joueur on 0 HP
if ($battleMonsterHpRemaining <= 0 && $characterHpMin <= 0)
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
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles_monsters 
    WHERE battleMonsterId = :battleMonsterId");
    $deleteBattle->execute(array('battleMonsterId' => $battleMonsterId));
    $deleteBattle->closeCursor();
    ?>

    <hr>

    <form method="POST" action="../../modules/dungeon/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le monstre a moins ou a zéro HP et que le joueur à plus de 0 hp
if ($battleMonsterHpRemaining <= 0 && $characterHpMin > 0)
{
    //On calcule l'expérience bonus que le joueur va recevoir en fonction de sa sagesse
    $experienceBonus = $monsterExperience * $characterWisdomTotal;
    $monsterExperience = $monsterExperience + $experienceBonus;

    //On prévient le joueur qu'il a remporté le combat
    echo "<p>$characterName remporte le combat !</p>";
    echo "Vous obtenez:<br />";
    echo "-$monsterExperience point(s) d'experience<br />";
    echo "-$monsterGold pièce(s) d'or<br />";

    //On recherche dans la base de donnée les objets que ce monstre peut faire gagner
    $monsterDropQuery = $bdd->prepare("SELECT * FROM car_monsters, car_items, car_monsters_drops
    WHERE monsterDropMonsterID = monsterId
    AND monsterDropItemID = itemId
    AND monsterDropMonsterID = ?");
    $monsterDropQuery->execute([$battleMonsterId]);
    $monsterDropRow = $monsterDropQuery->rowCount();

    //Si il existe un ou plusieurs objet pour ce monstre
    if ($monsterDropRow > 0) 
    {
        //On va voir pour chaque objet si le joueur obtient ou non
        while ($monsterDrop = $monsterDropQuery->fetch())
        {
            $monsterDropItemId = stripslashes($monsterDrop['itemId']);
            $monsterDropItemName = stripslashes($monsterDrop['itemName']);
            $monsterDropLuck = stripslashes($monsterDrop['monsterDropLuck']);

            //On génère un nombre entre 0 et 1001 (Pour que 1000 puisse aussi être choisit)
            $numberRandom = mt_rand(0, 1001);

            //Si le nombre obtenu est inférieur ou égal à l'objet il l'obtient
            if ($numberRandom <= $monsterDropLuck)
            {
                ?>
                -Objet: <?php echo "$monsterDropItemName<br />" ?>
                <?php
            }
        }
        $monsterDropQuery->closeCursor();
    }

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
    $updateCharacter->closeCursor();

    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles_monsters 
    WHERE battleMonsterId = :battleMonsterId");
    $deleteBattle->execute(array('battleMonsterId' => $battleMonsterId));
    $deleteBattle->closeCursor();
    ?>

    <hr>
    
    <form method="POST" action="../../modules/dungeon/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le joueur a moins ou a zéro HP et que le monstre à plus de 0 HP
if ($characterHpMin <= 0 && $battleMonsterHpRemaining > 0)
{
    //On prévient le joueur qu'il a perdu
    echo "<p>$monsterName remporte le combat !</p>";
    
    //On soigne le personnage et ont le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = characterHpTotal,
    characterMpMin = characterMpTotal
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);

    //On détruit le combat en cours
    $DeleteBattle = $bdd->prepare("DELETE FROM car_battles_monsters 
    WHERE battleMonsterId = :battleMonsterId");
    $DeleteBattle->execute(array('battleMonsterId' => $battleMonsterId));
    ?>

    <hr>

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