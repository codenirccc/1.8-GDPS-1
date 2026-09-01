CREATE TABLE `userLinks` (
  `extID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `accountID` int(11) NOT NULL DEFAULT '0',
  `discordID` bigint(24) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `userLinks`
  ADD PRIMARY KEY (`extID`),
  ADD UNIQUE KEY (`accountID`);

ALTER TABLE `actions_likes`
  ADD COLUMN `accountID` int(11) NOT NULL DEFAULT '0' AFTER `isLike`,
  ADD UNIQUE KEY `accountID` (`itemID`,`type`,`accountID`);