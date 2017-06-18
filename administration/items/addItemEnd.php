<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminItemPicture'])
&& isset($_POST['adminItemLevelRequired'])
&& isset($_POST['adminItemName'])
&& isset($_POST['adminItemDescription'])
&& isset($_POST['adminItemHpEffects'])
&& isset($_POST['adminItemMpEffect'])
&& isset($_POST['adminItemPurchasePrice'])
&& isset($_POST['adminItemSalePrice'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminItemLevelRequired'])
    && ctype_digit($_POST['adminItemHpEffects'])
    && ctype_digit($_POST['adminItemMpEffect'])
    && ctype_digit($_POST['adminItemPurchasePrice'])
    && ctype_digit($_POST['adminItemSalePrice'])
    && $_POST['adminItemLevelRequired'] >= 1
    && $_POST['adminItemHpEffects'] >= 0
    && $_POST['adminItemMpEffect'] >= 0
    && $_POST['adminItemPurchasePrice'] >= 0
    && $_POST['adminItemSalePrice'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminItemPicture = htmlspecialchars(addslashes($_POST['adminItemPicture']));
        $adminItemLevelRequired = htmlspecialchars(addslashes($_POST['adminItemLevelRequired']));
        $adminItemName = htmlspecialchars(addslashes($_POST['adminItemName']));
        $adminItemDescription = htmlspecialchars(addslashes($_POST['adminItemDescription']));
        $adminItemHpEffects = htmlspecialchars(addslashes($_POST['adminItemHpEffects']));
        $adminItemMpEffect = htmlspecialchars(addslashes($_POST['adminItemMpEffect']));
        $adminItemPurchasePrice = htmlspecialchars(addslashes($_POST['adminItemPurchasePrice']));
        $adminItemSalePrice = htmlspecialchars(addslashes($_POST['adminItemSalePrice']));

        //On met à jour l'équippement dans la base de donnée
        $addItem = $bdd->prepare("INSERT INTO car_items VALUES(
        '',
        :adminItemPicture,
        'Item',
        :adminItemLevelRequired,
        :adminItemName,
        :adminItemDescription,
        :adminItemHpEffects,
        :adminItemMpEffect,
        '0',
        '0',
        '0',
        '0',
        '0',
        '0',
        :adminItemPurchasePrice,
        :adminItemSalePrice)");

        $addItem->execute([
        'adminItemPicture' => $adminItemPicture,
        'adminItemLevelRequired' => $adminItemLevelRequired,
        'adminItemName' => $adminItemName,
        'adminItemDescription' => $adminItemDescription,
        'adminItemHpEffects' => $adminItemHpEffects,
        'adminItemMpEffect' => $adminItemMpEffect,
        'adminItemPurchasePrice' => $adminItemPurchasePrice,
        'adminItemSalePrice' => $adminItemSalePrice]);
        $addItem->closeCursor();
        ?>

        L'objet a bien été crée

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
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");