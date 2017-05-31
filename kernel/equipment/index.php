<?php
require_once("../../kernel/config.php");

//On fait une requête pour savoir quel équipement le personnage à d'équipé
$equipmentEquipedQuery = $bdd->prepare("SELECT * FROM car_equipments, car_inventory_equipments 
WHERE equipmentId = inventoryEquipmentEquipmentId
AND inventoryEquipmentEquipped = 1
AND inventoryEquipmentCharacterId = ?");
$equipmentEquipedQuery->execute([$characterId]);

//On fait une boucle sur les résultats et on vérifie à chaque fois de quel type d'équipement il s'agit
while ($equipment = $equipmentEquipedQuery->fetch())
{
    switch ($equipment['equipmentType'])
    {
        //Si il s'agit d'une armure
        case "Armor":
            $equipmentArmorId = stripslashes($equipment['equipmentId']);
            $equipmentArmorName = stripslashes($equipment['equipmentName']);
            $equipmentArmorDescription = stripslashes($equipment['equipmentDescription']);
        break;

        //Si il s'agit de bottes
        case "Boots":
            $equipmentBootsId = stripslashes($equipment['equipmentId']);
            $equipmentBootsName = stripslashes($equipment['equipmentName']);
            $equipmentBootsDescription = stripslashes($equipment['equipmentDescription']);
        break;

        //Si il s'agit de gants
        case "Gloves":
            $equipmentGlovesId = stripslashes($equipment['equipmentId']);
            $equipmentGlovesName = stripslashes($equipment['equipmentName']);
            $equipmentGlovesDescription = stripslashes($equipment['equipmentDescription']);
        break;

        //Si il s'agit d'un casque
        case "Helmet":
            $equipmentHelmetId = stripslashes($equipment['equipmentId']);
            $equipmentHelmetName = stripslashes($equipment['equipmentName']);
            $equipmentHelmetDescription = stripslashes($equipment['equipmentDescription']);
        break;

        //Si il s'agit d'une arme
        case "Weapon":
            $equipmentWeaponId = stripslashes($equipment['equipmentId']);
            $equipmentWeaponName = stripslashes($equipment['equipmentName']);
            $equipmentWeaponDescription = stripslashes($equipment['equipmentDescription']);
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