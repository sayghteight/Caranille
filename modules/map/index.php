<?php 
require_once("../../html/header.php");
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans une ville on le redirige vers la ville
if ($characterTownId >= 1) { exit(header("Location: ../../modules/town/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
?>

Bienvenue sur la carte du monde.<br />
Ici vous allez pouvoir choisir la ville dans laquel vous rendre<br /><br />

<?php
$townListQuery = $bdd->prepare('SELECT * FROM car_towns
WHERE townChapter <= ?');
$townListQuery->execute([$characterChapter]);
//On recherche combien il y a de villes disponible
$townList = $townListQuery->rowCount();
//Si il y a au moins une ville de disponible on affiche le formulaire
if ($townList >= 1)
{
    ?>
    <form method="POST" action="map.php">
        <div class="form-group row">
            <label for="raceList" class="col-2 col-form-label">Liste des villes</label>
            <select class="form-control" id="townList" name="townId">
            <?php
            //On fait une boucle sur tous les résultats
            while ($townList = $townListQuery->fetch())
            {
                //on récupère les valeurs de chaque villes qu'on va ensuite mettre dans le menu déroulant
                $townId = stripslashes($townList['townId']); 
                $townName = stripslashes($townList['townName']);
                ?>
                    <option value="<?php echo $townId ?>"><?php echo $townName ?></option>
                <?php
            }
            ?>
            </select>
        </div>
        <center><input type="submit" name="enter" class="btn btn-default form-control" value="Entrer dans la ville"></center>
    </form>
    <?php
}
//Si il n'y a aucune ville de disponible on affiche un message
else
{
    echo "Aucune ville disponible";
}
$townListQuery->closeCursor();
?>

<?php require_once("../../html/footer.php"); ?>