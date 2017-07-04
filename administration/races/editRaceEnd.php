<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRaceId'])
&& isset($_POST['adminRacePicture'])
&& isset($_POST['adminRaceName']) 
&& isset($_POST['adminRaceDescription'])
&& isset($_POST['adminRaceHpBonus'])
&& isset($_POST['adminRaceMpBonus'])
&& isset($_POST['adminRaceStrengthBonus'])
&& isset($_POST['adminRaceMagicBonus'])
&& isset($_POST['adminRaceAgilityBonus'])
&& isset($_POST['adminRaceDefenseBonus'])
&& isset($_POST['adminRaceDefenseMagicBonus'])
&& isset($_POST['adminRaceWisdomBonus'])
&& isset($_POST['finalEdit']))
{
    //On vérifie si l'id de la race récupéré dans le formulaire est en entier positif
    if (ctype_digit($_POST['adminRaceId'])
    && ctype_digit($_POST['adminRaceHpBonus'])
    && ctype_digit($_POST['adminRaceMpBonus'])
    && ctype_digit($_POST['adminRaceStrengthBonus'])
    && ctype_digit($_POST['adminRaceMagicBonus'])
    && ctype_digit($_POST['adminRaceAgilityBonus'])
    && ctype_digit($_POST['adminRaceDefenseBonus'])
    && ctype_digit($_POST['adminRaceDefenseMagicBonus'])
    && ctype_digit($_POST['adminRaceWisdomBonus'])
    && $_POST['adminRaceId'] >= 1
    && $_POST['adminRaceHpBonus'] >= 0
    && $_POST['adminRaceMpBonus'] >= 0
    && $_POST['adminRaceStrengthBonus'] >= 0
    && $_POST['adminRaceMagicBonus'] >= 0
    && $_POST['adminRaceAgilityBonus'] >= 0
    && $_POST['adminRaceDefenseBonus'] >= 0
    && $_POST['adminRaceDefenseMagicBonus'] >= 0
    && $_POST['adminRaceWisdomBonus'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminRaceId = htmlspecialchars(addslashes($_POST['adminRaceId']));
        $adminRacePicture = htmlspecialchars(addslashes($_POST['adminRacePicture']));
        $adminRaceName = htmlspecialchars(addslashes($_POST['adminRaceName']));
        $adminRaceDescription = htmlspecialchars(addslashes($_POST['adminRaceDescription']));
        $adminRaceHpBonus = htmlspecialchars(addslashes($_POST['adminRaceHpBonus']));
        $adminRaceMpBonus = htmlspecialchars(addslashes($_POST['adminRaceMpBonus']));
        $adminRaceStrengthBonus = htmlspecialchars(addslashes($_POST['adminRaceStrengthBonus']));
        $adminRaceMagicBonus = htmlspecialchars(addslashes($_POST['adminRaceMagicBonus']));
        $adminRaceAgilityBonus = htmlspecialchars(addslashes($_POST['adminRaceAgilityBonus']));
        $adminRaceDefenseBonus = htmlspecialchars(addslashes($_POST['adminRaceDefenseBonus']));
        $adminRaceDefenseMagicBonus = htmlspecialchars(addslashes($_POST['adminRaceDefenseMagicBonus']));
        $adminRaceWisdomBonus = htmlspecialchars(addslashes($_POST['adminRaceWisdomBonus']));
        
        //On fait une requête pour vérifier si la race choisie existe
        $raceQuery = $bdd->prepare('SELECT * FROM car_races 
        WHERE raceId = ?');
        $raceQuery->execute([$adminRaceId]);
        $raceRow = $raceQuery->rowCount();

        //Si la race existe
        if ($raceRow == 1) 
        {
            //On met à jour la race dans la base de donnée
            $updateRace = $bdd->prepare('UPDATE car_races 
            SET racePicture = :adminRacePicture,
            raceName = :adminRaceName, 
            raceDescription = :adminRaceDescription,
            raceHpBonus = :adminRaceHpBonus,
            raceMpBonus = :adminRaceMpBonus,
            raceStrengthBonus = :adminRaceStrengthBonus,
            raceMagicBonus = :adminRaceMagicBonus,
            raceAgilityBonus = :adminRaceAgilityBonus,
            raceDefenseBonus = :adminRaceDefenseBonus,
            raceDefenseMagicBonus = :adminRaceDefenseMagicBonus,
            raceWisdomBonus = :adminRaceWisdomBonus
            WHERE raceId = :adminRaceId');

            $updateRace->execute([
            'adminRacePicture' => $adminRacePicture,
            'adminRaceName' => $adminRaceName,
            'adminRaceDescription' => $adminRaceDescription,
            'adminRaceHpBonus' => $adminRaceHpBonus,
            'adminRaceMpBonus' => $adminRaceMpBonus,
            'adminRaceStrengthBonus' => $adminRaceStrengthBonus,
            'adminRaceMagicBonus' => $adminRaceMagicBonus,
            'adminRaceAgilityBonus' => $adminRaceAgilityBonus,
            'adminRaceDefenseBonus' => $adminRaceDefenseBonus,
            'adminRaceDefenseMagicBonus' => $adminRaceDefenseMagicBonus,
            'adminRaceWisdomBonus' => $adminRaceWisdomBonus,
            'adminRaceId' => $adminRaceId]);
            $updateRace->closeCursor();
            
            //On cherche les joueurs qui utilise cette classe et qui sont au dessus du niveau 1
            $characterRaceQuery = $bdd->prepare('SELECT * FROM car_characters 
            WHERE characterRaceId = ?');
            $characterRaceQuery->execute([$adminRaceId]);
    
            //Pour chaque joueurs qui a cette classe
            while ($characterRace = $characterRaceQuery->fetch())
            {
                //On définit les statistiques d'un personnage de niveau 1
                $initialHp = 120;
                $initialMp = 10;
                $initialStrength = 10;
                $initialMagic = 10;
                $initialAgility = 10;
                $initialDefense = 10;
                $initialDefenseMagic = 10;
                $initialSagesse = 10;
                
                //On récupère le niveau du joueur et son Id
                $adminCharacterId = $characterRace['characterId'];
                $adminCharacterLevel = $characterRace['characterLevel'] - 1;
                
                $adminCharacterHP = $initialHp + $adminRaceHpBonus * $adminCharacterLevel;
                $adminCharacterMP = $initialMp + $adminRaceMpBonus * $adminCharacterLevel;
                $adminCharacterStrength = $initialStrength + $adminRaceStrengthBonus * $adminCharacterLevel;
                $adminCharacterMagic = $initialMagic + $adminRaceMagicBonus * $adminCharacterLevel;
                $adminCharacterAgility = $initialAgility + $adminRaceAgilityBonus * $adminCharacterLevel;
                $adminCharacterDefense = $initialDefense + $adminRaceDefenseBonus * $adminCharacterLevel;
                $adminCharacterDefenseMagic = $initialDefenseMagic + $adminRaceDefenseMagicBonus * $adminCharacterLevel;
                $adminCharacterSagesse = $initialSagesse + $adminRaceWidsomBonus * $adminCharacterLevel;
                
                //On met le personnage à jour
                $updateCharacter = $bdd->prepare("UPDATE car_characters SET
                characterHpMax = :adminCharacterHP, 
                characterMpMax = :adminCharacterMP, 
                characterStrength = :adminCharacterStrength, 
                characterMagic = :adminCharacterMagic, 
                characterAgility = :adminCharacterAgility, 
                characterDefense = :adminCharacterDefense, 
                characterDefenseMagic = :adminCharacterDefenseMagic, 
                characterWisdom = :adminCharacterSagesse
                WHERE characterId = :adminCharacterId");
            
                $updateCharacter->execute(array(
                'adminCharacterHP' => $adminCharacterHP,  
                'adminCharacterMP' => $adminCharacterMP, 
                'adminCharacterStrength' => $adminCharacterStrength, 
                'adminCharacterMagic' => $adminCharacterMagic, 
                'adminCharacterAgility' => $adminCharacterAgility, 
                'adminCharacterDefense' => $adminCharacterDefense, 
                'adminCharacterDefenseMagic' => $adminCharacterDefenseMagic, 
                'adminCharacterSagesse' => $adminCharacterSagesse, 
                'adminCharacterId' => $adminCharacterId));
                $updateCharacter->closeCursor();
                
                //On recalcule toutes les statisiques max du personnage
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
            }
            
            ?>

            La classe a bien été mise à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si la race n'existe pas
        else
        {
            echo "Erreur: Cette classe n'existe pas";
        }
        $raceQuery->closeCursor();
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");