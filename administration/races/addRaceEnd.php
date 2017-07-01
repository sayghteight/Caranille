<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminRacePicture'])
&& isset($_POST['adminRaceName'])
&& isset($_POST['adminRaceDescription'])
&& isset($_POST['adminRaceHpBonus'])
&& isset($_POST['adminRaceMpBonus'])
&& isset($_POST['adminRaceStrengthBonus'])
&& isset($_POST['adminRaceMagicBonus'])
&& isset($_POST['adminRaceAgilityBonus'])
&& isset($_POST['adminRaceDefenseBonus'])
&& isset($_POST['adminRaceDefenseMagicBonus'])
&& isset($_POST['adminRaceWisdomBonus'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si l'id de la race récupéré dans le formulaire est en entier positif
    if (ctype_digit($_POST['adminRaceHpBonus'])
    && ctype_digit($_POST['adminRaceMpBonus'])
    && ctype_digit($_POST['adminRaceStrengthBonus'])
    && ctype_digit($_POST['adminRaceMagicBonus'])
    && ctype_digit($_POST['adminRaceAgilityBonus'])
    && ctype_digit($_POST['adminRaceDefenseBonus'])
    && ctype_digit($_POST['adminRaceDefenseMagicBonus'])
    && ctype_digit($_POST['adminRaceWisdomBonus'])
    && $_POST['adminRaceHpBonus'] >= 0
    && $_POST['adminRaceMpBonus'] >= 0
    && $_POST['adminRaceStrengthBonus'] >= 0
    && $_POST['adminRaceMagicBonus'] >= 0
    && $_POST['adminRaceAgilityBonus'] >= 0
    && $_POST['adminRaceDefenseBonus'] >= 0
    && $_POST['adminRaceDefenseMagicBonus'] >= 0
    && $_POST['adminRaceWisdomBonus'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminRaceId = htmlspecialchars(addslashes($_POST['adminRaceId']));
        $adminRacePicture = htmlspecialchars(addslashes($_POST['adminRacePicture']));
        $adminRaceName = htmlspecialchars(addslashes($_POST['adminRaceName']));
        $adminRaceDescription = htmlspecialchars(addslashes($_POST['adminRaceDescription']));
        $adminRaceHpBonus = htmlspecialchars(addslashes($_POST['adminRaceHpBonus']));
        $adminRaceMpBonus = htmlspecialchars(addslashes($_POST['adminRaceMpBonus']));
        $adminRaceStrengthBonus = htmlspecialchars(addslashes($_POST['adminRaceStrengthBonus']));
        $adminRaceMagicBonus = htmlspecialchars(addslashes($_POST['adminRaceMagicBonus']));
        $adminRaceAgilityBonus = htmlspecialchars(addslashes($_POST['adminRaceAgilityBonus']));
        $adminRaceDefenseBonus = htmlspecialchars(addslashes($_POST['adminRaceDefenseBonus']));
        $adminRaceDefenseMagicBonus = htmlspecialchars(addslashes($_POST['adminRaceDefenseMagicBonus']));
        $adminRaceWisdomBonus = htmlspecialchars(addslashes($_POST['adminRaceWisdomBonus']));
        
        //On ajoute la race
        $addRace = $bdd->prepare("INSERT INTO car_races VALUES(
        '',
        :adminRacePicture,
        :adminRaceName,
        :adminRaceDescription,
        :adminRaceHpBonus,
        :adminRaceMpBonus,
        :adminRaceStrengthBonus,
        :adminRaceMagicBonus,
        :adminRaceAgilityBonus,
        :adminRaceDefenseBonus,
        :adminRaceDefenseMagicBonus,
        :adminRaceWisdomBonus)");

        $addRace->execute([
        'adminRacePicture' => $adminRacePicture,
        'adminRaceName' => $adminRaceName,
        'adminRaceDescription' => $adminRaceDescription,
        'adminRaceHpBonus' => $adminRaceHpBonus,
        'adminRaceMpBonus' => $adminRaceMpBonus,
        'adminRaceStrengthBonus' => $adminRaceStrengthBonus,
        'adminRaceMagicBonus' => $adminRaceMagicBonus,
        'adminRaceAgilityBonus' => $adminRaceAgilityBonus,
        'adminRaceDefenseBonus' => $adminRaceDefenseBonus,
        'adminRaceDefenseMagicBonus' => $adminRaceDefenseMagicBonus,
        'adminRaceWisdomBonus' => $adminRaceWisdomBonus]);
        $addRace->closeCursor();
        ?>
        
        La race a bien été crée

        <hr>
            
        <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
        <?php
    }
    //Si la race choisit n'est pas un nombre
    else
    {
        echo "Erreur: La race choisie est incorrect";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");
