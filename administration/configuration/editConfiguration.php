<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['adminGameName'])
&& isset($_POST['adminGamePresentation'])
&& isset($_POST['adminGameExperience'])
&& isset($_POST['adminGameSkillPoint'])
&& isset($_POST['adminGameExperienceBonus'])
&& isset($_POST['adminGameGoldBonus'])
&& isset($_POST['adminGameDropBonus'])
&& isset($_POST['adminGameAccess'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminGameExperience'])
    && ctype_digit($_POST['adminGameSkillPoint'])
    && ctype_digit($_POST['adminGameExperienceBonus'])
    && ctype_digit($_POST['adminGameGoldBonus'])
    && ctype_digit($_POST['adminGameDropBonus'])
    && $_POST['adminGameExperience'] > 0
    && $_POST['adminGameSkillPoint'] >= 0
    && $_POST['adminGameExperienceBonus'] >= 0
    && $_POST['adminGameGoldBonus'] >= 0
    && $_POST['adminGameDropBonus'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminGameName = htmlspecialchars(addslashes($_POST['adminGameName']));
        $adminGamePresentation = htmlspecialchars(addslashes($_POST['adminGamePresentation']));
        $adminGameExperience = htmlspecialchars(addslashes($_POST['adminGameExperience']));
        $adminGameSkillPoint = htmlspecialchars(addslashes($_POST['adminGameSkillPoint']));
        $adminGameExperienceBonus = htmlspecialchars(addslashes($_POST['adminGameExperienceBonus']));
        $adminGameGoldBonus = htmlspecialchars(addslashes($_POST['adminGameGoldBonus']));
        $adminGameDropBonus = htmlspecialchars(addslashes($_POST['adminGameDropBonus']));
        $adminGameAccess = htmlspecialchars(addslashes($_POST['adminGameAccess']));
        
        //On met à jour la configuration dans la base de donnée
        $updateConfiguration = $bdd->prepare('UPDATE car_configuration
        SET configurationGameName = :adminGameName,
        configurationPresentation = :adminGamePresentation,
        configurationExperience = :adminGameExperience,
        configurationSkillPoint = :adminGameSkillPoint,
        configurationExperienceBonus = :adminGameExperienceBonus,
        configurationGoldBonus = :adminGameGoldBonus,
        configurationDropBonus = :adminGameDropBonus,
        configurationAccess = :adminGameAccess');

        $updateConfiguration->execute([
        'adminGameName' => $adminGameName,
        'adminGamePresentation' => $adminGamePresentation,
        'adminGameExperience' => $adminGameExperience,
        'adminGameSkillPoint' => $adminGameSkillPoint,
        'adminGameExperienceBonus' => $adminGameExperienceBonus,
        'adminGameGoldBonus' => $adminGameGoldBonus,
        'adminGameDropBonus' => $adminGameDropBonus,
        'adminGameAccess' => $adminGameAccess]);
        $updateConfiguration->closeCursor();
        ?>
        
        La configuration a bien été mise à jour

        <hr>
            
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
        
        <?php
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur: Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur: Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");
