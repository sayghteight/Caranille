<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalEdit
if (isset($_POST['finalEdit']))
{
    //On récupère les informations du formulaire
    $adminItemId = htmlspecialchars(addslashes($_POST['adminItemId']));
    $adminItemPicture = htmlspecialchars(addslashes($_POST['adminItemPicture']));
    $adminItemName = htmlspecialchars(addslashes($_POST['adminItemName']));
    $adminItemDescription = htmlspecialchars(addslashes($_POST['adminItemDescription']));
    $adminItemHpEffects = htmlspecialchars(addslashes($_POST['adminItemHpEffects']));
    $adminItemMpEffect = htmlspecialchars(addslashes($_POST['adminItemMpEffect']));
    $adminItemPurchasePrice = htmlspecialchars(addslashes($_POST['adminItemPurchasePrice']));
    $adminItemSalePrice = htmlspecialchars(addslashes($_POST['adminItemSalePrice']));

    //On met à jour l'équippement dans la base de donnée
    $updateItems = $bdd->prepare('UPDATE car_items 
    SET itemPicture = :adminItemPicture,
    itemName = :adminItemName,
    itemDescription = :adminItemDescription,
    itemHpEffect = :adminItemHpEffects,
    itemMpEffect = :adminItemMpEffect,
    itemPurchasePrice = :adminItemPurchasePrice,
    itemSalePrice = :adminItemSalePrice
    WHERE itemId = :adminItemId');

    $updateItems->execute([
    'adminItemPicture' => $adminItemPicture,
    'adminItemName' => $adminItemName,
    'adminItemDescription' => $adminItemDescription,
    'adminItemHpEffects' => $adminItemHpEffects,
    'adminItemMpEffect' => $adminItemMpEffect,
    'adminItemPurchasePrice' => $adminItemPurchasePrice,
    'adminItemSalePrice' => $adminItemSalePrice,
    'adminItemId' => $adminItemId]);
    $updateItems->closeCursor();
    ?>

    L'objet a bien été mit à jour

    <hr>
        
    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    <?php
}
//Si l'utilisateur n'a pas cliqué sur le bouton finalEdit
else
{
    echo "Erreur: Aucun choix effectué";
}

require_once("../html/footer.php");