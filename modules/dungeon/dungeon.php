<?php require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['battleMonsterId']))
{
    //On vérifi si la monstre choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['battleMonsterId']))
    {
        //On récupère l'Id du monstre
        $monsterId = htmlspecialchars(addslashes($_POST['battleMonsterId']));

        //On fait une requête pour vérifier si le monstre est bien disponible dans la ville du joueur
        $monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
        WHERE townMonsterId = monsterId
        AND townTownId = townId
        AND monsterId = ?
        AND townId = ?");
        $monsterQueryList->execute([$monsterId, $townId]);
        $monsterQuery = $monsterQueryList->rowCount();

        //Si le monstre est disponible
        if ($monsterQuery > 0) 
        {
            //On fait une requête pour récupérer les informations du monstre
            $monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters
            WHERE monsterId = ?");
            $monsterQueryList->execute([$monsterId]);

            while ($monster = $monsterQueryList->fetch())
            {
                //On récupère les HP et MP du monstre
                $monsterHp = $monster['monsterHp'];
                $monsterMp = $monster['monsterMp'];
            }

            //Insertion du combat dans la base de donnée avec les données du monstre
            $addBattmeMonster = $bdd->prepare("INSERT INTO car_battles_monsters VALUES(
            '',
            :characterId,
            :monsterId,
            :monsterHp,
            :monsterMp)");

            $addBattmeMonster->execute([
            'characterId' => $characterId,
            'monsterId' => $monsterId,
            'monsterHp' => $monsterHp,
            'monsterMp' => $monsterMp]);
            $addBattmeMonster->closeCursor();

            header("Location: ../../modules/battleMonster/index.php");
        }
        //Si le monstre n'est pas disponible
        else
        {
            echo "Erreur: Monstre indisponible";
        }
    }
}

require_once("../../html/footer.php"); ?>