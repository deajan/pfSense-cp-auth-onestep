CREATE TABLE IF NOT EXISTS `reg_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `familyName` varchar(150) NOT NULL,
  `surName` varchar(50) NOT NULL,
  `roomNumber` varchar(15) NOT NULL,
  `emailAddress` varchar(150) NOT NULL,
  `macAddress` varchar(25) NOT NULL,
  `ipAddress` varchar(45) NOT NULL,
  `regDate` datetime NOT NULL,
  `identificator` varchar(25) NOT NULL,
  `newsletter` boolean,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;
