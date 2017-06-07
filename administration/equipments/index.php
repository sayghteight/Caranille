<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//On récupère les valeurs de chaque équippements qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les équippements
$equipmentQuery = $bdd->query("SELECT * FROM car_items
WHERE (itemType = 'Armor' 
OR itemType = 'Boots' 
OR itemType = 'Gloves' 
OR itemType = 'Helmet' 
OR itemType = 'Weapon')
ORDER by itemType");
$equipmentRow = $equipmentQuery->rowCount();

//S'il existe un ou plusieurs équipements on affiche le menu déroulant
if ($equipmentRow > 0) 
{
    ?>
    <form method="POST" action="manage.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des équippements</label>
            <select class="form-control" id="adminItemId" name="adminItemId">
            <?php
            while ($equipment = $equipmentQuery->fetch())
            {
                $adminItemId = stripslashes($equipment['itemId']);
                $adminItemName = stripslashes($equipment['itemName']);?>
                ?>
                    <option value="<?php echo $adminItemId ?>"><?php echo "$adminItemName"; ?></option>
                <?php
            }
            $equipmentQuery->closeCursor();
            ?>
            </select>
        </div>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer l'équippement">
    </form>
    <?php
}
//Si il n'y a aucun équippement on préviens le joueur
else
{
    ?>
    Il n'y a actuellement aucun équippement
    <?php
}
?>

<form method="POST" action="add.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Ajouter un équippement">
</form>

<?php require_once("../html/footer.php");