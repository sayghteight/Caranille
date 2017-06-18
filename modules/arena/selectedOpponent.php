<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['opponentCharacterId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['opponentCharacterId'])
    && $_POST['opponentCharacterId'] >= 1)
    {
        //On récupère l'ID de la personne à défier
        $opponentCharacterId = htmlspecialchars(addslashes($_POST['opponentCharacterId']));

        //On fait une requête pour vérifier si le personnage est bien disponible dans la ville du joueur
        $opponentQuery = $bdd->prepare("SELECT * FROM car_characters 
        WHERE characterId = ?
        AND characterTownId = ?");
        $opponentQuery->execute([$opponentCharacterId, $townId]);
        $opponent = $opponentQuery->rowCount();

        //Si le personnages a été trouvé
        if ($opponent == 1)
        {
            //On recherche le personnage
            while ($opponent = $opponentQuery->fetch())
            {
                $opponentCharacterHp = stripslashes($opponent['characterHpTotal']);
                $opponentCharacterMp = stripslashes($opponent['characterMpTotal']);
            }
            $opponentQuery->closeCursor();

            //Insertion du combat dans la base de donnée avec les données du personnage adverse
            $addBattleArena = $bdd->prepare("INSERT INTO car_battles_arenas VALUES(
            '',
            :characterId,
            :opponentCharacterId,
            :opponentCharacterHp,
            :opponentCharacterMp)");

            $addBattleArena->execute([
            'characterId' => $characterId,
            'opponentCharacterId' => $opponentCharacterId,
            'opponentCharacterHp' => $opponentCharacterHp,
            'opponentCharacterMp' => $opponentCharacterMp]);
            
            $addBattleArena->closeCursor();

            //On redirige l'utilisateur vers le module battleArena
            header("Location: ../../modules/battleArena/index.php");
        }
        //Si le personnage n'est pas disponible
        else
        {
            echo "Erreur: Personnage indisponible";
        }
    }
    //Si le personnage n'est pas un nombre
    else
    {
        echo "Erreur: personnage invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>