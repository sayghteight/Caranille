<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterDropMonsterId'])
&& isset($_POST['adminMonsterDropItemId'])
&& isset($_POST['adminMonsterDropItemVisible'])
&& isset($_POST['adminMonsterDropRate'])
&& isset($_POST['adminMonsterDropRateVisible'])
&& isset($_POST['add']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterDropMonsterId'])
    && ctype_digit($_POST['adminMonsterDropItemId'])
    && ctype_digit($_POST['adminMonsterDropRate'])
    && $_POST['adminMonsterDropMonsterId'] >= 0
    && $_POST['adminMonsterDropItemId'] >= 0)
    {
        //On récupère l'id du formulaire précédent
        $adminMonsterDropMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterDropMonsterId']));
        $adminMonsterDropItemId = htmlspecialchars(addslashes($_POST['adminMonsterDropItemId']));
        $adminMonsterDropItemVisible = htmlspecialchars(addslashes($_POST['adminMonsterDropItemVisible']));
        $adminMonsterDropRate = htmlspecialchars(addslashes($_POST['adminMonsterDropRate']));
        $adminMonsterDropRateVisible = htmlspecialchars(addslashes($_POST['adminMonsterDropRateVisible']));

        //Si le taux d'obtention est entre 0 et 100 on ajoute l'objet
        if ($adminMonsterDropRate >= 0 && $adminMonsterDropRate <= 100)
        {
            //On fait une requête pour vérifier si le monstre choisit existe
            $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
            WHERE monsterId = ?');
            $monsterQuery->execute([$adminMonsterDropMonsterId]);
            $monsterRow = $monsterQuery->rowCount();

            //Si le monstre existe
            if ($monsterRow == 1) 
            {
                //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                while ($monster = $monsterQuery->fetch())
                {
                    //On récupère les informations du monstre
                    $adminMonsterDropMonsterPicture = stripslashes($monster['monsterPicture']);
                    $adminMonsterDropMonsterName = stripslashes($monster['monsterName']);
                }
                $monsterQuery->closeCursor();

                //On fait une requête pour vérifier si l'objet choisit existe
                $itemQuery = $bdd->prepare('SELECT * FROM car_items 
                WHERE itemId = ?');
                $itemQuery->execute([$adminMonsterDropItemId]);
                $itemRow = $itemQuery->rowCount();

                //Si l'objet existe
                if ($itemRow == 1) 
                {
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($item = $itemQuery->fetch())
                    {
                        ///On récupère les informations de l'objet
                        $adminMonsterDropItemPicture = stripslashes($item['itemPicture']);
                        $adminMonsterDropItemName = stripslashes($item['itemName']);
                    }
                    $itemQuery->closeCursor();

                    //On fait une requête pour vérifier si l'objet n'est pas déjà sur ce monstre
                    $monsterDropQuery = $bdd->prepare('SELECT * FROM car_monsters_drops 
                    WHERE monsterDropMonsterID = ?
                    AND monsterDropItemID = ?');
                    $monsterDropQuery->execute([$adminMonsterDropMonsterId, $adminMonsterDropItemId]);
                    $monsterDropRow = $monsterDropQuery->rowCount();

                    //Si cet objet n'est pas sur le monstre
                    if ($monsterDropRow == 0) 
                    {
                        ?>
            
                        <p>ATTENTION</p> 

                        Vous êtes sur le point d'ajouter l'objet <em><?php echo $adminMonsterDropItemName ?></em> sur le monstre <em><?php echo $adminMonsterDropMonsterName ?></em>.<br />
                        Confirmez-vous l'ajout ?

                        <hr>
                            
                        <form method="POST" action="addMonsterDropEnd.php">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterDropMonsterId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropItemId" value="<?php echo $adminMonsterDropItemId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropItemVisible" value="<?php echo $adminMonsterDropItemVisible ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropRate" value="<?php echo $adminMonsterDropRate ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropRateVisible" value="<?php echo $adminMonsterDropRateVisible ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalAdd" value="Je confirme">
                        </form>
                        
                        <hr>

                        <form method="POST" action="index.php">
                            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                        </form>

                        <?php
                    }
                    //Si l'objet est déjà sur le monstre est déjà dans cette ville
                    else
                    {
                        ?>
                        
                        Erreur: Cet objet est déjà sur ce monstre

                        <form method="POST" action="manageMonsterDrop.php">
                            <input type="hidden" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterDropMonsterId ?>">
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
                echo "Erreur: Ce monstre n'existe pas";
            }
            $monsterQuery->closeCursor();
        }
        //Si le taux d'obtention de l'objet est inférieur à 0 ou est supérieur à 1000
        else
        {
            ?>
            
            Erreur: Le taux d'obtention doit être entre 0 et 100%
            <form method="POST" action="manageMonsterDrop.php">
                <input type="hidden" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterDropMonsterId ?>">
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