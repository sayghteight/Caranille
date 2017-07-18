--
-- Mise à jour de la base de donnée de Caranille 1.1.0 à 1.2.0
--

ALTER TABLE `car_configuration` 
ADD `configurationExperienceBonus` INT(11) NOT NULL AFTER `configurationSkillPoint`, 
ADD `configurationGoldBonus` INT(11) NOT NULL AFTER `configurationExperienceBonus`, 
ADD `configurationDropBonus` INT(11) NOT NULL AFTER `configurationGoldBonus`;

CREATE TABLE IF NOT EXISTS `car_market` (
  `marketId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `marketCharacterId` int(11) NOT NULL,
  `marketItemId` int(11) NOT NULL,
  `marketSalePrice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;