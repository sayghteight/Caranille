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

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId = ?');
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
                    Liste des objets/équipements du monstre : <select name="adminMonsterDropItemId" class="form-control">
                        
                    <?php
                    //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
                    while ($monsterDrop = $monsterDropQuery->fetch())
                    {
                        //On récupère les informations des objets du monstre
                        $adminMonsterDropItemId = stripslashes($monsterDrop['itemId']);
                        $adminMonsterDropItemName = stripslashes($monsterDrop['itemName']);
                        $adminMonsterDropRate = stripslashes($monsterDrop['monsterDropRate']);
                        ?>
                        <option value="<?php echo $adminMonsterDropItemId ?>"><?php echo "$adminMonsterDropItemName (Obtention: $adminMonsterDropRate%)"; ?></option>
                        <?php
                    }
                    $monsterDropQuery->closeCursor();
                    ?>
                        
                    </select>
                    <input type="hidden" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterDropMonsterId ?>">
                    <input type="submit" name="edit" class="btn btn-default form-control" value="Modifier">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer">
                </form>

                <hr>

                <?php
            }
            $monsterQuery->closeCursor();

            //On recherche la liste des objets et équipements du jeu qui ne sont pas attribué à ce monstre
            $itemQuery = $bdd->prepare("SELECT * FROM car_items
            WHERE (SELECT COUNT(*) FROM car_monsters_drops
            WHERE monsterDropMonsterId = ?
            AND monsterDropItemId = itemId) = 0
            ORDER BY itemName");
            $itemQuery->execute([$adminMonsterDropMonsterId]);
            $itemRow = $itemQuery->rowCount();
            //S'il existe un ou plusieurs monstres on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($itemRow > 0) 
            {
                ?>
                
                <form method="POST" action="addMonsterDrop.php">
                    Objets/équipements existant : <select name="adminMonsterDropItemId" class="form-control">
                            
                        <?php
                        while ($item = $itemQuery->fetch())
                        {
                            //On récupère les informations des objets
                            $adminMonsterDropItemId = stripslashes($item['itemId']);
                            $adminMonsterDropItemName = stripslashes($item['itemName']);
                            ?>
                            <option value="<?php echo $adminMonsterDropItemId ?>"><?php echo "$adminMonsterDropItemName"; ?></option>
                            <?php
                        }
                        $itemQuery->closeCursor();
                        ?>
                            
                    </select>
                    Objet visible dans le bestiaire ? : <select name="adminMonsterDropItemVisible" class="form-control">                        
                        <option value="Yes">Oui</option>
                        <option value="No">Non</option>
                    </select>
                    Taux d'obtention (en pourcentage) : <input type="number" name="adminMonsterDropRate" class="form-control" placeholder="Taux d'obtention (en pourcentage)" value="0" required>
                    Taux visible dans le bestiaire ? : <select name="adminMonsterDropRateVisible" class="form-control">
                        <option value="Yes">Oui</option>
                        <option value="No">Non</option>
                    </select>
                    <input type="hidden" name="adminMonsterDropMonsterId" value="<?php echo $adminMonsterDropMonsterId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter cet objet/équipement">
                </form>
                
                <?php
            }
            else
            {
               echo "Il n'y a actuellement aucun objet";
            }
            ?>

            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le monstre n'exite pas
        else
        {
           echo "Erreur: Ce monstre n'existe pas";
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
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");