<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//on récupère les valeurs de chaque monstres qu'on va ensuite mettre dans le menu déroulant
//On fait une recherche dans la base de donnée de tous les monstres
$monsterQuery = $bdd->query("SELECT * FROM car_monsters
ORDER by monsterName");
$monsterRow = $monsterQuery->rowCount();

//S'il existe un ou plusieurs monstres on affiche le menu déroulant
if ($monsterRow > 0) 
{
    ?>
    <form method="POST" action="manageMonster.php">
        Liste des monstres : <select name="adminMonsterId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($monster = $monsterQuery->fetch())
            {
                $adminMonsterId = stripslashes($monster['monsterId']);
                $adminMonsterName = stripslashes($monster['monsterName']);
                ?>
                <option value="<?php echo $adminMonsterId ?>"><?php echo "$adminMonsterName"; ?></option>
                <?php
            }
            $monsterQuery->closeCursor();
            ?>
            
        </select>
        <input type="submit" name="manage" class="btn btn-default form-control" value="Gérer le monstre">
    </form>
    
    <?php
}
//S'il n'y a aucun monstre on prévient le joueur
else
{
    echo "Il n'y a actuellement aucun monstre";
}
?>

<hr>

<form method="POST" action="addMonster.php">
    <input type="submit" class="btn btn-default form-control" name="add" value="Créer un monstre">
</form>

<?php require_once("../html/footer.php");