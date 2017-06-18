<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
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
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");