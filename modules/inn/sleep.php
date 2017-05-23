<?php 
require_once("../../html/header.php"); 
//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }

//Si le personnage a assez d'argent pour se soigner
if ($characterGold >= 10) 
{
    $updateAccount = $bdd->prepare('UPDATE car_characters
    SET characterGold = characterGold - 10,
    characterHpMin = characterHpTotal,
    characterMpMin = characterMpTotal
    WHERE characterId = :characterId');
    $updateAccount->execute([
    'characterId' => $characterId]);
    $updateAccount->closeCursor();
    ?>
    Votre personnage à récupéré toutes ses forces !

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" value="Retour">
    </form>
<?php
}
//Si le personnage n'a pas assez d'argent pour se soigner
else
{
    ?>
    Vous n'avez pas assez d'argent
    
    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" value="Retour">
    </form>
    <?php
}

require_once("../../html/footer.php"); ?>