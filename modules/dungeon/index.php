<?php require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
?>

<h4>Liste des monstres disponible</h4> 

<?php
//On fait une recherche de tous les comptes dans la base de donnée qui ont un Id différent du notre
$monsterQueryList = $bdd->prepare("SELECT * FROM car_monsters");
$monsterQueryList->execute([$trainerId]);
$monsterQuery = $monsterQueryList->rowCount();

//On boucle la liste des résultats
if ($monsterQuery > 0)
{
    
}
else
{
    ?>
    Il n'y a aucun monstre de disponible.
    <?php
}

$monsterQueryList->closeCursor();

require_once("../../html/footer.php"); ?>