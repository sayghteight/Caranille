<?php require_once("../../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['accountPseudo']) 
&& isset($_POST['accountPassword'])
&& isset($_POST['accountPasswordConfirm'])
&& isset($_POST['accountEmail'])
&& isset($_POST['characterRaceId'])
&& isset($_POST['characterSex'])
&& isset($_POST['characterName']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['characterRaceId'])
    && ctype_digit($_POST['characterSex'])
    && $_POST['characterRaceId'] >= 1
    && $_POST['characterSex'] >= 0
    && $_POST['characterSex'] <= 1)
    {
        //On récupère les valeurs du formulaire dans une variable
        $accountPseudo = htmlspecialchars(addslashes($_POST['accountPseudo']));
        $accountPassword = sha1(htmlspecialchars(addslashes($_POST['accountPassword'])));
        $accountPasswordConfirm = sha1(htmlspecialchars(addslashes($_POST['accountPasswordConfirm'])));
        $accountEmail = htmlspecialchars(addslashes($_POST['accountEmail']));
        $characterRaceId = htmlspecialchars(addslashes($_POST['characterRaceId']));
        $characterSex = htmlspecialchars(addslashes($_POST['characterSex']));
        $characterName = htmlspecialchars(addslashes($_POST['characterName']));

        //On vérifie si les deux mots de passes sont identiques
        if ($accountPassword == $accountPasswordConfirm) 
        {
            //On fait une requête pour vérifier si le pseudo est déjà utilisé
            $pseudoQuery = $bdd->prepare('SELECT * FROM car_accounts 
            WHERE accountPseudo= ?');
            $pseudoQuery->execute([$accountPseudo]);
            $pseudoRow = $pseudoQuery->rowCount();
            $pseudoQuery->closeCursor();

            //Si le pseudo est disponible
            if ($pseudoRow == 0) 
            {
                //On fait une requête pour vérifier si le nom du personnage est déjà utilisé
                $characterQuery = $bdd->prepare('SELECT * FROM car_characters 
                WHERE characterName= ?');
                $characterQuery->execute([$characterName]);
                $characterRow = $characterQuery->rowCount();
                $characterQuery->closeCursor();

                //Si le personnage existe
                if ($characterRow == 0) 
                {
                    //On fait une requête pour vérifier si le nom du personnage est déjà utilisé
                    $raceQuery = $bdd->prepare('SELECT * FROM car_races 
                    WHERE raceId = ?');
                    $raceQuery->execute([$characterRaceId]);
                    $raceRow = $raceQuery->rowCount();
                    $raceQuery->closeCursor();

                    //Si la race du personnage existe
                    if ($raceRow >= 1) 
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
                            //On Stock l'id du compte
                            $id = $accountId['accountId'];
                        }
                        $accountIdQuery->closeCursor();

                        /*
                        Add character model
                        '', //characterId
                        :accountId, //characterAccountId
                        '1', //characterRaceId
                        'http://localhost/character.png', //characterPicture
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
                        '0', //characterTownId
                        '1', //characterChapter
                        '0', //characterOnBattle
                        '1' //characterEnable
                        */

                        $addCharacter = $bdd->prepare("INSERT INTO car_characters VALUES(
                        '',
                        :accountId,
                        :characterRaceId,
                        'http://localhost/character.png',
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
                        '1',
                        '0',
                        '1'
                        )");

                        $addCharacter->execute([
                        'accountId' => $id,
                        'characterRaceId' => $characterRaceId,
                        'characterName' => $characterName,
                        'characterSex' => $characterSex]);

                        $addCharacter->closeCursor();

                        echo "Compte crée";
                    }
                    //Si la classe choisie n'existe pas
                    else
                    {
                        echo "La classe choisi n'existe pas";
                    }
                }
                //Si le nom du personnage a déjà été utilisé
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
    //Si la classe choisi n'est pas un nombre
    else
    {
         echo "La classe choisi est invalide";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>
