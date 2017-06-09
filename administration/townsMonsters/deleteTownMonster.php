<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton delete
if (isset($_POST['adminTownMonsterTownId'])
&& isset($_POST['adminTownMonsterMonsterId'])
&& isset($_POST['delete']))
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
            while ($town = $townQuery->fetch())
            {
                $adminTownMonsterTownName = stripslashes($town['townName']);
            }
    
            //On fait une requête pour vérifier si le monstre choisit existe
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId= ?');
            $monsterQuery->execute([$adminTownMonsterMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si le monstre est disponible
            if ($monsterRow == 1) 
            {
                while ($monster = $monsterQuery->fetch())
                {
                    $adminTownMonsterMonsterName = stripslashes($monster['monsterName']);
                }

                //On fait une requête pour vérifier si le monstre choisit existe bien dans la ville
                $monsterTownQuery = $bdd->prepare('SELECT * FROM car_towns_monsters 
                WHERE townMonsterTownId = ?
                AND townMonsterMonsterId = ?');
                $monsterTownQuery->execute([$adminTownMonsterTownId, $adminTownMonsterMonsterId]);
                $monsterTownRow = $monsterTownQuery->rowCount();

                //Si le monstre est disponible
                if ($monsterTownRow == 1) 
                {
                    ?>
                    <p>ATTENTION</p> 
                    Vous êtes sur le point de supprimer le monstre <em><?php echo $adminTownMonsterMonsterName ?></em> de la ville <em><?php echo $adminTownMonsterTownName ?></em><br />
                    confirmez-vous la suppression ?

                    <hr>
                        
                    <form method="POST" action="deleteTownMonsterEnd.php">
                        <input type="hidden" class="btn btn-default form-control" name="adminTownMonsterTownId" value="<?= $adminTownMonsterTownId ?>">
                        <input type="hidden" class="btn btn-default form-control" name="adminTownMonsterMonsterId" value="<?= $adminTownMonsterMonsterId ?>">
                        <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
                    </form>
            
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
                $monsterTownQuery->closeCursor();
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
//Si le joueur n'a pas cliqué sur le bouton finalDelete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");