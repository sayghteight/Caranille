<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['battleMonsterId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['battleMonsterId'])
    && $_POST['battleMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $opponentId = htmlspecialchars(addslashes($_POST['battleMonsterId']));

        //On fait une requête pour vérifier si le monstre est bien disponible dans la ville du joueur
        $opponentQuery = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
        WHERE townMonsterMonsterId = monsterId
        AND townMonsterTownId = townId
        AND monsterId = ?
        AND townId = ?");
        $opponentQuery->execute([$opponentId, $townId]);
        $opponentRow = $opponentQuery->rowCount();

        //Si le monstre existe
        if ($opponentRow == 1) 
        {
            while ($opponent = $opponentQuery->fetch())
            {
                //On récupère les informations du monstre
                $opponentHp = $opponent['monsterHp'];
                $opponentMp = $opponent['monsterMp'];
            }
            $opponentQuery->closeCursor();

            //Insertion du combat dans la base de donnée avec les données
            $addBattle = $bdd->prepare("INSERT INTO car_battles VALUES(
            NULL,
            :characterId,
            :opponentId,
            'Dungeon',
            :opponentHp,
            :opponentMp)");
            $addBattle->execute([
            'characterId' => $characterId,
            'opponentId' => $opponentId,
            'opponentHp' => $opponentHp,
            'opponentMp' => $opponentMp]);
            $addBattle->closeCursor();

            //On redirige le joueur vers le combat
            header("Location: ../../modules/battle/index.php");
        }
        //Si le monstre n'exite pas
        else
        {
            echo "Erreur: Monstre indisponible";
        }
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
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>