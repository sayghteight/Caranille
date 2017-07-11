<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterDropMonsterId'])
&& isset($_POST['adminMonsterDropItemId']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterDropMonsterId'])
    && ctype_digit($_POST['adminMonsterDropItemId'])
    && $_POST['adminMonsterDropMonsterId'] >= 1
    && $_POST['adminMonsterDropItemId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminMonsterDropMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterDropMonsterId']));
        $adminMonsterDropItemId = htmlspecialchars(addslashes($_POST['adminMonsterDropItemId']));

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId = ?');
        $monsterQuery->execute([$adminMonsterDropMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre existe
        if ($monsterRow == 1) 
        {
            //On fait une boucle sur tous les résultats
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterDropMonsterName = stripslashes($monster['monsterName']);
            }

            //On fait une requête pour vérifier si l'objet choisit existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$adminMonsterDropItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet existe
            if ($itemRow == 1) 
            {
                //On fait une boucle sur tous les résultats
                while ($item = $itemQuery->fetch())
                {
                    $adminMonsterDropItemName = stripslashes($item['itemName']);
                }

                //On fait une requête pour vérifier si l'objet est sur ce monstre
                $monsterDropQuery = $bdd->prepare('SELECT * FROM car_monsters_drops 
                WHERE monsterDropMonsterId = ?
                AND monsterDropItemId = ?');
                $monsterDropQuery->execute([$adminMonsterDropMonsterId, $adminMonsterDropItemId]);
                $monsterDropRow = $monsterDropQuery->rowCount();

                //Si cet objet est sur le monstre
                if ($monsterDropRow == 1) 
                {
                    //Si l'utilisateur à cliqué sur le bouton edit
                    if (isset($_POST['edit']))
                    {
                        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                        while ($monsterDrop = $monsterDropQuery->fetch())
                        {
                            //On récupère les informations des objets du monstre
                            $adminMonsterDropItemVisible = stripslashes($monsterDrop['monsterDropItemVisible']);
                            $adminMonsterDropRate = stripslashes($monsterDrop['monsterDropRate']);
                            $adminMonsterDropRateVisible = stripslashes($monsterDrop['monsterDropRateVisible']);
                        }
                        ?>
                        
                        <h4><?php echo $adminMonsterDropItemName ?></h4><br />
                        
                        <form method="POST" action="editMonsterDrop.php">
                            Objet visible dans le bestiaire ? : <select name="adminMonsterDropItemVisible" class="form-control">
                            
                                <?php
                                switch ($adminMonsterDropItemVisible)
                                {
                                    case "Yes":
                                        ?>
                                        
                                        <option selected="selected" value="Yes">Oui</option>
                                        <option value="No">Non</option>
                                        
                                        <?php
                                    break;
                
                                    case "No":
                                        ?>
                                        
                                        <option value="Yes">Oui</option>
                                        <option selected="selected" value="No">Non</option>
                                        
                                        <?php
                                    break;
                                }
                                ?>
                            
                            </select>
                            Taux d'obtention (en pourcentage) : <input type="number" name="adminMonsterDropRate" class="form-control" placeholder="Taux d'obtention (en pourcentage)" value="<?php echo $adminMonsterDropRate ?>" required>
                            Taux visible dans le bestiaire ? : <select name="adminMonsterDropRateVisible" class="form-control">
                            
                                <?php
                                switch ($adminMonsterDropRateVisible)
                                {
                                    case "Yes":
                                        ?>
                                        
                                        <option selected="selected" value="Yes">Oui</option>
                                        <option value="No">Non</option>
                                        
                                        <?php
                                    break;
                
                                    case "No":
                                        ?>
                                        
                                        <option value="Yes">Oui</option>
                                        <option selected="selected" value="No">Non</option>
                                        
                                        <?php
                                    break;
                                }
                                ?>
                            
                            </select>
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropItemId" value="<?= $adminMonsterDropItemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalEdit" value="Mettre à jour">
                        </form>
                        
                        <?php
                    }
                    //Si l'utilisateur à cliqué sur le bouton delete
                    elseif (isset($_POST['delete']))
                    {
                        ?>
                        
                        <p>ATTENTION</p>
                        
                        Vous êtes sur le point de retirer l'objet <em><?php echo $adminMonsterDropItemName ?></em> du monstre <em><?php echo $adminMonsterDropMonsterName ?></em>.<br />
                        Confirmez-vous ?

                        <hr>
                            
                        <form method="POST" action="deleteMonsterDrop.php">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                            <input type="hidden" class="btn btn-default form-control" name="adminMonsterDropItemId" value="<?= $adminMonsterDropItemId ?>">
                            <input type="submit" class="btn btn-default form-control" name="finalDelete" value="Je confirme">
                        </form>
                
                        <hr>

                        <form method="POST" action="index.php">
                            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
                        </form>
                        
                        <?php
                    }
                    //Si l'utilisateur n'a pas cliqué sur le bouton edit ou delete
                    else 
                    {
                        echo "Erreur: Tous les champs n'ont pas été remplis";
                    }
                }
                //Si l'objet n'exite pas
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
            echo "Erreur: Ce monstre n'existe pas";
        }
        $monsterQuery->closeCursor();
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");