<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterDropMonsterId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterDropMonsterId'])
    && $_POST['adminMonsterDropMonsterId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminMonsterDropMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterDropMonsterId']));

        //On fait une requête pour vérifier si le monstre choisi existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId= ?');
        $monsterQuery->execute([$adminMonsterDropMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si le monstre existe
        if ($monsterRow == 1) 
        {
            $monsterDropQuery = $bdd->prepare("SELECT * FROM car_monsters, car_items, car_monsters_drops
            WHERE monsterDropMonsterID = monsterId
            AND monsterDropItemId = itemId
            AND monsterDropMonsterId = ?
            ORDER BY itemName");
            $monsterDropQuery->execute([$adminMonsterDropMonsterId]);
            $monsterDropRow = $monsterDropQuery->rowCount();

            //S'il existe un ou plusieurs objet pour ce monstre on affiche le menu déroulant
            if ($monsterDropRow > 0) 
            {
                ?>
                <form method="POST" action="editDeleteMonsterDrop.php">
                    <div class="form-group row">
                        <label for="townMonsterMonsterId" class="col-2 col-form-label">Liste des objets/équipements du monstre</label>
                        <select class="form-control" id="adminMonsterDropItemId" name="adminMonsterDropItemId">
                        <?php
                        while ($monsterDrop = $monsterDropQuery->fetch())
                        {
                            $adminMonsterDropItemId = stripslashes($monsterDrop['itemId']);
                            $adminMonsterDropItemName = stripslashes($monsterDrop['itemName']);
                            $adminMonsterDropLuck = stripslashes($monsterDrop['monsterDropLuck']);?>
                            ?>
                                <option value="<?php echo $adminMonsterDropItemId ?>"><?php echo "$adminMonsterDropItemName (Obtention: $adminMonsterDropLuck%)"; ?></option>
                            <?php
                        }
                        $monsterDropQuery->closeCursor();
                        ?>
                        </select>
                    </div>
                    <input type="hidden" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                    <input type="submit" name="edit" class="btn btn-default form-control" value="Modifier">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer">
                </form>

                <hr>

                <?php
            }
            $monsterQuery->closeCursor();

            $itemQuery = $bdd->query("SELECT * FROM car_items
            ORDER BY itemName");
            $itemRow = $itemQuery->rowCount();
            //S'il existe un ou plusieurs monstres on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($itemRow > 0) 
            {
                ?>
                <form method="POST" action="addMonsterDrop.php">
                    <div class="form-group row">
                        <label for="adminMonsterDropItemId" class="col-2 col-form-label">Objets/équipements existant</label>
                        <select class="form-control" id="adminMonsterDropItemId" name="adminMonsterDropItemId">
                        <?php
                        while ($item = $itemQuery->fetch())
                        {
                            $adminMonsterDropItemId = stripslashes($item['itemId']);
                            $adminMonsterDropItemName = stripslashes($item['itemName']);?>
                            ?>
                                <option value="<?php echo $adminMonsterDropItemId ?>"><?php echo "$adminMonsterDropItemName"; ?></option>
                            <?php
                        }
                        $itemQuery->closeCursor();
                        ?>
                        </select>
                    </div>
                    Taux d'obtention (en pourcentage) <br> <input type="number" name="adminMonsterDropLuck" class="form-control" placeholder="Taux d'obtention (en pourcentage)" required><br /><br />
                    <input type="hidden" name="adminMonsterDropMonsterId" value="<?= $adminMonsterDropMonsterId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter cet objet/équipement">
                </form>
                <?php
            }
            else
            {
                ?>
                Il n'y a actuellement aucun objet
                <?php
            }
            ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si l'objet n'exite pas
        else
        {
            echo "Erreur: Objet indisponible";
        }
    }
    //Si l'objet choisi n'est pas un nombre
    else
    {
        echo "Erreur: Objet invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");