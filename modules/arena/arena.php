<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['battleCharacterId']))
{
    //On vérifi si la monstre choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['battleCharacterId']))
    {
        //On récupère l'ID de la personne à défier
        $battleCharacterId = htmlspecialchars(addslashes($_POST['battleCharacterId']));

        //On recherche le personnage
        $opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
        WHERE characterId = ?");
        $opponentQuery->execute([$battleCharacterId]);

        //On fait une boucle pour récupérer les résultats
        while ($opponent = $opponentQuery->fetch())
        {
            $opponentCharacterHp = stripslashes($opponent['characterHpTotal']);
            $opponentCharacterMp = stripslashes($opponent['characterMpTotal']);
        }

        //On enregistre le combat dans la base de donnée
        $addBattle = $bdd->prepare("INSERT INTO car_battles_arenas VALUES(
        '',
        :characterId,
        '0',
        '0',
        :characterHpTotal,
        :characterMpTotal,
        :opponentId,
        '0',
        '0',
        :opponentCharacterHp,
        :opponentCharacterMp)");

        $addBattle->execute([
        'characterId' => $characterId,
        'characterHpTotal' => $characterHpTotal,
        'characterMpTotal' => $characterMpTotal,
        'opponentId' => $battleCharacterId,
        'opponentCharacterHp' => $opponentCharacterHp,
        'opponentCharacterMp' => $opponentCharacterMp]);
        $addBattle->closeCursor();

        //On redirige l'utilisateur vers le module battleArena
        header("Location: ../../modules/battleArena/index.php");
    }
    //Si le joueur n'est pas disponible
    else
    {
        echo "Erreur: Monstre indisponible";
    }
}

require_once("../../html/footer.php"); ?>