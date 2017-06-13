<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton edit ou delete
if (isset($_POST['adminMonsterDropMonsterId'])
&& isset($_POST['adminMonsterDropItemId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterDropMonsterId'])
    && ctype_digit($_POST['adminMonsterDropItemId'])
    && $_POST['adminMonsterDropMonsterId'] >= 1
    && $_POST['adminMonsterDropItemId'] >= 1)
    {
        //On récupère l'Id du formulaire précédent
        $adminMonsterDropMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterDropMonsterId']));
        $adminMonsterDropItemId = htmlspecialchars(addslashes($_POST['adminMonsterDropItemId']));

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId= ?');
        $monsterQuery->execute([$adminMonsterDropMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre est disponible
        if ($monsterRow == 1) 
        {
            //On récupère les informations du monstre
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterDropMonsterName = stripslashes($monster['monsterName']);
            }

            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId= ?');
            $itemQuery->execute([$adminMonsterDropItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet est disponible
            if ($itemRow == 1) 
            {
                while ($item = $itemQuery->fetch())
                {
                    $adminMonsterDropItemName = stripslashes($item['itemName']);
                }

                //On fait une requête pour vérifier si l'objet est sur ce monstre
                $monsterDropQuery = $bdd->prepare('SELECT * FROM car_monsters_drops 
                WHERE monsterDropMonsterID = ?
                AND monsterDropItemID = ?');
                $monsterDropQuery->execute([$adminMonsterDropMonsterId, $adminMonsterDropItemId]);
                $monsterDropRow = $monsterDropQuery->rowCount();

                //Si cet objet est sur le monstre
                if ($monsterDropRow == 1) 
                {
                    //Si l'utilisateur à cliqué sur le bouton edit
                    if (isset($_POST['edit']))
                    {
                        //On récupère le taux d'obtention de l'objet/équipement
                        while ($monsterDrop = $monsterDropQuery->fetch())
                        {
                            $adminMonsterDropLuck = stripslashes($monsterDrop['monsterDropLuck']);
                        }
                        ?>
                        <form method="POST" action="editMonsterDrop.php">
                            Taux d'obtention (De 0 à 1000) : <br> <input type="number" name="adminMonsterDropLuck" class="form-control" placeholder="Taux d'obtention" value="<?php echo $adminMonsterDropLuck; ?>" required><br /><br />
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropItemId" value="<?= $adminMonsterDropItemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalEdit" value="Mettre à jour">
                        </form>
                        <?php
                    }
                    
                    //Si l'utilisateur à cliqué sur le bouton delete
                    if (isset($_POST['delete']))
                    {
                        ?>
                        <p>ATTENTION</p> 
                        Vous êtes sur le point de supprimer l'objet <em><?php echo $adminMonsterDropItemName ?></em> du monstre <em><?php echo $adminMonsterDropMonsterName ?></em><br />
                        confirmez-vous la suppression ?

                        <hr>
                            
                        <form method="POST" action="deleteMonsterDrop.php">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropItemId" value="<?= $adminMonsterDropItemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme la suppression">
                        </form>
                
                        <hr>

                        <form method="POST" action="index.php">
                            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                        </form>
                        <?php
                    }
                }
                //Si l'objet n'est pas disponible
                else
                {
                    echo "Erreur: Objet indisponible";
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
    //Si le monstre choisit n'est pas un nombre
    else
    {
        echo "Erreur: Monstre invalide";
    }
}
//Si le joueur n'a pas cliqué sur le bouton finalDelete
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");