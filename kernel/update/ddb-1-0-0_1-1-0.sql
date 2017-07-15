--
-- Mise à jour de la base de donnée de Caranille 1.0.0 à 1.1.0
--

CREATE TABLE IF NOT EXISTS `Caranille_Chat` 
(
  `Chat_Message_ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `Chat_Character_ID` int(5) NOT NULL,
  `Chat_Message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_private_conversation` 
(
  `privateConversationId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `privateConversationCharacterOneId` int(5) NOT NULL,
  `privateConversationCharacterTwoId` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `car_private_conversation_message` 
(
  `privateConversationMessageId` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `privateConversationMessagePrivateConversationId` int(11) NOT NULL,
  `privateConversationMessageCharacterId` int(11) NOT NULL,
  `privateConversationMessageDateTime` datetime NOT NULL,
  `privateConversationMessage` text NOT NULL,
  `privateConversationMessageRead` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;