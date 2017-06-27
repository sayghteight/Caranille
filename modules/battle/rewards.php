<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow == 0) { exit(header("Location: ../../modules/main/index.php")); }

//Si le monstre et le joueur on 0 HP
if ($battleOpponentHpRemaining <= 0 && $characterHpMin <= 0)
{
    //On prévient le joueur qu'il y a un match nul
    ?>
    <p>Match Nul !</p>";
    <?php
    
    //On soigne le personnage et ont le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = characterHpTotal,
    characterMpMin = characterMpTotal
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);
    $updateCharacter->closeCursor();

    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles 
    WHERE battleId = :battleId");
    $deleteBattle->execute(array('battleId' => $battleId));
    ?>

    <hr>

    <form method="POST" action="../../modules/town/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le monstre a moins ou a zéro HP et que le joueur à plus de 0 hp
if ($battleOpponentHpRemaining <= 0 && $characterHpMin > 0)
{
    //On calcule l'expérience bonus que le joueur va recevoir en fonction de sa sagesse
    $experienceBonus = $monsterExperience * $characterWisdomTotal;
    $monsterExperience = $monsterExperience + $experienceBonus;

    //On prévient le joueur qu'il a remporté le combat
    ?>
    <p><?php echo $characterName; ?> remporte le combat !</p>
    Vous obtenez:<br />
    
    <?php
    //S'il s'agit d'un combat de Donjon, de mission
    if ($battleType == "Dungeon"
    || $battleType == "Mission"
    || $battleType == "Story")
    {
        ?>
        -<?php echo $opponentExperience; ?> point(s) d'experience<br />
        -<?php echo $opponentGold; ?> pièce(s) d'or<br />
        <?php
    
        //On recherche dans la base de donnée les objets que ce monstre peut faire gagner
        $opponentDropQuery = $bdd->prepare("SELECT * FROM car_monsters, car_items, car_monsters_drops
        WHERE monsterDropMonsterID = monsterId
        AND monsterDropItemID = itemId
        AND monsterDropMonsterID = ?");
        $opponentDropQuery->execute([$opponentId]);
        $opponentDropRow = $opponentDropQuery->rowCount();
    
        //Si il existe un ou plusieurs objet pour ce monstre
        if ($opponentDropRow > 0) 
        {
            //On va voir pour chaque objet si le joueur l'obtient ou non
            while ($opponentDrop = $opponentDropQuery->fetch())
            {
                $opponentDropItemId = stripslashes($opponentDrop['itemId']);
                $opponentDropItemName = stripslashes($opponentDrop['itemName']);
                $opponentDropLuck = stripslashes($opponentDrop['monsterDropLuck']);
    
                //On génère un nombre entre 0 et 1001 (Pour que 1000 puisse aussi être choisit)
                $numberRandom = mt_rand(0, 1001);
                
                //Si le nombre obtenu est inférieur ou égal à l'objet il l'obtient
                if ($numberRandom <= $opponentDropLuck)
                {
                    $itemQuery = $bdd->prepare("SELECT * FROM car_inventory 
                    WHERE inventoryItemId = ?");
                    $itemQuery->execute([$opponentId]);
                    $itemRow = $itemQuery->rowCount();
    
                    //Si l'objet a été trouvé dans l'inventaire du joueur on ajoute +1 à cet objet
                    if ($itemRow > 0)
                    {
                        //On met à jour l'objet dans la base de donnée
                        $updateItems = $bdd->prepare('UPDATE car_inventory 
                        SET inventoryQuantity = inventoryQuantity + 1
                        WHERE inventoryItemId = :monsterDropItemId');
    
                        $updateItems->execute(['monsterDropItemId' => $opponentId]);
                        $updateItems->closeCursor();
                    }
                    //Si l'objet n'a pas été trouvé dans l'inventaire du joueur on l'ajoute
                    else
                    {
                        //On ajoute l'objet dans la base de donnée
                        $addItem = $bdd->prepare("INSERT INTO car_inventory VALUES(
                        '',
                        :characterId,
                        :monsterDropItemId,
                        '1',
                        'no')");
    
                        $addItem->execute([
                        'characterId' => $characterId,
                        'monsterDropItemId' => $opponentId]);
                        $addItem->closeCursor();
                    }
                    $itemQuery->closeCursor();
                    ?>
                    -1 <?php echo "$opponentDropItemName<br />" ?>
                    <?php
                }
            }
        }
        $opponentDropQuery->closeCursor();
        
        //S'il s'agit d'un combat d'histoire
        if ($battleType == "Story")
        {
            ?>
            
            <hr>
        
            <?php
            //On récupère les informations du chapitre en cours
            $chapterQuery = $bdd->prepare("SELECT * FROM car_chapters
            WHERE chapterId = ?");
            $chapterQuery->execute([$characterChapter]);
            $chapterRow = $chapterQuery->rowCount();
            
            //Si le chapitre du joueur est disponible
            if ($chapterRow == 1)
            {
                //On récupère la fin du chapitre pour l'afficher au joueur
            	while ($chapter = $chapterQuery->fetch())
            	{
            		$chapterEnding = stripslashes(nl2br($chapter['chapterEnding']));
            	}
            	$chapterQuery->closeCursor();
            	
                //On affiche la fin du chapitre
                echo "$chapterEnding";
                
                //On met à jour le chapitre du joueur à jour
                $updateCharacterChapter = $bdd->prepare('UPDATE car_characters 
                SET characterChapter = characterChapter + 1
                WHERE characterId = :characterId');
                $updateCharacterChapter->execute(['characterId' => $characterId]);
                $updateCharacterChapter->closeCursor();
            }
            //Si le chapitre n'existe pas
            else 
            {
            	echo "Il n'y a actuellement aucun nouveau chapitre";
            }
        }

        //On donne les récompenses au personnage et on le met à jour dans la base de donnée
        $updateCharacter = $bdd->prepare("UPDATE car_characters
        SET characterExperience = characterExperience + :opponentExperience,
        characterExperienceTotal = characterExperienceTotal + :opponentExperience,
        characterGold = characterGold + :opponentGold
        WHERE characterId = :characterId");
        $updateCharacter->execute([
        'opponentExperience' => $opponentExperience,
        'opponentGold' => $opponentGold,
        'characterId' => $characterId]);
        $updateCharacter->closeCursor();
    }
    //Si il s'agit d'un combat contre un joueur
    else if ($battleType == "Arena")
    {
        echo "-1 point de victoire<br />";
        //On donne les récompenses au personnage et on le met à jour dans la base de donnée
        $updateCharacter = $bdd->prepare("UPDATE car_characters
        SET characterVictory = characterVictory + 1
        WHERE characterId = :characterId");
        $updateCharacter->execute([
        'characterId' => $characterId]);
        $updateCharacter->closeCursor();
    }
    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles 
    WHERE battleId = :battleId");
    $deleteBattle->execute(array('battleId' => $battleId));
    ?>

    <hr>

    <form method="POST" action="../../modules/town/index.php">
        <input type="submit" name="escape" class="btn btn-default form-control" value="Continuer"><br />
    </form>
    <?php
}

//Si le joueur a moins ou a zéro HP et que le monstre à plus de 0 HP
if ($characterHpMin <= 0 && $battleOpponentHpRemaining > 0)
{
    //On prévient le joueur qu'il a perdu
    ?>
    <p><?php echo $opponentName; ?> remporte le combat !</p>
    <?php
    
    //On soigne le personnage et ont le met à jour dans la base de donnée
    $updateCharacter = $bdd->prepare("UPDATE car_characters
    SET characterHpMin = characterHpTotal,
    characterMpMin = characterMpTotal
    WHERE characterId = :characterId");
    $updateCharacter->execute([
    'characterId' => $characterId]);

    //On détruit le combat en cours
    $deleteBattle = $bdd->prepare("DELETE FROM car_battles 
    WHERE battleId = :battleId");
    $deleteBattle->execute(array('battleId' => $battleId));
    ?>

    <hr>

    <form method="POST" action="../../modules/town/index.php">
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