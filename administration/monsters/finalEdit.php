<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['finalEdit']))
{
    //On vérifie si l'id de l'équippement choisit est correct et que le select retourne bien un nombre
    if(ctype_digit($_POST['adminMonsterId']))
    {
        //On récupère l'Id du formulaire précédent
        $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));

        //On fait une requête pour vérifier si le monstre choisit existe
        $monsterQuery = $bdd->prepare('SELECT * FROM car_monsters 
        WHERE monsterId= ?');
        $monsterQuery->execute([$adminMonsterId]);
        $monsterRow = $monsterQuery->rowCount();

        //Si l'équippement est disponible
        if ($monsterRow == 1) 
        {
            //On récupère les informations du formulaire
            $adminMonsterId = htmlspecialchars(addslashes($_POST['adminMonsterId']));
            $adminMonsterPicture = htmlspecialchars(addslashes($_POST['adminMonsterPicture']));
            $adminMonsterName = htmlspecialchars(addslashes($_POST['adminMonsterName']));
            $adminMonsterDescription = htmlspecialchars(addslashes($_POST['adminMonsterDescription']));
            $adminMonsterLevel = htmlspecialchars(addslashes($_POST['adminMonsterLevel']));
            $adminMonsterHp = htmlspecialchars(addslashes($_POST['adminMonsterHp']));
            $adminMonsterMp = htmlspecialchars(addslashes($_POST['adminMonsterMp']));
            $adminMonsterStrength = htmlspecialchars(addslashes($_POST['adminMonsterStrength']));
            $adminMonsterMagic = htmlspecialchars(addslashes($_POST['adminMonsterMagic']));
            $adminMonsterAgility = htmlspecialchars(addslashes($_POST['adminMonsterAgility']));
            $adminMonsterDefense = htmlspecialchars(addslashes($_POST['adminMonsterDefense']));
            $adminMonsterDefenseMagic = htmlspecialchars(addslashes($_POST['adminMonsterDefenseMagic']));
            $adminMonsterWisdom = htmlspecialchars(addslashes($_POST['adminMonsterWisdom']));     
            $adminMonsterExperience = htmlspecialchars(addslashes($_POST['adminMonsterExperience']));          
            $adminMonsterGold = htmlspecialchars(addslashes($_POST['adminMonsterGold']));

            //On met à jour l'équippement dans la base de donnée
            $updateMonster = $bdd->prepare('UPDATE car_monsters 
            SET monsterPicture = :adminMonsterPicture,
            monsterName = :adminMonsterName,
            monsterDescription = :adminMonsterDescription,
            monsterLevel = :adminMonsterLevel,
            monsterHp = :adminMonsterHp,
            monsterMp = :adminMonsterMp,
            monsterStrength = :adminMonsterStrength,
            monsterMagic = :adminMonsterMagic,
            monsterAgility = :adminMonsterAgility,
            monsterDefense = :adminMonsterDefense,
            monsterDefenseMagic = :adminMonsterDefenseMagic,
            monsterWisdom = :adminMonsterWisdom,
            monsterExperience = :adminMonsterExperience,
            monsterGold = :adminMonsterGold
            WHERE monsterId = :adminMonsterId');

            $updateMonster->execute([
            'adminMonsterPicture' => $adminMonsterPicture,
            'adminMonsterName' => $adminMonsterName,
            'adminMonsterDescription' => $adminMonsterDescription,
            'adminMonsterLevel' => $adminMonsterLevel,
            'adminMonsterHp' => $adminMonsterHp,
            'adminMonsterMp' => $adminMonsterMp,
            'adminMonsterStrength' => $adminMonsterStrength,
            'adminMonsterMagic' => $adminMonsterMagic,
            'adminMonsterAgility' => $adminMonsterAgility,
            'adminMonsterDefense' => $adminMonsterDefense,
            'adminMonsterDefenseMagic' => $adminMonsterDefenseMagic,
            'adminMonsterWisdom' => $adminMonsterWisdom,
            'adminMonsterExperience' => $adminMonsterExperience,
            'adminMonsterGold' => $adminMonsterGold,
            'adminMonsterId' => $adminMonsterId]);
            $updateMonster->closeCursor();
            ?>

            Le monstre a bien été mit à jour

            <hr>
                
            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            <?php
        }
        //Si le monstre n'est pas disponible
        else
        {
            echo "Erreur: Monstre indisponible";
        }
        $monsterQuery->closeCursor();
    }
    //Si le monstre choisit n'est pas un nombre
    else
    {
        echo "Erreur: Monstre invalide";
    }
}
//Si l'utilisateur n'a pas cliqué sur le bouton finalEdit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");