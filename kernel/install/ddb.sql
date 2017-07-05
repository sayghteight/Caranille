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

CREATE TABLE IF NOT EXISTS `car_battles` (
  `battleId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `battleCharacterId` int(11) NOT NULL,
  `battleOpponentId` int(11) NOT NULL,
  `battleType` varchar(30) NOT NULL,
  `battleOpponentHpRemaining` int(11) NOT NULL,
  `battleOpponentMpRemaining` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_bestiary` (
  `bestiaryId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bestiaryCharacterId` int(11) NOT NULL,
  `bestiaryMonsterId` int(11) NOT NULL,
  `bestiaryMonsterQuantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_chapters` 
(
  `chapterId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `chapterMonsterId` int(5) NOT NULL,
  `chapterTitle` varchar(30) NOT NULL,
  `chapterOpening` text NOT NULL,
  `chapterEnding` text NOT NULL
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
  `codeGiftitemId` int(11) NOT NULL,
  `codeGiftBonusId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_codes_used` (
  `codeUsedId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `codeUsedCodeId` int(11) NOT NULL,
  `codeUsedaccountId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_configuration` 
(
  `configurationId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `configurationGameName` varchar(30) NOT NULL,
  `configurationPresentation` text NOT NULL,
  `configurationSkillPoint` int(11) NOT NULL,
  `configurationAccess` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_inventory` 
(
  `inventoryId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `inventoryCharacterId` int(5) NOT NULL,
  `inventoryItemId` int(5) NOT NULL,
  `inventoryQuantity` int(5) NOT NULL,
  `inventoryEquipped` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_items` (
  `itemId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `itemRaceId` int(11) NOT NULL,
  `itemPicture` text NOT NULL,
  `itemType` varchar(30) NOT NULL,
  `itemLevel` int(11) NOT NULL,
  `itemLevelRequired` int(11) NOT NULL,
  `itemName` varchar(30) NOT NULL,
  `itemDescription` text NOT NULL,
  `itemHpEffect` int(11) NOT NULL,
  `itemMpEffect` int(11) NOT NULL,
  `itemStrengthEffect` int(11) NOT NULL,
  `itemMagicEffect` int(11) NOT NULL,
  `itemAgilityEffect` int(11) NOT NULL,
  `itemDefenseEffect` int(11) NOT NULL,
  `itemDefenseMagicEffect` int(11) NOT NULL,
  `itemWisdomEffect` int(11) NOT NULL,
  `itemPurchasePrice` int(11) NOT NULL,
  `itemSalePrice` int(11) NOT NULL
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

CREATE TABLE IF NOT EXISTS `car_monsters_drops`
(
  `monsterDropID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `monsterDropMonsterId` int(11) NOT NULL,
  `monsterDropItemId` int(11) NOT NULL,
  `monsterDropItemVisible` varchar(3),
  `monsterDropLuck` int(11) NOT NULL,
  `monsterDropLuckVisible` varchar(3)
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
  `raceWisdomBonus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_shops` 
(
  `shopId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `shopPicture` varchar(50) NOT NULL,
  `shopName` varchar(30) NOT NULL,
  `shopDescription` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_shops_items` 
(
  `shopItemId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `shopItemShopId` varchar(30) NOT NULL,
  `shopItemItemId` varchar(30) NOT NULL,
  `shopItemDiscount` int(5) NOT NULL
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
  `townMonsterTownId` int(10) NOT NULL,
  `townMonsterMonsterId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_towns_shops` 
(
  `townShopId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `townShopTownId` int(10) NOT NULL,
  `townShopShopId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `car_chapters`
--

INSERT INTO `car_chapters` (`chapterId`, `chapterMonsterId`, `chapterTitle`, `chapterOpening`, `chapterEnding`) VALUES
(1, 2, 'Un étrange dragon', 'Vous êtes actuellement en train de dormir quand tout à coup...\r\n\r\nA secours !!!\r\n\r\nD''énorme cri se font entendre et lorsque vous regardé à l''extérieur vous voyez apparaître la silhouette d''un énorme dragon.\r\n\r\nVotre sang ne fait qu''un tour vous prenez votre arme et vous courrez vers le dragon pour protéger le village.', 'Merci, bravo, félicitation, tels sont les mots que vous pouvez entendre de la part des villageois.\r\n\r\nLe dragon quand a lui servira de repas de fête le soir pour fêter votre victoire.');

--
-- Contenu de la table `car_configuration`
--

INSERT INTO `car_configuration` (`configurationId`, `configurationGameName`, `configurationPresentation`, `configurationSkillPoint`, `configurationAccess`) VALUES
(1, 'Caranille', 'MMORPG Officiel de Caranille', 4, 'Opened');

--
-- Contenu de la table `car_items`
--

INSERT INTO `car_items` (`itemId`, `itemRaceId`, `itemPicture`, `itemType`, `itemLevel`, `itemLevelRequired`, `itemName`, `itemDescription`, `itemHpEffect`, `itemMpEffect`, `itemStrengthEffect`, `itemMagicEffect`, `itemAgilityEffect`, `itemDefenseEffect`, `itemDefenseMagicEffect`, `itemWisdomEffect`, `itemPurchasePrice`, `itemSalePrice`) VALUES
(1, 0, 'http://localhost/item.png', 'Item', 1, 1, 'Potion', 'Cette petite fiole vous rendra 100 HP', 100, 0, 0, 0, 0, 0, 0, 0, 50, 10),
(2, 0, 'http://localhost/item.png', 'Item', 1, 1, 'Ether', 'Cette petite fiole vous rendra 10 MP', 0, 10, 0, 0, 0, 0, 0, 0, 50, 10),
(3, 0, 'http://localhost/item.png', 'Armor', 1, 1, 'Manteau de laine', 'Ce manteau vous ira a ravir.\r\nCet équipement est disponible pour toutes les classes', 10, 1, 1, 1, 1, 1, 1, 1, 100, 50),
(4, 0, 'http://localhost/item.png', 'Boots', 1, 1, 'Botte de laine', 'Ses bottes de laine sont 100% moutons.\r\nCet équipement est disponible pour toutes les classes', 10, 1, 1, 1, 1, 1, 1, 1, 100, 50),
(5, 0, 'http://localhost/item.png', 'Gloves', 1, 1, 'Gants de laine', 'Ses gants vous donneront vous protégerons des courants d''air mais juste pour les mains.\r\nCet équipement est disponible pour toutes les classes', 10, 1, 1, 1, 1, 1, 1, 1, 100, 50),
(6, 0, 'http://localhost/item.png', 'Helmet', 1, 1, 'Casque de laine', 'Ce casque vous protégera juste de la pluie.\r\nCet équipement est disponible pour toutes les classes', 10, 1, 1, 1, 1, 1, 1, 1, 100, 50),
(7, 0, 'http://localhost/item.png', 'Weapon', 1, 1, 'Boule de laine', 'Cette boule de laine pourra être lancée sur vos ennemis en leur occasionnant peu de dégâts.\r\nCet équipement est disponible pour toutes les classes', 10, 1, 1, 1, 1, 1, 1, 1, 100, 50);

--
-- Contenu de la table `car_monsters`
--

INSERT INTO `car_monsters` (`monsterId`, `monsterPicture`, `monsterName`, `monsterDescription`, `monsterLevel`, `monsterHp`, `monsterMp`, `monsterStrength`, `monsterMagic`, `monsterAgility`, `monsterDefense`, `monsterDefenseMagic`, `monsterWisdom`, `monsterExperience`, `monsterGold`) VALUES
(1, 'http://localhost/monster.png', 'Plop', 'Ce monstre se nourrit exclusivement de plante et de feuille tombé à même le sol.\r\nIl y a très longtemps celui-ci était jaune et est devenue vert de part son alimentation...\r\nMais S\\''il est devenu vert de part son alimentation pourquoi était t\\''il jaune ?', 1, 50, 10, 12, 1, 1, 1, 1, 1, 25, 25),
(2, 'http://localhost/monster.png', 'Dragon', 'Cet énorme dragon peut faire brûler des hectare de forêt en quelques minutes.', 2, 500, 1, 15, 1, 1, 1, 1, 1, 1000, 1000);

--
-- Contenu de la table `car_monsters_drops`
--

INSERT INTO `car_monsters_drops` (`monsterDropID`, `monsterDropMonsterId`, `monsterDropItemId`, `monsterDropItemVisible`, `monsterDropLuck`, `monsterDropLuckVisible`) VALUES
(1, 1, 1, 'Yes', 50, 'Yes'),
(5, 2, 2, 'No', 20, 'No');

--
-- Contenu de la table `car_news`
--

INSERT INTO `car_news` (`newsId`, `newsPicture`, `newsTitle`, `newsMessage`, `newsAccountPseudo`, `newsDate`) VALUES
(1, 'http://localhost/news.png', 'Installation de Caranille', 'Félicitation Caranille est bien installé vous pouvez maintenant vous connecter avec vos identifiants \r\n\r\nBon RPG Making', 'admin', '2017-05-18');

--
-- Contenu de la table `car_races`
--

INSERT INTO `car_races` (`raceId`, `racePicture`, `raceName`, `raceDescription`, `raceHpBonus`, `raceMpBonus`, `raceStrengthBonus`, `raceMagicBonus`, `raceAgilityBonus`, `raceDefenseBonus`, `raceDefenseMagicBonus`, `raceWisdomBonus`) VALUES
(1, 'http://localhost/race.png', 'Chevalier', 'Classe de personnage axé sur la force.', 10, 1, 2, 1, 1, 1, 1, 1),
(2, 'http://localhost/race.png', 'Mage Noir', 'Classe de personnage axé sur la magie.', 10, 1, 1, 2, 1, 1, 1, 1),
(3, 'http://localhost/race.png', 'Ninja', 'Classe de personnage axé sur l''agilité.', 10, 1, 1, 1, 2, 1, 1, 1),
(4, 'http://localhost/race.png', 'Guerrier', 'Classe de personnage axé sur la défense.', 10, 1, 1, 1, 1, 2, 1, 1),
(5, 'http://localhost/race.png', 'Mage Blanc', 'Classe de personnage axé sur la défense magique.', 10, 1, 1, 1, 1, 1, 2, 1),
(6, 'http://localhost/race.png', 'Sage', 'Classe de personnage axé sur la sagesse.', 10, 1, 1, 1, 1, 1, 1, 2);

--
-- Contenu de la table `car_shops`
--

INSERT INTO `car_shops` (`shopId`, `shopPicture`, `shopName`, `shopDescription`) VALUES
(1, 'http://localhost/shop.png', 'LittleShop', 'Le petit magasin de proximité'),
(2, 'http://localhost/shop.png', 'Au bon filon', 'Petite boutique artisanale qui vend plusieurs équipements');

--
-- Contenu de la table `car_shops_items`
--

INSERT INTO `car_shops_items` (`shopItemId`, `shopItemShopId`, `shopItemItemId`, `shopItemDiscount`) VALUES
(1, '1', '1', 0),
(2, '1', '2', 0),
(3, '2', '6', 20),
(4, '2', '3', 30);

--
-- Contenu de la table `car_towns`
--

INSERT INTO `car_towns` (`townId`, `townPicture`, `townName`, `townDescription`, `townPriceInn`, `townChapter`) VALUES
(1, 'http://localhost/town.png', 'Indicia', 'Petite ville côtière qui vie exclusivement du commerce de la pêche.', 10, 1),
(2, 'http://localhost/town.png', 'Teran', 'Petite ville rural qui vie de l''agriculture', 12, 2);

--
-- Contenu de la table `car_towns_monsters`
--

INSERT INTO `car_towns_monsters` (`townMonsterId`, `townMonsterTownId`, `townMonsterMonsterId`) VALUES
(1, 1, 1),
(2, 2, 2);

--
-- Contenu de la table `car_towns_shops`
--

INSERT INTO `car_towns_shops` (`townShopId`, `townShopTownId`, `townShopShopId`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 2, 1);
