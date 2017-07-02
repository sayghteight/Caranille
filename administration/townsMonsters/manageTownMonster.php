<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminTownMonsterTownId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminTownMonsterTownId'])
    && $_POST['adminTownMonsterTownId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminTownMonsterTownId = htmlspecialchars(addslashes($_POST['adminTownMonsterTownId']));

        //On fait une requête pour vérifier si la ville choisi existe
        $townQuery = $bdd->prepare('SELECT * FROM car_towns 
        WHERE townId= ?');
        $townQuery->execute([$adminTownMonsterTownId]);
        $townRow = $townQuery->rowCount();

        //Si la ville existe
        if ($townRow == 1)
        {
            $townMonsterQuery = $bdd->prepare("SELECT * FROM car_monsters, car_towns, car_towns_monsters
            WHERE townMonsterMonsterId = monsterId
            AND townMonsterTownId = townId
            AND townId = ?");
            $townMonsterQuery->execute([$adminTownMonsterTownId]);
            $townMonsterRow = $townMonsterQuery->rowCount();

            //S'il existe un ou plusieurs monstre dans la ville on affiche le menu déroulant
            if ($townMonsterRow > 0) 
            {
                ?>
                
                <form method="POST" action="deleteTownMonster.php">
                    <div class="form-group row">
                        <label for="townMonsterMonsterId" class="col-2 col-form-label">Monstres présent dans la ville</label>
                        <select class="form-control" id="adminTownMonsterMonsterId" name="adminTownMonsterMonsterId">
                            
                            <?php
                            while ($townMonster = $townMonsterQuery->fetch())
                            {
                                $adminTownMonsterMonsterId = stripslashes($townMonster['monsterId']);
                                $adminTownMonsterMonsterName = stripslashes($townMonster['monsterName']);?>
                                ?>
                                    <option value="<?php echo $adminTownMonsterMonsterId ?>"><?php echo "$adminTownMonsterMonsterName"; ?></option>
                                <?php
                            }
                            $townMonsterQuery->closeCursor();
                            ?>
                            
                        </select>
                    </div>
                    <input type="hidden" name="adminTownMonsterTownId" value="<?= $adminTownMonsterTownId ?>">
                    <input type="submit" name="delete" class="btn btn-default form-control" value="Retirer le monstre">
                </form>
                
                <hr>

                <?php
            }
            $townMonsterQuery->closeCursor();

            $monsterQuery = $bdd->query("SELECT * FROM car_monsters");
            $monsterRow = $monsterQuery->rowCount();
            //S'il existe un ou plusieurs monstres on affiche le menu déroulant pour proposer au joueur d'en ajouter
            if ($monsterRow > 0) 
            {
                ?>
                
                <form method="POST" action="addTownMonster.php">
                    <div class="form-group row">
                        <label for="townMonsterMonsterId" class="col-2 col-form-label">Monstres disponible</label>
                        <select class="form-control" id="adminTownMonsterMonsterId" name="adminTownMonsterMonsterId">
                            
                            <?php
                            while ($monster = $monsterQuery->fetch())
                            {
                                $adminTownMonsterMonsterId = stripslashes($monster['monsterId']);
                                $adminTownMonsterMonsterName = stripslashes($monster['monsterName']);?>
                                ?>
                                    <option value="<?php echo $adminTownMonsterMonsterId ?>"><?php echo "$adminTownMonsterMonsterName"; ?></option>
                                <?php
                            }
                            $monsterQuery->closeCursor();
                            ?>
                            
                        </select>
                    </div>
                    <input type="hidden" name="adminTownMonsterTownId" value="<?= $adminTownMonsterTownId ?>">
                    <input type="submit" name="add" class="btn btn-default form-control" value="Ajouter le monstre">
                </form>
                
                <?php
            }
            else
            {
                ?>
                
                Il n'y a actuellement aucun monstre
                
                <?php
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
            echo "Erreur: Monstre indisponible";
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