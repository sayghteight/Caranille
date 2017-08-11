--
-- Mise à jour de la base de donnée de Caranille 1.4.0 à 1.5.0
--

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
  `guildSkillPoinst` int(11) NOT NULL,
  `guildCreationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_guilds_members` 
(
  `guildMemberId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `guildMemberCharacterId` int(11) NOT NULL,
  `guildMemberCharacterAccess` text NOT NULL,
  `guildMemberCharacterExperience` text NOT NULL,
  `guildMemberCharacterGold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_guilds_requests` 
(
  `guildRequestId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `guildRequestGuildId` int(11) NOT NULL,
  `guildRequestCharacterId` int(11) NOT NULL,
  `guildRequestMessage` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades` 
(
  `tradeId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeCharacterOneId` int(5) NOT NULL,
  `tradeCharacterTwoId` int(5) NOT NULL,
  `tradeMessage` varchar(30) NOT NULL,
  `tradeCharacterOneTradeAccepted` varchar(30) NOT NULL,
  `tradeCharacterTwoTradeAccepted` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades_items` 
(
  `tradeItemId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeItemTradeId` int(5) NOT NULL,
  `tradeItemItemId` int(5) NOT NULL,
  `tradeItemCharacterId` int(5) NOT NULL,
  `tradeItemItemQuantity` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_trades_golds` 
(
  `tradeGoldId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tradeGoldTradeId` int(5) NOT NULL,
  `tradeItemCharacterId` int(5) NOT NULL,
  `tradeGoldQuantity` int(5) NOT NULL