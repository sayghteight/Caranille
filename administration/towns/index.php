<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque ville qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de toutes les villes
$townQuery = $bdd->query("SELECT * FROM car_towns
ORDER by townName");
$townRow = $townQuery->rowCount();

//S'il existe un ou plusieurs villes on affiche le menu déroulant
if ($townRow > 0) 
{
    ?>
    
    <form method="POST" action="manageTown.php">
        Liste des villes : <select name="adminTownId" class="form-control">

            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($town = $townQuery->fetch())
            {
                $adminTownId = stripslashes($town['townId']);
                $adminTownName = stripslashes($town['townName']);
                ?>

                    <option value="<?php echo $adminTownId ?>"><?php echo "$adminTownName"; ?></option>

                <?php
            }
            ?>
        
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer la ville">
    </form>
    
    <?php
}
//S'il n'y a aucune ville on prévient le joueur
else
{
    echo "Il n'y a actuellement aucune ville";
}
$townQuery->closeCursor();
?>

<hr>

<form method="POST" action="addTown.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Créer une ville">
</form>

<?php require_once("../html/footer.php");