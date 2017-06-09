<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton add
if (isset($_POST['adminTownMonsterTownId'])
&& isset($_POST['adminTownMonsterMonsterId'])
&& isset($_POST['add']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre
    if (ctype_digit($_POST['adminTownMonsterTownId'])
    && ctype_digit($_POST['adminTownMonsterMonsterId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminTownMonsterTownId = htmlspecialchars(addslashes($_POST['adminTownMonsterTownId']));
        $adminTownMonsterMonsterId = htmlspecialchars(addslashes($_POST['adminTownMonsterMonsterId']));

        //On fait une requête pour vérifier si la ville choisie existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $townQuery->execute([$adminTownMonsterTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville est disponible
        if ($townRow == 1) 
        {
            //On fait une requête pour vérifier si le monstre choisit existe
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId= ?');
            $monsterQuery->execute([$adminTownMonsterMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si le monstre est disponible
            if ($monsterRow == 1) 
            {
                //On fait une requête pour vérifier si le monstre n'est pas déjà dans cette ville
                $townMonsterQuery = $bdd->prepare('SELECT * FROM car_towns_monsters 
                WHERE townMonsterTownId = ?
                AND townMonsterMonsterId = ?');
                $townMonsterQuery->execute([$adminTownMonsterTownId, $adminTownMonsterMonsterId]);
                $townMonsterRow = $townMonsterQuery->rowCount();

                //Si le monstre n'est pas dans la ville
                if ($townMonsterRow == 0) 
                {
                    //On met à jour le monstre dans la base de donnée
                    $addTownMonster = $bdd->prepare("INSERT INTO car_towns_monsters VALUES(
                    '',
                    :townMonsterTownId,
                    :townMonsterMonsterId)");

                    $addTownMonster->execute([
                    'townMonsterTownId' => $adminTownMonsterTownId,
                    'townMonsterMonsterId' => $adminTownMonsterMonsterId]);
                    $addTownMonster->closeCursor();
                    ?>

                    Le monstre a bien été ajouté à la ville

                    <hr>
                        
                    <form method="POST" action="manageTownMonster.php">
                        <input type="hidden" name="adminTownMonsterTownId" value="<?= $adminTownMonsterTownId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                    </form>
                    <?php
                }
                //Si le monstre est déjà dans cette ville
                else
                {
                    //Si le joueur a essayé de mettre un monstre qui est déjà dans la ville on lui donne la possibilité de revenir en arrière
                    ?>
                    Erreur: Ce monstre est déjà dans cette ville
                    <form method="POST" action="manageTownMonster.php">
                        <input type="hidden" name="adminTownMonsterTownId" value="<?= $adminTownMonsterTownId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                    </form>
                    <?php
                }
                $monsterQuery->closeCursor();
            }
            //Si le monstre existe pas
            else
            {
                echo "Erreur: Monstre indisponible";
            }
            $monsterQuery->closeCursor();
        }
        //Si la ville existe pas
        else
        {
            echo "Erreur: Ville indisponible";
        }
        $townQuery->closeCursor();
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");