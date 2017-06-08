<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque ville qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de toutes les villes
$townQuery = $bdd->query("SELECT * FROM car_towns
ORDER by townName");
$townRow = $townQuery->rowCount();

//Si il existe un ou plusieurs villes on affiche le menu déroulant
if ($townRow > 0) 
{
    ?>
    <form method="POST" action="manageTownMonster.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des villes</label>
            <select class="form-control" id="adminTownMonsterTownId" name="adminTownMonsterTownId">
            <?php
            while ($town = $townQuery->fetch())
            {
                $adminTownMonsterTownId = stripslashes($town['townId']);
                $adminTownName = stripslashes($town['townName']);?>
                ?>
                    <option value="<?php echo $adminTownMonsterTownId ?>"><?php echo "$adminTownName"; ?></option>
                <?php
            }
            $townQuery->closeCursor();
            ?>
            </select>
        </div>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer la ville">
    </form>
    <?php
}
//Si il n'y a aucune ville on prévient le joueur
else
{
    ?>
    Il n'y a actuellement aucune ville
    <?php
}
?>

<form method="POST" action="addTown.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Ajouter une ville">
</form>

<?php require_once("../html/footer.php");