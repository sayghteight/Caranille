<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalAdd
if (isset($_POST['adminShopPicture'])
&& isset($_POST['adminShopName'])
&& isset($_POST['adminShopDescription'])
&& isset($_POST['finalAdd']))
{
    //On récupère les informations du formulaire
    $adminShopPicture = htmlspecialchars(addslashes($_POST['adminShopPicture']));
    $adminShopName = htmlspecialchars(addslashes($_POST['adminShopName']));
    $adminShopDescription = htmlspecialchars(addslashes($_POST['adminShopDescription']));

    //On ajoute le magasin dans la base de donnée
    $addShop = $bdd->prepare("INSERT INTO car_shops VALUES(
    '',
    :adminShopPicture,
    :adminShopName,
    :adminShopDescription)");

    $addShop->execute([
    'adminShopPicture' => $adminShopPicture,
    'adminShopName' => $adminShopName,
    'adminShopDescription' => $adminShopDescription]);
    $addShop->closeCursor();
    ?>

    Le magasin a bien été crée

    <hr>
        
    <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton add
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");