<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['adminGameName'])
&& isset($_POST['adminGamePresentation'])
&& isset($_POST['adminGameSkillPoint'])
&& isset($_POST['adminGameAccess'])
&& isset($_POST['edit']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminGameSkillPoint'])
    && ctype_digit($_POST['adminGameAccess'])
    && $_POST['adminGameSkillPoint'] >= 0
    && $_POST['adminGameAccess'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminGameName = htmlspecialchars(addslashes($_POST['adminGameName']));
        $adminGamePresentation = htmlspecialchars(addslashes($_POST['adminGamePresentation']));
        $adminGameSkillPoint = htmlspecialchars(addslashes($_POST['adminGameSkillPoint']));
        $adminGameAccess = htmlspecialchars(addslashes($_POST['adminGameAccess']));
        
        //On met à jour l'objet dans la base de donnée
        $updateConfiguration = $bdd->prepare('UPDATE car_configuration
        SET configurationGameName = :adminGameName,
        configurationPresentation = :adminGamePresentation,
        configurationSkillPoint = :adminGameSkillPoint,
        configurationAccess = :adminGameAccess');

        $updateConfiguration->execute([
        'adminGameName' => $adminGameName,
        'adminGamePresentation' => $adminGamePresentation,
        'adminGameSkillPoint' => $adminGameSkillPoint,
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
}

require_once("../html/footer.php");
