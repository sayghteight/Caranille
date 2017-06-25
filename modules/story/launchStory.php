<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['continue']))
{
    //On vérifie si le chapitre existe
    $chapterQuery = $bdd->prepare("SELECT * FROM car_chapters
    WHERE chapterId = ?");
    $chapterQuery->execute([$characterChapter]);
    $chapterRow = $chapterQuery->rowCount();
    
    //Si le chapitre existe
    if ($chapterRow == 1)
    {
        //On fait une requête pour récupérer le monstre du chapitre
        $opponentQuery = $bdd->prepare("SELECT * FROM car_monsters, car_chapters
        WHERE monsterId = chapterMonsterId
        AND chapterId = ?");
        $opponentQuery->execute([$characterChapter]);
        $opponentRow = $opponentQuery->rowCount();

        //Si le monstre est disponible
        if ($opponentRow == 1) 
        {
            while ($opponent = $opponentQuery->fetch())
            {
                //On récupère les HP et MP du monstre
                $opponentId = $opponent['monsterId'];
                $opponentHp = $opponent['monsterHp'];
                $opponentMp = $opponent['monsterMp'];
            }
            $opponentQuery->closeCursor();

            //Insertion du combat dans la base de donnée avec les données
            $addBattle = $bdd->prepare("INSERT INTO car_battles VALUES(
            '',
            :characterId,
            :opponentId,
            'Story',
            :opponentHp,
            :opponentMp)");

            $addBattle->execute([
            'characterId' => $characterId,
            'opponentId' => $opponentId,
            'opponentHp' => $opponentHp,
            'opponentMp' => $opponentMp]);
            $addBattle->closeCursor();

            //On redirige le joueur vers le combat du monstre
            header("Location: ../../modules/battle/index.php");
        }
        //Si le monstre n'est pas disponible
        else
        {
            echo "Erreur: Monstre indisponible";
        }
    }
    //Si le chapitre n'exite pas
    {
        echo "Erreur: Le chapitre demandé n'existe pas";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../../html/footer.php"); ?>