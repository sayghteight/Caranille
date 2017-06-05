<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalAdd
if (isset($_POST['finalAdd']))
{
    //On récupère les informations du formulaire
    $adminMonsterPicture = htmlspecialchars(addslashes($_POST['adminMonsterPicture']));
    $adminMonsterName = htmlspecialchars(addslashes($_POST['adminMonsterName']));
    $adminMonsterLevel = htmlspecialchars(addslashes($_POST['adminMonsterLevel']));
    $adminMonsterDescription = htmlspecialchars(addslashes($_POST['adminMonsterDescription']));
    $adminMonsterHp = htmlspecialchars(addslashes($_POST['adminMonsterHp']));
    $adminMonsterMp = htmlspecialchars(addslashes($_POST['adminMonsterMp']));
    $adminMonsterStrength = htmlspecialchars(addslashes($_POST['adminMonsterStrength']));
    $adminMonsterMagic = htmlspecialchars(addslashes($_POST['adminMonsterMagic']));
    $adminMonsterAgility = htmlspecialchars(addslashes($_POST['adminMonsterAgility']));
    $adminMonsterDefense = htmlspecialchars(addslashes($_POST['adminMonsterDefense']));
    $adminMonsterDefenseMagic = htmlspecialchars(addslashes($_POST['adminMonsterDefenseMagic']));
    $adminMonsterWisdom = htmlspecialchars(addslashes($_POST['adminMonsterWisdom']));               
    $adminMonsterGold = htmlspecialchars(addslashes($_POST['adminMonsterGold']));
    $adminMonsterExperience = htmlspecialchars(addslashes($_POST['adminMonsterExperience']));

    //On met à jour le monstre dans la base de donnée
    $addMonster = $bdd->prepare("INSERT INTO car_monsters VALUES(
    '',
    :adminMonsterPicture,
    :adminMonsterName,
    :adminMonsterLevel,
    :adminMonsterDescription,
    :adminMonsterHp,
    :adminMonsterMp,
    :adminMonsterStrength,
    :adminMonsterMagic,
    :adminMonsterAgility,
    :adminMonsterDefense,
    :adminMonsterDefenseMagic,
    :adminMonsterWisdom,
    :adminMonsterExperience,
    :adminMonsterGold)");

    $addMonster->execute([
    'adminMonsterPicture' => $adminMonsterPicture,
    'adminMonsterName' => $adminMonsterName,
    'adminMonsterLevel' => $adminMonsterLevel,
    'adminMonsterDescription' => $adminMonsterDescription,
    'adminMonsterHp' => $adminMonsterHp,
    'adminMonsterMp' => $adminMonsterMp,
    'adminMonsterStrength' => $adminMonsterStrength,
    'adminMonsterMagic' => $adminMonsterMagic,
    'adminMonsterAgility' => $adminMonsterAgility,
    'adminMonsterDefense' => $adminMonsterDefense,
    'adminMonsterDefenseMagic' => $adminMonsterDefenseMagic,
    'adminMonsterWisdom' => $adminMonsterWisdom,
    'adminMonsterExperience' => $adminMonsterExperience,
    'adminMonsterGold' => $adminMonsterGold]);
    $addMonster->closeCursor();
    ?>

    Le monstre a bien été crée

    <hr>
        
    <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton finalAdd
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");