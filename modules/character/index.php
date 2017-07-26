<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION)) { exit(header("Location: ../../index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }
?>

<p><img src="<?php echo $characterPicture ?>" height="100" width="100"></p>

<?php echo $characterName ?><br />
Classe : <?php echo $characterRaceName ?><br />

<hr>

Niveau : <?php echo $characterLevel ?><br />
Armure : <?php echo $equipmentArmorName ?><br />
Bottes : <?php echo $equipmentBootsName ?><br />
Gants : <?php echo $equipmentGlovesName ?><br />
Casque : <?php echo $equipmentHelmetName ?><br />
Arme : <?php echo $equipmentWeaponName ?><br />
HP : <?php echo "$characterHpMin/$characterHpTotal" ?><br />
MP : <?php echo "$characterMpMin/$characterMpTotal" ?><br />
Force : <?php echo $characterStrengthTotal ?><br />
Magie : <?php echo $characterMagicTotal ?><br />
Agilité : <?php echo $characterAgilityTotal ?><br />
Défense : <?php echo $characterDefenseTotal ?><br />
Défense Magique : <?php echo $characterDefenseMagicTotal ?><br />
Sagesse : <?php echo $characterWisdomTotal ?><br />
Prospection : <?php echo $characterProspectingTotal ?><br />
Défaite(s) : <?php echo $characterDefeate ?><br />
Victoire(s) : <?php echo $characterVictory ?><br />
Expérience : <?php echo "$characterExperience/$experienceLevel" ?><br />
Prochain niveau dans : <?php echo $experienceRemaining ?><br />
Experience total : <?php echo $characterExperienceTotal ?><br />
Argent : <?php echo $characterGold ?><br />

<?php require_once("../../html/footer.php"); ?>