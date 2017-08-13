--
-- Mise à jour de la base de donnée de Caranille 1.5.0 à 1.6.0
--

CREATE TABLE IF NOT EXISTS `car_notifications` 
(
  `notificationId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `notificationCharacterId` int(11) NOT NULL,
  `notificationDateTime` datetime NOT NULL,
  `notificationMessage` text NOT NULL,
  `notificationRead` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;