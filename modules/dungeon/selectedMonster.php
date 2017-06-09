<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['battleMonsterId']))
{
    //On vérifie si la monstre choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['battleMonsterId']))
    {
        //On récupère l'Id du monstre
        $monsterId = htmlspecialchars(addslashes($_POST['battleMonsterId']));

        //On fait une requête pour vérifier si le monstre est bien disponible dans la ville du joueur
        /*
        SELECT * FROM car_monsters, car_towns, car_towns_monsters //On prend les trois tables car_monsters, car_towns, car_towns_monsters afin de les lier
        WHERE townMonsterId = monsterId //On fait une liaison entre la table car_towns_monsters et car_monsters avec l'Id du monstre
        AND townTownId = townId //On fait une liaison entre la table car_towns_monsters et car_towns avec l'Id de la ville
        AND monsterId = ? //On ajoute l'Id du monstre obtenu par le formulaire
        AND townId = ? //On ajoute l'Id de la ville dans laquel on est
        */
        $monsterQuery = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
        WHERE townMonsterMonsterId = monsterId
        AND townMonsterTownId = townId
        AND monsterId = ?
        AND townId = ?");
        $monsterQuery->execute([$monsterId, $townId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre est disponible
        if ($monsterRow == 1) 
        {
            while ($monster = $monsterQuery->fetch())
            {
                //On récupère les HP et MP du monstre
                $monsterHp = $monster['monsterHp'];
                $monsterMp = $monster['monsterMp'];
            }
            $monsterQuery->closeCursor();

            //Insertion du combat dans la base de donnée avec les données du monstre
            $addBattleMonster = $bdd->prepare("INSERT INTO car_battles_monsters VALUES(
            '',
            :characterId,
            :monsterId,
            :monsterHp,
            :monsterMp)");

            $addBattleMonster->execute([
            'characterId' => $characterId,
            'monsterId' => $monsterId,
            'monsterHp' => $monsterHp,
            'monsterMp' => $monsterMp]);
            $addBattleMonster->closeCursor();

            //On redirige le joueur vers le combat du monstre
            header("Location: ../../modules/battleMonster/index.php");
        }
        //Si le monstre n'est pas disponible
        else
        {
            echo "Erreur: Monstre indisponible";
        }
    }
    //Si le monstre n'est pas un nombre
    else
    {
        echo "Erreur: monstre invalide";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>