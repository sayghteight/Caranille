<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque monstres qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les monstres
$monsterQuery = $bdd->query("SELECT * FROM car_monsters
ORDER by monsterName");
$monsterRow = $monsterQuery->rowCount();

//Si il existe un ou plusieurs monstres on affiche le menu déroulant
if ($monsterRow > 0) 
{
    ?>
    <form method="POST" action="edit.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des monstres</label>
            <select class="form-control" id="adminMonsterId" name="adminMonsterId">
            <?php
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterId = stripslashes($monster['monsterId']);
                $adminMonsterName = stripslashes($monster['monsterName']);?>
                ?>
                    <option value="<?php echo $adminMonsterId ?>"><?php echo "$adminMonsterName"; ?></option>
                <?php
            }
            $monsterQuery->closeCursor();
            ?>
            </select>
        </div>
        <input type="submit" name="edit" class="btn btn-default form-control" value="Afficher/Modifier">
    </form>
    <?php
}
//Si il n'y a aucun monstre on prévient le joueur
else
{
    ?>
    Il n'y a actuellement aucun monstre
    <?php
}
?>

<form method="POST" action="add.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Ajouter un monstre">
</form>

<?php require_once("../html/footer.php");