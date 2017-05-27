<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//On récupère l'ID de la personne à défier
$opponentId = htmlspecialchars(addslashes($_POST['opponentId']));

//On recherche le personnage
$opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
WHERE characterId = ?");
$opponentQuery->execute([$opponentId]);

//On fait une boucle pour récupérer les résultats
while ($opponent = $opponentQuery->fetch())
{
    $opponentcharacterHp = $opponent['characterHpTotal'];
    $opponentcharacterMp = $opponent['characterMpTotal'];
}

//On enregistre le combat dans la base de donnée
$addBattle = $bdd->prepare("INSERT INTO mop_battles VALUES(
'',
:characterId,
'no',
'0',
:characterHpTotal,
:characterMpTotal,
:opponentId,
'no',
'0',
:opponentCharacterHp,
:opponentCharacterMp)");

$addBattle->execute([
'characterId' => $characterId,
'characterHpTotal' => $characterHpTotal,
'characterMpTotal' => $characterMpTotal,
'opponentId' => $opponentId,
'opponentCharacterHp' => $opponentCharacterHp,
'opponentCharacterMp' => $opponentCharacterMp]);
$addBattle->closeCursor();

//On redirige l'utilisateur vers le module battleArena
header("Location: ../../modules/battleArena/index.php");

require_once("../../html/footer.php"); ?>