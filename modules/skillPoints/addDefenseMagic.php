<?php 
require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

if ($characterSkillPoints > 0)
{
    //On met la stats à jour
    $updateCharacter = $bdd->prepare('UPDATE car_characters 
    SET characterDefenseMagicSkillPoints = characterDefenseMagicSkillPoints + 1,
    characterSkillPoints = characterSkillPoints -1
    WHERE characterId = :characterId');
    $updateCharacter->execute(['characterId' => $characterId]);
    $updateCharacter->closeCursor();

    $updateCharacter = $bdd->prepare('UPDATE car_characters
    SET characterHpTotal = characterHpMax + characterHpSkillPoints + characterHpBonus + characterHpEquipments,
    characterMpTotal = characterMpMax + characterMpSkillPoints + characterMpBonus + characterMpEquipments,
    characterStrengthTotal = characterStrength + characterStrengthSkillPoints + characterStrengthBonus + characterStrengthEquipments,
    characterMagicTotal = characterMagic + characterMagicSkillPoints + characterMagicBonus + characterMagicEquipments,
    characterAgilityTotal = characterAgility + characterAgilitySkillPoints + characterAgilityBonus + characterAgilityEquipments,
    characterDefenseTotal = characterDefense + characterDefenseSkillPoints + characterDefenseBonus + characterDefenseEquipments,
    characterDefenseMagicTotal = characterDefenseMagic + characterDefenseMagicSkillPoints + characterDefenseMagicBonus + characterDefenseMagicEquipments,
    characterWisdomTotal = characterWisdom + characterWisdomSkillPoints + characterWisdomBonus + characterWisdomEquipments
    WHERE characterId = :characterId');
    $updateCharacter->execute(['characterId' => $characterId]);
    $updateCharacter->closeCursor();
    
    header("Location: index.php");
}
else
{
    echo "Vous n'avez pas assez de points de compétences";
    ?>
    <form method="POST" action="index.php">
	    <input type="submit" class="btn btn-default form-control" value="Retour">
    </form>
    <?php
}

require_once("../../html/footer.php"); ?>
