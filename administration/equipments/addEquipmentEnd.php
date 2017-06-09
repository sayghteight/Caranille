<?php 
require_once("../html/header.php");

//Si il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si l'utilisateur à cliqué sur le bouton finalAdd
if (isset($_POST['adminItemRaceId'])
&& isset($_POST['adminItemPicture'])
&& isset($_POST['adminItemLevel'])
&& isset($_POST['adminItemLevelRequired'])
&& isset($_POST['adminItemName'])
&& isset($_POST['adminItemDescription'])
&& isset($_POST['adminItemHpEffects'])
&& isset($_POST['adminItemMpEffect'])
&& isset($_POST['adminItemStrengthEffect'])
&& isset($_POST['adminItemMagicEffect'])
&& isset($_POST['adminItemAgilityEffect'])
&& isset($_POST['adminItemDefenseEffect'])
&& isset($_POST['adminItemDefenseMagicEffect'])
&& isset($_POST['adminItemWisdomEffect'])
&& isset($_POST['adminItemPurchasePrice'])
&& isset($_POST['adminItemSalePrice'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre
    if (ctype_digit($_POST['adminItemRaceId'])
    && ctype_digit($_POST['adminItemLevel'])
    && ctype_digit($_POST['adminItemLevelRequired'])
    && ctype_digit($_POST['adminItemHpEffects'])
    && ctype_digit($_POST['adminItemMpEffect'])
    && ctype_digit($_POST['adminItemStrengthEffect'])
    && ctype_digit($_POST['adminItemMagicEffect'])
    && ctype_digit($_POST['adminItemAgilityEffect'])
    && ctype_digit($_POST['adminItemDefenseEffect'])
    && ctype_digit($_POST['adminItemDefenseMagicEffect'])
    && ctype_digit($_POST['adminItemWisdomEffect'])
    && ctype_digit($_POST['adminItemPurchasePrice'])
    && ctype_digit($_POST['adminItemSalePrice']))
    {
        //On récupère les informations du formulaire
        $adminItemRaceId = htmlspecialchars(addslashes($_POST['adminItemRaceId']));
        $adminItemPicture = htmlspecialchars(addslashes($_POST['adminItemPicture']));
        $adminItemType = htmlspecialchars(addslashes($_POST['adminItemType']));
        $adminItemLevel = htmlspecialchars(addslashes($_POST['adminItemLevel']));
        $adminItemLevelRequired = htmlspecialchars(addslashes($_POST['adminItemLevelRequired']));
        $adminItemName = htmlspecialchars(addslashes($_POST['adminItemName']));
        $adminItemDescription = htmlspecialchars(addslashes($_POST['adminItemDescription']));
        $adminItemHpEffects = htmlspecialchars(addslashes($_POST['adminItemHpEffects']));
        $adminItemMpEffect = htmlspecialchars(addslashes($_POST['adminItemMpEffect']));
        $adminItemStrengthEffect = htmlspecialchars(addslashes($_POST['adminItemStrengthEffect']));
        $adminItemMagicEffect = htmlspecialchars(addslashes($_POST['adminItemMagicEffect']));
        $adminItemAgilityEffect = htmlspecialchars(addslashes($_POST['adminItemAgilityEffect']));
        $adminItemDefenseEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseEffect']));
        $adminItemDefenseMagicEffect = htmlspecialchars(addslashes($_POST['adminItemDefenseMagicEffect']));
        $adminItemWisdomEffect = htmlspecialchars(addslashes($_POST['adminItemWisdomEffect']));
        $adminItemPurchasePrice = htmlspecialchars(addslashes($_POST['adminItemPurchasePrice']));
        $adminItemSalePrice = htmlspecialchars(addslashes($_POST['adminItemSalePrice']));

        //On met à jour l'équippement dans la base de donnée
        $addItem = $bdd->prepare("INSERT INTO car_items VALUES(
        '',
        :adminItemRaceId,
        :adminItemPicture,
        :adminItemType,
        :adminItemLevel,
        :adminItemLevelRequired,
        :adminItemName,
        :adminItemDescription,
        :adminItemHpEffects,
        :adminItemMpEffect,
        :adminItemStrengthEffect,
        :adminItemMagicEffect,
        :adminItemAgilityEffect,
        :adminItemDefenseEffect,
        :adminItemDefenseMagicEffect,
        :adminItemWisdomEffect,
        :adminItemPurchasePrice,
        :adminItemSalePrice)");

        $addItem->execute([
        'adminItemRaceId' => $adminItemRaceId,
        'adminItemPicture' => $adminItemPicture,
        'adminItemType' => $adminItemType,
        'adminItemLevel' => $adminItemLevel,
        'adminItemLevelRequired' => $adminItemLevelRequired,
        'adminItemName' => $adminItemName,
        'adminItemDescription' => $adminItemDescription,
        'adminItemHpEffects' => $adminItemHpEffects,
        'adminItemMpEffect' => $adminItemMpEffect,
        'adminItemStrengthEffect' => $adminItemStrengthEffect,
        'adminItemMagicEffect' => $adminItemMagicEffect,
        'adminItemAgilityEffect' => $adminItemAgilityEffect,
        'adminItemDefenseEffect' => $adminItemDefenseEffect,
        'adminItemDefenseMagicEffect' => $adminItemDefenseMagicEffect,
        'adminItemWisdomEffect' => $adminItemWisdomEffect,
        'adminItemPurchasePrice' => $adminItemPurchasePrice,
        'adminItemSalePrice' => $adminItemSalePrice]);
        $addItem->closeCursor();
        ?>

        L'équippement a bien été crée

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
//Si tous les champs n'ont pas été rempli
else
{
    echo "Erreur: Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");