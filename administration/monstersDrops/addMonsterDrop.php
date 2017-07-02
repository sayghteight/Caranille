<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterDropMonsterId'])
&& isset($_POST['adminMonsterDropItemId'])
&& isset($_POST['adminMonsterDropLuck'])
&& isset($_POST['add']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterDropMonsterId'])
    && ctype_digit($_POST['adminMonsterDropItemId'])
    && ctype_digit($_POST['adminMonsterDropLuck'])
    && $_POST['adminMonsterDropMonsterId'] >= 0
    && $_POST['adminMonsterDropItemId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $adminMonsterDropMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterDropMonsterId']));
        $adminMonsterDropItemId = htmlspecialchars(addslashes($_POST['adminMonsterDropItemId']));
        $adminMonsterDropLuck = htmlspecialchars(addslashes($_POST['adminMonsterDropLuck']));

        //Si le taux d'obtention est entre 0 et 100 on ajoute l'objet
        if ($adminMonsterDropLuck >= 0 && $adminMonsterDropLuck <= 100)
        {
            //On fait une requête pour vérifier si le monstre choisi existe
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId= ?');
            $monsterQuery->execute([$adminMonsterDropMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si la ville existe
            if ($monsterRow == 1) 
            {
                //On fait une requête pour vérifier si l'objet choisi existe
                $itemQuery = $bdd->prepare('SELECT * FROM car_items 
                WHERE itemId= ?');
                $itemQuery->execute([$adminMonsterDropItemId]);
                $itemRow = $itemQuery->rowCount();

                //Si l'objet existe
                if ($itemRow == 1) 
                {
                    //On fait une requête pour vérifier si l'objet n'est pas déjà sur ce monstre
                    $monsterDropQuery = $bdd->prepare('SELECT * FROM car_monsters_drops 
                    WHERE monsterDropMonsterID = ?
                    AND monsterDropItemID = ?');
                    $monsterDropQuery->execute([$adminMonsterDropMonsterId, $adminMonsterDropItemId]);
                    $monsterDropRow = $monsterDropQuery->rowCount();

                    //Si cet objet n'est pas sur le monstre
                    if ($monsterDropRow == 0) 
                    {
                        //On met à jour le monstre dans la base de donnée
                        $addTownMonster = $bdd->prepare("INSERT INTO car_monsters_drops VALUES(
                        '',
                        :adminMonsterDropMonsterId,
                        :adminMonsterDropItemId,
                        :adminMonsterDropLuck)");

                        $addTownMonster->execute([
                        'adminMonsterDropMonsterId' => $adminMonsterDropMonsterId,
                        'adminMonsterDropItemId' => $adminMonsterDropItemId,
                        'adminMonsterDropLuck' => $adminMonsterDropLuck]);
                        $addTownMonster->closeCursor();
                        ?>

                        L'objet/équipement a bien été ajouté au monstre

                        <hr>
                            
                        <form method="POST" action="manageMonsterDrop.php">
                            <input type="hidden" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                            <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                        </form>
                        
                        <?php
                    }
                    //Si l'objet est déjà sur le monstre est déjà dans cette ville
                    else
                    {
                        //Si le joueur a essayé de mettre un objet qui est déjà sur le monstre on lui donne la possibilité de revenir en arrière
                        ?>
                        
                        Erreur: Cet objet est déjà sur ce monstre
                        <form method="POST" action="manageMonsterDrop.php">
                            <input type="hidden" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                            <input type="submit" class="btn btn-default form-control" name="manage" value="Retour">
                        </form>
                        
                        <?php
                    }
                    $monsterDropQuery->closeCursor();
                }
                //Si l'objet existe pas
                else
                {
                    echo "Erreur: Objet indisponible";
                }
                $itemQuery->closeCursor();
            }
            //Si le monstre existe pas
            else
            {
                echo "Erreur: Monstre indisponible";
            }
            $monsterQuery->closeCursor();
        }
        //Si le taux d'obtention de l'objet est inférieur à 0 ou est supérieur à 1000
        else
        {
            ?>
            
            Erreur: Le taux d'obtention doit être entre 0 et 100%
            <form method="POST" action="manageMonsterDrop.php">
                <input type="hidden" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
            </form>
            
            <?php
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

require_once("../html/footer.php");