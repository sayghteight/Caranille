<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalDelete
if (isset($_POST['finalDelete']))
{
    //On vérifie si l'id de l'équippement choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminMonsterId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId= ?');
        $monsterQuery->execute([$adminMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si l'équippement est disponible
        if ($monsterRow == 1) 
        {
            //On supprime l'équippement de la base de donnée
            $monsterDeleteQuery = $bdd->prepare("DELETE FROM car_monsters
            WHERE monsterId = ?");
            $monsterDeleteQuery->execute([$adminMonsterId]);
            $monsterDeleteQuery->closeCursor();

            //On supprime aussi les combats contre ce monstre dans la base de donnée
            $batleMonsterDeleteQuery = $bdd->prepare("DELETE FROM car_battles_monsters
            WHERE battleMonsterMonsterId = ?");
            $batleMonsterDeleteQuery->execute([$adminMonsterId]);
            $batleMonsterDeleteQuery->closeCursor();

            //On supprime aussi les monstre de la ville où il se trouve
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
        //Si le monstre n'est pas disponible
        else
        {
            echo "Erreur: Monstre indisponible";
        }
        $monsterQuery->closeCursor();
    }
    //Si le monstre choisit n'est pas un nombre
    else
    {
        echo "Erreur: Equippement invalide";
    }
}
//Si le joueur n'a pas cliqué sur le bouton finalDelete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");