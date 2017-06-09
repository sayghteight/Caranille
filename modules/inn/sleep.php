<?php require_once("../../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'est pas dans une ville on le redirige vers la carte du monde
if ($characterTownId == 0) { exit(header("Location: ../../modules/map/index.php")); }
//Si il y a actuellement un combat contre un joueur on redirige le joueur vers le module battleArena
if ($battleArenaRow > 0) { exit(header("Location: ../../modules/battleArena/index.php")); }
//Si il y a actuellement un combat contre un monstre on redirige le joueur vers le module battleMonster
if ($battleMonsterRow > 0) { exit(header("Location: ../../modules/battleMonster/index.php")); }

//Si tous les champs ont bien été rempli
if (isset($_POST['sleep']))
{
    //Si le personnage a assez d'argent pour se soigner
    if ($characterGold >= $townPriceInn) 
    {
        $updateAccount = $bdd->prepare('UPDATE car_characters
        SET characterGold = characterGold - :townPriceInn,
        characterHpMin = characterHpTotal,
        characterMpMin = characterMpTotal
        WHERE characterId = :characterId');
        $updateAccount->execute([
        'townPriceInn' => $townPriceInn,
        'characterId' => $characterId]);
        $updateAccount->closeCursor();
        ?>
        Votre personnage à récupéré toutes ses forces !

        <hr>
        
        <form method="POST" action="../../modules/town/index.php">
            <input type="submit" class="btn btn-default form-control" value="Retour">
        </form>
    <?php
    }
    //Si le personnage n'a pas assez d'argent pour se soigner
    else
    {
        ?>
        Vous n'avez pas assez d'argent
        
        <hr>
        
        <form method="POST" action="../../modules/town/index.php">
            <input type="submit" class="btn btn-default form-control" value="Retour">
        </form>
        <?php
    }
}
//Si tous les champs n'ont pas été rmepli
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>