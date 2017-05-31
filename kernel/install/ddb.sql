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
  `accountLastAction` datetime NOT NULL,
  `accountLastConnection` datetime NOT NULL,
  `accountLastIp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_battles_arenas` (
  `battleArenaId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `battleArenaCharacterId` int(11) NOT NULL,
  `battleArenaOpponentCharacterId` int(11) NOT NULL,
  `battleArenaOpponentCharacterHpRemaining` int(11) NOT NULL,
  `battleArenaOpponentCharacterMpRemaining` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_battles_arenas` (
  `battleArenaId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `battleArenaCharacterOneId` int(11) NOT NULL,
  `battleArenaCharacterOneStep` int(11) NOT NULL,
  `battleArenaCharacterOneDamages` int(11) NOT NULL,
  `battleArenaCharacterOneHp` int(11) NOT NULL,
  `battleArenaCharacterOneMp` int(11) NOT NULL,
  `battleArenaCharacterTwoId` int(11) NOT NULL,
  `battleArenaCharacterTwoStep` int(11) NOT NULL,
  `battleArenaCharacterTwoDamages` int(11) NOT NULL,
  `battleArenaCharacterTwoHp` int(11) NOT NULL,
  `battleArenaCharacterTwoMp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_battles_monsters` (
  `battleMonsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `battleMonsterCharacterId` int(11) NOT NULL,
  `battleMonsterMonsterId` int(11) NOT NULL,
  `battleMonsterMonsterHpRemaining` int(11) NOT NULL,
  `battleMonsterMonsterMpRemaining` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_characters` (
  `characterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `characterAccountId` int(11) NOT NULL,
  `characterRaceId` int(11) NOT NULL,
  `characterPicture` varchar(50) NOT NULL,
  `characterName` varchar(30) NOT NULL,
  `characterLevel` int(11) NOT NULL,
  `characterSex` int(11) NOT NULL,
  `characterHpMin` int(11) NOT NULL,
  `characterHpMax` int(11) NOT NULL,
  `characterHpSkillPoints` int(11) NOT NULL,
  `characterHpBonus` int(11) NOT NULL,
  `characterHpEquipments` int(11) NOT NULL,
  `characterHpTotal` int(11) NOT NULL,
  `characterMpMin` int(11) NOT NULL,
  `characterMpMax` int(11) NOT NULL,
  `characterMpSkillPoints` int(11) NOT NULL,
  `characterMpBonus` int(11) NOT NULL,
  `characterMpEquipments` int(11) NOT NULL,
  `characterMpTotal` int(11) NOT NULL,
  `characterStrength` int(11) NOT NULL,
  `characterStrengthSkillPoints` int(11) NOT NULL,
  `characterStrengthBonus` int(11) NOT NULL,
  `characterStrengthEquipments` int(11) NOT NULL,
  `characterStrengthTotal` int(11) NOT NULL,
  `characterMagic` int(11) NOT NULL,
  `characterMagicSkillPoints` int(11) NOT NULL,
  `characterMagicBonus` int(11) NOT NULL,
  `characterMagicEquipments` int(11) NOT NULL,
  `characterMagicTotal` int(11) NOT NULL,
  `characterAgility` int(11) NOT NULL,
  `characterAgilitySkillPoints` int(11) NOT NULL,
  `characterAgilityBonus` int(11) NOT NULL,
  `characterAgilityEquipments` int(11) NOT NULL,
  `characterAgilityTotal` int(11) NOT NULL,
  `characterDefense` int(11) NOT NULL,
  `characterDefenseSkillPoints` int(11) NOT NULL,
  `characterDefenseBonus` int(11) NOT NULL,
  `characterDefenseEquipments` int(11) NOT NULL,
  `characterDefenseTotal` int(11) NOT NULL,
  `characterDefenseMagic` int(11) NOT NULL,
  `characterDefenseMagicSkillPoints` int(11) NOT NULL,
  `characterDefenseMagicBonus` int(11) NOT NULL,
  `characterDefenseMagicEquipments` int(11) NOT NULL,
  `characterDefenseMagicTotal` int(11) NOT NULL,
  `characterWisdom` int(11) NOT NULL,
  `characterWisdomSkillPoints` int(11) NOT NULL,
  `characterWisdomBonus` int(11) NOT NULL,
  `characterWisdomEquipments` int(11) NOT NULL,
  `characterWisdomTotal` int(11) NOT NULL,
  `characterDefeate` int(11) NOT NULL,
  `characterVictory` int(11) NOT NULL,
  `characterExperience` int(11) NOT NULL,
  `characterExperienceTotal` int(11) NOT NULL,
  `characterSkillPoints` int(11) NOT NULL,
  `characterGold` int(11) NOT NULL,
  `characterTownId` int(11) NOT NULL,
  `characterChapter` int(11) NOT NULL,
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
  `codeGiftBonusId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes_used` (
  `codeUsedId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeUsedCodeId` int(11) NOT NULL,
  `codeUsedaccountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_equipments` 
(
  `equipmentId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `equipmentRaceId` int(11) NOT NULL,
  `equipmentPicture` text NOT NULL,
  `equipmentType` varchar(30) NOT NULL,
  `equipmentLevel` int(11) NOT NULL,
  `equipmentLevel_Required` int(11) NOT NULL,
  `equipmentName` varchar(30) NOT NULL,
  `equipmentDescription` text NOT NULL,
  `equipmentHpEffect` int(11) NOT NULL,
  `equipmentMpEffect` int(11) NOT NULL,
  `equipmentStrengthEffect` int(11) NOT NULL,
  `equipmentMagicEffect` int(11) NOT NULL,
  `equipmentAgilityEffect` int(11) NOT NULL,
  `equipmentDefenseEffect` int(11) NOT NULL,
  `equipmentSagesseEffect` int(11) NOT NULL,
  `equipmentPurchasePrice` int(11) NOT NULL,
  `equipmentSalePrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_inventory_equipments` 
(
  `inventoryEquipmentId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inventoryEquipmentCharacterId` int(5) NOT NULL,
  `inventoryEquipmentEquipmentId` int(5) NOT NULL,
  `inventoryEquipmentQuantity` int(5) NOT NULL,
  `inventoryEquipmentEquipped` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_monsters` (
  `monsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `monsterPicture` varchar(50) NOT NULL,
  `monsterName` varchar(30) NOT NULL,
  `monsterDescription` text NOT NULL,
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
  `newsPicture` varchar(50) NOT NULL,
  `newsTitle` varchar(30) NOT NULL,
  `newsMessage` text NOT NULL,
  `newsAccountPseudo` varchar(15) NOT NULL,
  `newsDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_races` 
(
  `raceId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `racePicture` varchar(50) NOT NULL,
  `raceName` varchar(30) NOT NULL,
  `raceDescription` text NOT NULL,
  `raceHpBonus` int(11) NOT NULL,
  `raceMpBonus` int(11) NOT NULL,
  `raceStrengthBonus` int(11) NOT NULL,
  `raceMagicBonus` int(11) NOT NULL,
  `raceAgilityBonus` int(11) NOT NULL,
  `raceDefenseBonus` int(11) NOT NULL,
  `raceDefenseMagicBonus` int(11) NOT NULL,
  `raceWidsomBonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_towns` 
(
  `townId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `townPicture` varchar(50) NOT NULL,
  `townName` varchar(30) NOT NULL,
  `townDescription` text NOT NULL,
  `townPriceInn` int(10) NOT NULL,
  `townChapter` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_towns_monsters` 
(
  `townMonsterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `townTownId` int(10) NOT NULL,
  `townMonsterMonsterId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Ajout des exemples dans la base de donnée
--

INSERT INTO `car_equipments` (`equipmentID`, `equipmentRaceID`, `equipmentPicture`, `equipmentType`, `equipmentLevel`, `equipmentLevel_Required`, `equipmentName`, `equipmentDescription`, `equipmentHpEffect`, `equipmentMpEffect`, `equipmentStrengthEffect`, `equipmentMagicEffect`, `equipmentAgilityEffect`, `equipmentDefenseEffect`, `equipmentSagesseEffect`, `equipmentPurchasePrice`, `equipmentSalePrice`) VALUES 
(1, '1', 'http://localhost/equipment.png', 'Weapon', '1', '1', 'Epée de cuivre', 'Cette épée de cuivre est parfaite pour débuter.', '0', '0', '10', '0', '0', '0', '0', '10', '5');

INSERT INTO `car_monsters` (`monsterId`, `monsterPicture`, `monsterName`, `monsterDescription`, `monsterLevel`, `monsterHp`, `monsterMp`, `monsterStrength`, `monsterMagic`, `monsterAgility`, `monsterDefense`, `monsterDefenseMagic`, `monsterWisdom`, `monsterExperience`, `monsterGold`) VALUES
(1, 'http://localhost/monster.png', 'Plop', 'Ce monstre se nourrit exclusivement de plante et de feuille tombé à même le sol.\r\n\r\nIl y a très longtemps celui-ci était jaune et est devenue vert de part son alimentation...\r\n\r\nMais si il est devenu vert de part son alimentation pourquoi était t\'il jaune ?', 1, 10, 10, 1, 1, 1, 1, 1, 1, 10, 10),
(2, 'http://localhost/monster.png', 'Igle', 'Ce rapace est capable de voler à plus de 120km/h et peut distinguer du gibier à plus de 20 kilomètres', '2', '30', '1', '10', '1', '1', '1', '1', '1', '30', '30');

INSERT INTO `car_news` (`newsId`, `newsPicture`, `newsTitle`, `newsMessage`, `newsAccountPseudo`, `newsDate`) VALUES
(1, 'http://localhost/news.png', 'Installation de Caranille', 'Félicitation Caranille est bien installé vous pouvez maintenant vous connecter avec vos identifiants \r\n\r\nBon RPG Making', 'admin', '2017-05-18');

INSERT INTO `car_races` (`raceId`, `racePicture`, `raceName`, `raceDescription`, `raceHpBonus`, `raceMpBonus`, `raceStrengthBonus`, `raceMagicBonus`, `raceAgilityBonus`, `raceDefenseBonus`, `raceDefenseMagicBonus`, `raceWidsomBonus`) VALUES
(1, 'http://localhost/race.png', 'Guerrier', 'Classe de personnage axé sur la force et les HP', 11, 1, 2, 1, 1, 1, 1, 0),
(2, 'http://localhost/race.png', 'Mage', 'Classe de personnage axé sur la magie et les MP', 10, 2, 1, 2, 1, 1, 1, 0);

INSERT INTO `car_towns` (`townId`, `townPicture`, `townName`, `townDescription`, `townPriceInn`, `townChapter`) VALUES
(1, 'http://localhost/town.png', 'Indicia', 'Petite ville côtière qui vie exclusivement du commerce de la pêche.', 10, 1),
(2, 'http://localhost/town.png', 'Teran', 'Petite ville rural qui vie de l\'agriculture', 12, 2);

INSERT INTO `car_towns_monsters` (`townMonsterId`, `townTownId`, `townMonsterMonsterId`) VALUES
(1, 1, 1),
(2, 2, 2);
