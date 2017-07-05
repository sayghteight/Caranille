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

        //On fait une requête pour vérifier si le monstre choisi existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId = ?');
        $monsterQuery->execute([$adminMonsterDropMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre existe
        if ($monsterRow == 1) 
        {
            //On récupère les informations du monstre
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterDropMonsterName = stripslashes($monster['monsterName']);
            }

            //On fait une requête pour vérifier si l'objet choisi existe
            $itemQuery = $bdd->prepare('SELECT * FROM car_items 
            WHERE itemId = ?');
            $itemQuery->execute([$adminMonsterDropItemId]);
            $itemRow = $itemQuery->rowCount();

            //Si l'objet existe
            if ($itemRow == 1) 
            {
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
                        //On récupère le taux d'obtention de l'objet/équipement
                        while ($monsterDrop = $monsterDropQuery->fetch())
                        {
                            $adminMonsterDropItemVisible = stripslashes($monsterDrop['monsterDropItemVisible']);
                            $adminMonsterDropLuck = stripslashes($monsterDrop['monsterDropLuck']);
                            $adminMonsterDropLuckVisible = stripslashes($monsterDrop['monsterDropLuckVisible']);
                        }
                        ?>
                        
                        <h4><?php echo $adminMonsterDropItemName ?></h4><br />
                        
                        <form method="POST" action="editMonsterDrop.php">
                            <div class="form-group row">
                                <label for="adminMonsterDropItemVisible" class="col-2 col-form-label">Objet visible dans le bestiaire ?</label>
                                <select class="form-control" id="adminMonsterDropItemVisible" name="adminMonsterDropItemVisible">
                                
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
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-2 col-form-label">Taux d'obtention (en pourcentage)</label>
                                <input type="number" name="adminMonsterDropLuck" class="form-control" placeholder="Taux d'obtention (en pourcentage)" value="<?php echo $adminMonsterDropLuck ?>" required>
                            </div>
                            <div class="form-group row">
                                <label for="adminMonsterDropLuckVisible" class="col-2 col-form-label">Taux visible dans le bestiaire ?</label>
                                <select class="form-control" id="adminMonsterDropLuckVisible" name="adminMonsterDropLuckVisible">
                                
                                <?php
                                switch ($adminMonsterDropLuckVisible)
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
                            </div>
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
                        Vous êtes sur le point de retirer l'objet <em><?php echo $adminMonsterDropItemName ?></em> du monstre <em><?php echo $adminMonsterDropMonsterName ?></em><br />
                        confirmez-vous ?

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
            echo "Erreur: Monstre indisponible";
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