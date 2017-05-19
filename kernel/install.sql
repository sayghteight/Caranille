--
-- Création de la structure de la base de donnée
--

CREATE TABLE IF NOT EXISTS `car_accounts` (
  `accountId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `accountPseudo` varchar(50) NOT NULL,
  `accountPassword` varchar(255) NOT NULL,
  `accountEmail` varchar(50) NOT NULL,
  `accountAccess` int(11) NOT NULL,
  `accountStatus` int(11) NOT NULL,
  `accountReason` varchar(100) NOT NULL,
  `accountLastConnection` datetime NOT NULL,
  `accountLastIp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_battles_monster` (
  `battleMonsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `battleMonsterAccountId` int(11) NOT NULL,
  `battleMonsterMonsterId` int(11) NOT NULL,
  `battleMonsterMonsterHp` int(11) NOT NULL,
  `battleMonsterMonsterHpTotal` int(11) NOT NULL,
  `battleMonsterMonsterMp` int(11) NOT NULL,
  `battleMonsterMonsterMpTotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_characters` (
  `characterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `characterAccountID` int(11) NOT NULL,
  `characterName` varchar(30) NOT NULL,
  `characterLevel` int(11) NOT NULL,
  `characterSex` int(11) NOT NULL,
  `characterHpMin` int(11) NOT NULL,
  `characterHpMax` int(11) NOT NULL,
  `characterHpSkillPoints` int(11) NOT NULL,
  `characterHpParchment` int(11) NOT NULL,
  `characterHpEquipments` int(11) NOT NULL,
  `characterHpTotal` int(11) NOT NULL,
  `characterMpMin` int(11) NOT NULL,
  `characterMpMax` int(11) NOT NULL,
  `characterMpSkillPoints` int(11) NOT NULL,
  `characterMpParchment` int(11) NOT NULL,
  `characterMpEquipments` int(11) NOT NULL,
  `characterMpTotal` int(11) NOT NULL,
  `characterStrength` int(11) NOT NULL,
  `characterStrengthSkillPoints` int(11) NOT NULL,
  `characterStrengthParchment` int(11) NOT NULL,
  `characterStrengthEquipments` int(11) NOT NULL,
  `characterStrengthTotal` int(11) NOT NULL,
  `characterMagic` int(11) NOT NULL,
  `characterMagicSkillPoints` int(11) NOT NULL,
  `characterMagicParchment` int(11) NOT NULL,
  `characterMagicEquipments` int(11) NOT NULL,
  `characterMagicTotal` int(11) NOT NULL,
  `characterAgility` int(11) NOT NULL,
  `characterAgilitySkillPoints` int(11) NOT NULL,
  `characterAgilityParchment` int(11) NOT NULL,
  `characterAgilityEquipments` int(11) NOT NULL,
  `characterAgilityTotal` int(11) NOT NULL,
  `characterDefense` int(11) NOT NULL,
  `characterDefenseSkillPoints` int(11) NOT NULL,
  `characterDefenseParchment` int(11) NOT NULL,
  `characterDefenseEquipments` int(11) NOT NULL,
  `characterDefenseTotal` int(11) NOT NULL,
  `characterDefenseMagic` int(11) NOT NULL,
  `characterDefenseMagicSkillPoints` int(11) NOT NULL,
  `characterDefenseMagicParchment` int(11) NOT NULL,
  `characterDefenseMagicEquipments` int(11) NOT NULL,
  `characterDefenseMagicTotal` int(11) NOT NULL,
  `characterWisdom` int(11) NOT NULL,
  `characterWisdomSkillPoints` int(11) NOT NULL,
  `characterWisdomParchment` int(11) NOT NULL,
  `characterWisdomEquipments` int(11) NOT NULL,
  `characterWisdomTotal` int(11) NOT NULL,
  `characterDefeate` int(11) NOT NULL,
  `characterVictory` int(11) NOT NULL,
  `characterExperience` int(11) NOT NULL,
  `characterExperienceTotal` int(11) NOT NULL,
  `characterSkillPoints` int(11) NOT NULL,
  `characterGold` int(11) NOT NULL,
  `characterOnBattle` int(11) NOT NULL,
  `characterEnable` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes` (
  `codeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeName` date NOT NULL,
  `codeBegins` date NOT NULL,
  `codeFinish` date NOT NULL,
  `codeAmount` int(11) NOT NULL,
  `codeAmountRemaining` int(11) NOT NULL,
  `codeType` int(11) NOT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes_gift` (
  `codeGiftId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeGiftCodeId` int(11) NOT NULL,
  `codeGiftMoney` int(11) NOT NULL,
  `codeGiftGold` int(11) NOT NULL,
  `codeGiftcharacterLevel` int(11) NOT NULL,
  `codeGiftcharacterSex` int(11) NOT NULL,
  `codeGiftcharacterSize` double NOT NULL,
  `codeGiftcharacterWeight` double NOT NULL,
  `codeGiftcharacterMoral` double NOT NULL,
  `codeGiftcharacterThirst` double NOT NULL,
  `codeGiftcharacterHunger` double NOT NULL,
  `codeGiftcharacterPee` double NOT NULL,
  `codeGiftcharacterPooh` double NOT NULL,
  `codeGiftcharacterThrowUp` double NOT NULL,
  `codeGiftcharacterHygiene` double NOT NULL,
  `codeGiftcharacterTired` double NOT NULL,
  `codeGiftcharacterFun` double NOT NULL,
  `codeGiftcharacterAffection` double NOT NULL,
  `codeGiftcharacterHappiness` double NOT NULL,
  `codeGiftcharacterKarma` double NOT NULL,
  `codeGiftcharacterHp` int(11) NOT NULL,
  `codeGiftcharacterMp` int(11) NOT NULL,
  `codeGiftcharacterStrength` int(11) NOT NULL,
  `codeGiftcharacterMagic` int(11) NOT NULL,
  `codeGiftcharacterAgility` int(11) NOT NULL,
  `codeGiftcharacterDefense` int(11) NOT NULL,
  `codeGiftcharacterDefenseMagic` int(11) NOT NULL,
  `codeGiftcharacterWisdom` int(11) NOT NULL,
  `codeGiftEquipmentId` int(11) NOT NULL,
  `codeGiftItemId` int(11) NOT NULL,
  `codeGiftParchmentId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes_used` (
  `codeUsedId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeUsedCodeId` int(11) NOT NULL,
  `codeUsedaccountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_monsters` (
  `monsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `monsterName` varchar(30) NOT NULL,
  `monsterDescription` varchar(30) NOT NULL,
  `monsterLevel` int(11) NOT NULL,
  `monsterHp` int(11) NOT NULL,
  `monsterMp` int(11) NOT NULL,
  `monsterStrength` int(11) NOT NULL,
  `monsterMagic` int(11) NOT NULL,
  `monsterAgility` int(11) NOT NULL,
  `monsterDefense` int(11) NOT NULL,
  `monsterDefenseMagic` int(11) NOT NULL,
  `monsterWisdom` int(11) NOT NULL,
  `monsterExperience` int(11) NOT NULL,
  `monsterGold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_news` 
(
  `newsId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `newsTitle` varchar(30) NOT NULL,
  `newsMessage` text NOT NULL,
  `newsAccountPseudo` varchar(15) NOT NULL,
  `newsDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Ajout des exemples dans la base de donnée
--

INSERT INTO `car_accounts` (`accountId`, `accountPseudo`, `accountPassword`, `accountEmail`, `accountAccess`, `accountStatus`, `accountReason`, `accountLastConnection`, `accountLastIp`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@admin.com', 2, 0, 'None', '2017-05-19 00:00:00', '127.0.0.1');

INSERT INTO `car_characters` (`characterId`, `characterAccountID`, `characterName`, `characterLevel`, `characterSex`, `characterHpMin`, `characterHpMax`, `characterHpSkillPoints`, `characterHpParchment`, `characterHpEquipments`, `characterHpTotal`, `characterMpMin`, `characterMpMax`, `characterMpSkillPoints`, `characterMpParchment`, `characterMpEquipments`, `characterMpTotal`, `characterStrength`, `characterStrengthSkillPoints`, `characterStrengthParchment`, `characterStrengthEquipments`, `characterStrengthTotal`, `characterMagic`, `characterMagicSkillPoints`, `characterMagicParchment`, `characterMagicEquipments`, `characterMagicTotal`, `characterAgility`, `characterAgilitySkillPoints`, `characterAgilityParchment`, `characterAgilityEquipments`, `characterAgilityTotal`, `characterDefense`, `characterDefenseSkillPoints`, `characterDefenseParchment`, `characterDefenseEquipments`, `characterDefenseTotal`, `characterDefenseMagic`, `characterDefenseMagicSkillPoints`, `characterDefenseMagicParchment`, `characterDefenseMagicEquipments`, `characterDefenseMagicTotal`, `characterWisdom`, `characterWisdomSkillPoints`, `characterWisdomParchment`, `characterWisdomEquipments`, `characterWisdomTotal`, `characterDefeate`, `characterVictory`, `characterExperience`, `characterExperienceTotal`, `characterSkillPoints`, `characterGold`, `characterOnBattle`, `characterEnable`) VALUES
(1, 1, 'Admin', 1, 1, 120, 120, 0, 0, 0, 120, 10, 10, 0, 0, 0, 10, 10, 0, 0, 0, 10, 10, 0, 0, 0, 10, 10, 0, 0, 0, 10, 10, 0, 0, 0, 10, 10, 0, 0, 0, 10, 10, 0, 0, 0, 10, 0, 0, 0, 0, 0, 0, 0, 0);

INSERT INTO `car_monsters` (`monsterId`, `monsterName`, `monsterDescription`, `monsterLevel`, `monsterHp`, `monsterMp`, `monsterStrength`, `monsterMagic`, `monsterAgility`, `monsterDefense`, `monsterDefenseMagic`, `monsterWisdom`, `monsterExperience`, `monsterGold`) VALUES
(1, 'Plop', 'Petit monstre vert', 1, 10, 10, 1, 1, 1, 1, 1, 1, 10, 10);

INSERT INTO `car_news` (`newsId`, `newsTitle`, `newsMessage`, `newsAccountPseudo`, `newsDate`) VALUES
(1, 'Installation de Caranille', 'Félicitation Caranille est bien installé vous pouvez maintenant vous connecter avec les identifiants suivant\r\n\r\nIdentifiant: admin\r\nMot de passe: Admin\r\n\r\nATTENTION: à votre première connexion veuillez immédiatement changer le mot de passe de votre compte\r\n\r\nBon RPG Making', 'admin', '2017-05-18');