<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterId'])
    && $_POST['adminMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));

        //On fait une requête pour vérifier si le monstre choisi existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId= ?');
        $monsterQuery->execute([$adminMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre existe
        if ($monsterRow == 1) 
        {
            //On vérifie si le monstre n'est pas attribué à un chapitre
            $monsterChapterQuery = $bdd->prepare("SELECT * FROM car_chapters
            WHERE chapterMonsterId = ?");
            $monsterChapterQuery->execute([$adminMonsterId]);
            //On recherche combien il y a de monstres disponible
            $monsterChapterRow = $monsterChapterQuery->rowCount();
            $monsterChapterQuery->closeCursor();

            //Si ce monstre n'est pas attribué à un chapitre
            if ($monsterChapterRow == 0)
            {
                //On supprime le monstre de la base de donnée
                $monsterDeleteQuery = $bdd->prepare("DELETE FROM car_monsters
                WHERE monsterId = ?");
                $monsterDeleteQuery->execute([$adminMonsterId]);
                $monsterDeleteQuery->closeCursor();

                //On supprime aussi les combats contre ce monstre dans la base de donnée
                $batleMonsterDeleteQuery = $bdd->prepare("DELETE FROM car_battles
                WHERE battleOpponentId = ?");
                $batleMonsterDeleteQuery->execute([$adminMonsterId]);
                $batleMonsterDeleteQuery->closeCursor();
                
                //On supprime aussi les objets à dropper du monstre
                $monsterDropDeleteQuery = $bdd->prepare("DELETE FROM car_monsters_drops
                WHERE monsterDropMonsterId = ?");
                $monsterDropDeleteQuery->execute([$adminMonsterId]);
                $monsterDropDeleteQuery->closeCursor();
    
                //On supprime aussi le monstre de la ville où il se trouve
                $townMonsterDeleteQuery = $bdd->prepare("DELETE FROM car_towns_monsters
                WHERE townMonsterMonsterId = ?");
                $townMonsterDeleteQuery->execute([$adminMonsterId]);
                $townMonsterDeleteQuery->closeCursor();
                ?>
    
                Le monstre a bien été supprimé
    
                <hr>
                    
                <form method="POST" action="index.php">
                        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                    </form>
                <?php
            }
            //Si le monstre est attribué à un chapitre
            else
            {
                ?>
                Impossible de supprimer ce monstre car il est attribué à un chapitre.
                <form method="POST" action="manageMonster.php">
                    <input type="hidden" name="adminMonsterId" value="<?= $adminMonsterId ?>">
                    <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                </form>
                <?php
            }
        }
        //Si le monstre n'exite pas
        else
        {
            echo "Erreur: Monstre indisponible";
        }
        $monsterQuery->closeCursor();
    }
    //Si le monstre choisi n'est pas un nombre
    else
    {
        echo "Erreur: Equippement invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");