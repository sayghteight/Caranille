<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque objets qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les équipements
$itemQuery = $bdd->query("SELECT * FROM car_items
WHERE itemType = 'Item'
ORDER by itemName");
$itemRow = $itemQuery->rowCount();

//S'il existe un ou plusieurs objet(s) on affiche le menu déroulant
if ($itemRow > 0) 
{
    ?>
    
    <form method="POST" action="manageItem.php">
        Liste des objets : <select name="adminItemId" class="form-control">
                
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($item = $itemQuery->fetch())
            {
                $adminItemId = stripslashes($item['itemId']);
                $adminItemName = stripslashes($item['itemName']);
                ?>

                    <option value="<?php echo $adminItemId ?>"><?php echo "$adminItemName"; ?></option>
                    
                <?php
            }
            $itemQuery->closeCursor();
            ?>
                
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer l'objet">
    </form>
    
    <?php
}
//S'il n'y a actuellement aucun objet on prévient le joueur
else
{
    echo "Il n'y a actuellement aucun objet";
}
?>

<hr>

<form method="POST" action="addItem.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Créer un objet">
</form>

<?php require_once("../html/footer.php");