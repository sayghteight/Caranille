<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans une ville on le redirige vers la ville
if ($characterTownId >= 1) { exit(header("Location: ../../modules/town/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//On recherche la liste des villes disponible par rapport au chapitre du joueur
$townQuery = $bdd->prepare('SELECT * FROM car_towns
WHERE townChapter <= ?');
$townQuery->execute([$characterChapter]);
//On recherche combien il y a de villes disponible
$townRow = $townQuery->rowCount();

//S'il y a au moins une ville de disponible on affiche le formulaire
if ($townRow >= 1)
{
    ?>
    
    <form method="POST" action="chooseTown.php"><div class="form-group">
        Liste des villes disponible <select name="townId" class="form-control">
            
            <?php
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($townList = $townQuery->fetch())
            {
                //on récupère les valeurs de chaque villes qu'on va ensuite mettre dans le menu déroulant
                $townId = stripslashes($townList['townId']); 
                $townName = stripslashes($townList['townName']);
                ?>
                <option value="<?php echo $townId ?>"><?php echo $townName ?></option>
                <?php
            }
            $townQuery->closeCursor();
            ?>
            
        </select>
        <input type="submit" name="enter" class="btn btn-default form-control" value="Entrer dans la ville">
    </form>
    
    <?php
}
//S'il n'y a aucune ville de disponible on affiche un message
else
{
    echo "Aucune ville disponible";
}
?>

<?php require_once("../../html/footer.php"); ?>