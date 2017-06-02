<?php
require_once("../../kernel/config.php");

//On fait une requête pour savoir quel équipement le personnage à d'équipé
$equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_items, car_inventory 
WHERE itemId = inventoryItemItemId
AND inventoryItemEquipped = 1
AND inventoryItemCharacterId = ?");
$equipmentEquipedQuery->execute([$characterId]);

//On fait une boucle sur les résultats et on vérifie à chaque fois de quel type d'équipement il s'agit
while ($equipment = $equipmentEquipedQuery->fetch())
{
    switch ($equipment['itemType'])
    {
        //Si il s'agit d'une armure
        case "Armor":
            $equipmentArmorId = stripslashes($equipment['itemId']);
            $equipmentArmorName = stripslashes($equipment['itemName']);
            $equipmentArmorDescription = stripslashes($equipment['itemDescription']);
        break;

        //Si il s'agit de bottes
        case "Boots":
            $equipmentBootsId = stripslashes($equipment['itemId']);
            $equipmentBootsName = stripslashes($equipment['itemName']);
            $equipmentBootsDescription = stripslashes($equipment['itemDescription']);
        break;

        //Si il s'agit de gants
        case "Gloves":
            $equipmentGlovesId = stripslashes($equipment['itemId']);
            $equipmentGlovesName = stripslashes($equipment['itemName']);
            $equipmentGlovesDescription = stripslashes($equipment['itemDescription']);
        break;

        //Si il s'agit d'un casque
        case "Helmet":
            $equipmentHelmetId = stripslashes($equipment['itemId']);
            $equipmentHelmetName = stripslashes($equipment['itemName']);
            $equipmentHelmetDescription = stripslashes($equipment['itemDescription']);
        break;

        //Si il s'agit d'une arme
        case "Weapon":
            $equipmentWeaponId = stripslashes($equipment['itemId']);
            $equipmentWeaponName = stripslashes($equipment['itemName']);
            $equipmentWeaponDescription = stripslashes($equipment['itemDescription']);
        break;
    }
}

//On cherche maintenant à voir quel équipement le personnage n'a pas d'équipé pour ne pas faire appel à une variable qui n'existerait pas

//Si la variable $equipmentArmorId existe pas c'est que le personnage n'en est pas équipé
if (!isset($equipmentArmorId))
{
    $equipmentArmorId = 0;
    $equipmentArmorName = "Vide";
    $equipmentArmorDescription = "";
}

//Si la variable $equipmentBootsId existe pas c'est que le personnage n'en est pas équipé
if (!isset($equipmentBootsId))
{
    $equipmentBootsId = 0;
    $equipmentBootsName = "Vide";
    $equipmentBootsDescription = "";
}

//Si la variable $equipmentGlovesId existe pas c'est que le personnage n'en est pas équipé
if (!isset($equipmentGlovesId))
{
    $equipmentGlovesId = 0;
    $equipmentGlovesName = "Vide";
    $equipmentGlovesDescription = "";
}

//Si la variable $equipmentHelmetId existe pas c'est que le personnage n'en est pas équipé
if (!isset($equipmentHelmetId))
{
    $equipmentHelmetId = 0;
    $equipmentHelmetName = "Vide";
    $equipmentHelmetDescription = "";
}

//Si la variable $equipmentWeaponId existe pas c'est que le personnage n'en est pas équipé
if (!isset($equipmentWeaponId))
{
    $equipmentWeaponId = 0;
    $equipmentWeaponName = "Vide";
    $equipmentWeaponDescription = "";
}
?>