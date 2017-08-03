ALTER TABLE  `car_characters` DROP  `characterTownId` ;

ALTER TABLE  `car_characters` 
ADD  `characterGuildId` INT( 11 ) NOT NULL AFTER  `characterAccountId`,
ADD  `characterTownId` INT( 11 ) NOT NULL AFTER  `characterRaceId`;