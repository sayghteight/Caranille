<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownMonsterTownId'])
&& isset($_POST['adminTownMonsterMonsterId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownMonsterTownId'])
    && ctype_digit($_POST['adminTownMonsterMonsterId'])
    && $_POST['adminTownMonsterTownId'] >= 1
    && $_POST['adminTownMonsterMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
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

            //Si l'équippement est disponible
            if ($monsterRow == 1) 
            {
                //On fait une requête pour vérifier si le monstre choisit existe bien dans la ville
                $monsterQuery = $bdd->prepare('SELECT * FROM car_towns_monsters 
                WHERE townMonsterTownId = ?
                AND townMonsterMonsterId = ?');
                $monsterQuery->execute([$adminTownMonsterTownId, $adminTownMonsterMonsterId]);
                $monsterRow = $monsterQuery->rowCount();

                //Si l'équippement est disponible
                if ($monsterRow == 1) 
                {
                    //On supprime l'équippement de la base de donnée
                    $townMonsterDeleteQuery = $bdd->prepare("DELETE FROM car_towns_monsters
                    WHERE townMonsterMonsterId = ?");
                    $townMonsterDeleteQuery->execute([$adminTownMonsterMonsterId]);
                    $townMonsterDeleteQuery->closeCursor();
                    ?>

                    Le monstre a bien été retiré de la ville

                    <hr>
                        
                    <form method="POST" action="manageTownMonster.php">
                        <input type="hidden" name="adminTownMonsterTownId" value="<?= $adminTownMonsterTownId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
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