<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque magasin qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les magasins
$shopQuery = $bdd->query("SELECT * FROM car_shops
ORDER by shopName");
$shopRow = $shopQuery->rowCount();

//S'il existe un ou plusieurs magasin(s) on affiche le menu déroulant
if ($shopRow > 0) 
{
    ?>
    <form method="POST" action="manageShop.php">
        <div class="form-group row">
            <label for="equipmentList" class="col-2 col-form-label">Liste des magasins</label>
            <select class="form-control" id="adminShopId" name="adminShopId">
                
                <?php
                while ($shop = $shopQuery->fetch())
                {
                    $adminShopId = stripslashes($shop['shopId']);
                    $adminShopName = stripslashes($shop['shopName']);?>
                    ?>
                        <option value="<?php echo $adminShopId ?>"><?php echo "$adminShopName"; ?></option>
                    <?php
                }
                ?>
                
            </select>
        </div>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer le magasin">
    </form>
    <?php
}
//S'il n'y a actuellement aucun magasin on prévient le joueur
else
{
    echo "Il n'y a actuellement aucun magasin";
}
$shopQuery->closeCursor();
?>

<hr>

<form method="POST" action="addShop.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Créer un magasin">
</form>

<?php require_once("../html/footer.php");