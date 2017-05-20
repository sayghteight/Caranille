<?php
require_once("../../html/header.php");

//Si tous les champs ont bien été rempli
if (isset($_POST['accountPseudo']) && ($_POST['accountPassword']) && ($_POST['accountPasswordConfirm']) && ($_POST['accountEmail']))
{
    //On récupère les valeurs du formulaire dans une variable
    $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
    $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));
    $accountPasswordConfirm = sha1(htmlspecialchars(addslashes($_POST['accountPasswordConfirm'])));
    $accountEmail = htmlspecialchars(addslashes($_POST['accountEmail']));
    $characterRace = htmlspecialchars(addslashes($_POST['characterRace']));
    $characterSex = htmlspecialchars(addslashes($_POST['characterSex']));
    $characterName = htmlspecialchars(addslashes($_POST['characterName']));

    //On vérifie si les deux mots de passes sont identiques
    if ($accountPassword == $accountPasswordConfirm) 
    {
        //On fait une requête pour vérifier si le pseudo est déjà utilisé
        $pseudoListQuery = $bdd->prepare('SELECT * FROM car_accounts 
        WHERE accountPseudo= ?');
        $pseudoListQuery->execute([$accountPseudo]);
        $pseudoList = $pseudoListQuery->rowCount();
        $pseudoListQuery->closeCursor();

        //Si le pseudo est disponible
        if ($pseudoList == 0) 
        {
            //On fait une requête pour vérifier si le nom du personnage est déjà utilisé
            $characterListQuery = $bdd->prepare('SELECT * FROM car_characters 
            WHERE characterName= ?');
            $characterListQuery->execute([$characterName]);
            $characterList = $characterListQuery->rowCount();
            $characterListQuery->closeCursor();

            //Si le personnage est disponible
            if ($characterList == 0) 
            {
                //Variables pour la création d'un compte
                $date = date('Y-m-d H:i:s');
                $ip = $_SERVER['REMOTE_ADDR'];
                $timeStamp = strtotime("now");

                /*
                Add account model
                '', //accountId
                :accountPseudo, //accountPseudo
                :accountPassword, //accountPassword
                :accountEmail, //accountEmail
                '0', //accountAccess
                '0', //accountStatus
                'None', //accountReason
                :accountLastAction, //accountLastAction
                :accountLastConnection, //accountLastConnection
                :accountIp, //accountLastIp
                */

                //Insertion du compte dans la base de donnée
                $addAccount = $bdd->prepare("INSERT INTO car_accounts VALUES(
                '',
                :accountPseudo,
                :accountPassword,
                :accountEmail,
                '0',
                '0',
                'None',
                :accountLastAction,
                :accountLastConnection,
                :accountIp)");

                $addAccount->execute([
                'accountPseudo' => $accountPseudo,
                'accountPassword' => $accountPassword,
                'accountEmail' => $accountEmail,
                'accountLastAction' => $date,
                'accountLastConnection' => $date,
                'accountIp' => $ip]);
                $addAccount->closeCursor();

                //Insertion du personnage dans la base de donnée
                $accountIdQuery = $bdd->prepare("SELECT * FROM car_accounts 
                WHERE accountPseudo = ?");
                $accountIdQuery->execute([$accountPseudo]);

                while ($accountId = $accountIdQuery->fetch())
                {
                    //On Stock l'Id du compte
                    $id = $accountId['accountId'];
                }

                /*
                Add character model
                '', //characterID
                :accountId, //characterAccountID
                '0', //characterRaceID
                :characterName, //characterName
                '1', //characterLevel
                :characterSex, //characterSex
                '120', //characterHpMin
                '120', //characterHpMax
                '0', //characterHpSkillPoints
                '0', //characterHpParchment
                '0', //characterHpEquipments
                '120', //characterHpTotal
                '10', //characterMpMin
                '10', //characterMpMax
                '0', //characterMpSkillPoints
                '0', //characterMpParchment
                '0', //characterMpEquipments
                '10', //characterMpTotal
                '10', //characterStrength
                '0', //characterStrengthSkillPoints
                '0', //characterStrengthParchment
                '0', //characterStrengthEquipments
                '10', //characterStrengthTotal
                '10', //characterMagic
                '0', //characterMagicSkillPoints
                '0', //characterMagicParchment
                '0', //characterMagicEquipments
                '10', //characterMagicTotal
                '10', //characterAgility
                '0', //characterAgilitySkillPoints
                '0', //characterAgilityParchment
                '0', //characterAgilityEquipments
                '10', //characterAgilityTotal
                '10', //characterDefense
                '0', //characterDefenseSkillPoints
                '0', //characterDefenseParchment
                '0', //characterDefenseEquipments
                '10', //characterDefenseTotal
                '10', //characterDefenseMagic
                '0', //characterDefenseMagicSkillPoints
                '0', //characterDefenseMagicParchment
                '0', //characterDefenseMagicEquipments
                '10', //characterDefenseMagicTotal
                '0', //characterWisdom
                '0', //characterWisdomSkillPoints
                '0', //characterWisdomParchment
                '0', //characterWisdomEquipments
                '0', //characterWisdomTotal
                '0', //characterDefeate
                '0', //characterVictory
                '0', //characterExperience
                '0', //characterExperienceTotal
                '0', //characterSkillPoints
                '0', //characterGold
                '0', //characterOnBattle
                '1' //characterEnable
                */

                $addCharacter = $bdd->prepare("INSERT INTO car_characters VALUES(
                '',
                :accountId,
                :characterRace,
                :characterName,
                '1',
                :characterSex,
                '120',
                '120',
                '0',
                '0',
                '0',
                '120',
                '10',
                '10',
                '0',
                '0',
                '0',
                '10',
                '10',
                '0',
                '0',
                '0',
                '10',
                '10',
                '0',
                '0',
                '0',
                '10',
                '10',
                '0',
                '0',
                '0',
                '10',
                '10',
                '0',
                '0',
                '0',
                '10',
                '10',
                '0',
                '0',
                '0',
                '10',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '1'
                )");

                $addCharacter->execute([
                'accountId' => $id,
                'characterRace' => $characterRace,
                'characterName' => $characterName,
                'characterSex' => $characterSex]);

                $addCharacter->closeCursor();

                echo "Compte crée";
            }
            else
            {
                echo "Ce nom de personnage est déjà utilisé";
            }
        }
        //Si le pseudo est déjà utilisé
        else 
        {
            echo "Le pseudo est déjà utilisé";
        }
    }
    //Si les deux mots de passe ne sont pas identique
    else 
    {
        echo "Les deux mots de passe ne sont pas identiques";
    }
}
//Si tous les champs n'ont pas été rmepli
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>