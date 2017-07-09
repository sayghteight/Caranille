<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//On récupère les valeurs de chaque équipements qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les équipements
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
    
    <form method="POST" action="manageEquipment.php">
        Liste des équipements : <select name="adminItemId" class="form-control">
                
            <?php
            //On fait une boucle sur tous les résultats
            while ($equipment = $equipmentQuery->fetch())
            {
                $adminItemId = stripslashes($equipment['itemId']);
                $adminItemName = stripslashes($equipment['itemName']);
                ?>

                    <option value="<?php echo $adminItemId ?>"><?php echo "$adminItemName"; ?></option>

                <?php
            }
            $equipmentQuery->closeCursor();
            ?>
            
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer l'équipement">
    </form>
    
    <?php
}
//S'il n'y a aucun équipement on préviens le joueur
else
{
    echo "Il n'y a actuellement aucun équipement";
}
?>

<hr>

<form method="POST" action="addEquipment.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Créer un équipement">
</form>

<?php require_once("../html/footer.php");