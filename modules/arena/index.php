<?php require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($foundBattleArena > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($foundBattleMonster > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }
?>

<h4>Liste des joueurs à proximité de vous !</h4> 

<?php
//On fait une recherche de tous les comptes dans la base de donnée qui ont un Id différent du notre
$opponentQueryList = $bdd->prepare("SELECT * FROM mop_characters 
WHERE characterId != ?");
$opponentQueryList->execute([$characterId]);
$opponentQuery = $monsterQueryList->rowCount();

//Si plusieurs personnages ont été trouvé
if ($opponentQuery > 0)
{
    //On boucle la liste des résultats
    while ($opponent = $opponentQuery->fetch())
    {
        //On récupère les informations du compte
        $characterId = $opponent['characterId'];
        $characterPseudo = $opponent['characterPseudo'];
        ?>
        <form method="POST" action="arena.php">
            <input class="form-control" type="hidden" name="opponentId" required value="<?php echo "$characterId"; ?>">
            <input type="submit" name="Register" class="btn btn-success btn-lg" value="Défier">
        </form>
        <?php
    }
}
//Si aucun personnage n'a été trouvé
else
{
    ?>
    Il n'y a aucun joueur à proximité, essayez de vous approcher d'un autre joueur.
    <?php
}
$opponentQueryList->closeCursor();

require_once("../../html/footer.php"); ?>