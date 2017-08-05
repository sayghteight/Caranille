ALTER TABLE  `car_characters` DROP  `characterTownId` ;

ALTER TABLE  `car_characters` 
ADD  `characterGuildId` INT( 11 ) NOT NULL AFTER  `characterAccountId`,
ADD  `characterTownId` INT( 11 ) NOT NULL AFTER  `characterRaceId`;

ALTER TABLE `car_items` DROP `itemType`;

ALTER TABLE  `car_items` 
ADD  `itemItemTypeId` INT( 11 ) NOT NULL AFTER  `itemId`;

CREATE TABLE IF NOT EXISTS `car_guilds` 
(
  `guildId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `guildName` varchar(30) NOT NULL,
  `guildDescription` text NOT NULL,
  `guildLevel` text NOT NULL,
  `guildHpBonus` int(11) NOT NULL,
  `guildMpBonus` int(11) NOT NULL,
  `guildStrengthBonus` int(11) NOT NULL,
  `guildMagicBonus` int(11) NOT NULL,
  `guildAgilityBonus` int(11) NOT NULL,
  `guildDefenseBonus` int(11) NOT NULL,
  `guildDefenseMagicBonus` int(11) NOT NULL,
  `guildWisdomBonus` int(11) NOT NULL,
  `guildProspectingBonus` int(11) NOT NULL,
  `guildExperience` int(11) NOT NULL,
  `guildExperienceTotal` int(11) NOT NULL,
  `guildCreationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_items_types` (
  `itemTypeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `itemTypeName` varchar(30) NOT NULL,
  `itemTypeNameShow` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `car_items_types` (`itemTypeId`, `itemTypeName`, `itemTypeNameShow`) VALUES
(1, 'Armor', 'Armure'),
(2, 'Boots', 'Bottes'),
(3, 'Gloves', 'Gants'),
(4, 'Helmet', 'Casque'),
(5, 'Weapon', 'Arme'),
(6, 'Item', 'Objet'),
(7, 'Parchment', 'Parchemin');