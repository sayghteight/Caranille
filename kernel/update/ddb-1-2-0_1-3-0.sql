--
-- Mise à jour de la base de donnée de Caranille 1.2.0 à 1.3.0
--

ALTER TABLE `car_characters` 
ADD `characterProspecting` INT(11) NOT NULL AFTER `characterWisdomTotal`, 
ADD `characterProspectingSkillPoints` INT(11) NOT NULL AFTER `characterProspecting`, 
ADD `characterProspectingBonus` INT(11) NOT NULL AFTER `characterProspectingSkillPoints`, 
ADD `characterProspectingEquipments` INT(11) NOT NULL AFTER `characterProspectingBonus`, 
ADD `characterProspectingGuild` INT(11) NOT NULL AFTER `characterProspectingEquipments`, 
ADD `characterProspectingTotal` INT(11) NOT NULL AFTER `characterProspectingGuild`;

ALTER TABLE `car_items`
DROP `itemLevel`,
DROP `itemLevelRequired`;

ALTER TABLE  `car_items` 
ADD  `itemLevel` INT( 11 ) NOT NULL AFTER  `itemDescription`,
ADD  `itemLevelRequired` INT( 11 ) NOT NULL AFTER  `itemLevel`,
ADD  `itemProspectingEffect` INT( 11 ) NOT NULL AFTER  `itemWisdomEffect` ;