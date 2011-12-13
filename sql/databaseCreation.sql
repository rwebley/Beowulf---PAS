-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 13, 2011 at 10:00 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.10
-- 
-- Database: `antiquities`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `abbreviations`
-- 
-- Creation: Sep 04, 2010 at 10:08 AM
-- Last update: Feb 16, 2011 at 03:45 PM
-- 

CREATE TABLE `abbreviations` (
  `id` int(11) NOT NULL auto_increment,
  `abbreviation` varchar(255) collate utf8_unicode_ci default NULL,
  `expanded` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Abbreviations in use on the database';

-- --------------------------------------------------------

-- 
-- Table structure for table `accreditedMuseums`
-- 
-- Creation: Oct 28, 2010 at 03:42 PM
-- Last update: Oct 28, 2010 at 03:42 PM
-- Last check: Oct 28, 2010 at 03:42 PM
-- 

CREATE TABLE `accreditedMuseums` (
  `id` int(11) NOT NULL auto_increment,
  `museumName` varchar(255) collate utf8_unicode_ci NOT NULL,
  `addressOne` varchar(255) collate utf8_unicode_ci NOT NULL,
  `addressTwo` varchar(255) collate utf8_unicode_ci NOT NULL,
  `town` varchar(55) collate utf8_unicode_ci NOT NULL,
  `county` varchar(55) collate utf8_unicode_ci NOT NULL,
  `postcode` varchar(15) collate utf8_unicode_ci NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `woeid` int(11) NOT NULL,
  `comments` text collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `woeid` (`woeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='A list of accredited museums that can bid for Treasure';

-- --------------------------------------------------------

-- 
-- Table structure for table `agreedTreasureValuations`
-- 
-- Creation: Nov 11, 2010 at 03:45 PM
-- Last update: Nov 11, 2010 at 03:49 PM
-- 

CREATE TABLE `agreedTreasureValuations` (
  `id` int(11) NOT NULL auto_increment,
  `treasureID` varchar(25) collate utf8_unicode_ci NOT NULL,
  `value` int(12) NOT NULL,
  `comments` text collate utf8_unicode_ci NOT NULL,
  `dateOfValuation` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Valuations for Treasure cases';

-- --------------------------------------------------------

-- 
-- Table structure for table `allentypes`
-- 
-- Creation: Nov 28, 2010 at 10:35 AM
-- Last update: Nov 28, 2010 at 10:35 AM
-- Last check: Nov 28, 2010 at 10:35 AM
-- 

CREATE TABLE `allentypes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  `updated` datetime default NULL,
  `updatedBy` int(11) default '56',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=374 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Allen Types for Iron Age coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `annotations`
-- 
-- Creation: Sep 15, 2010 at 11:55 AM
-- Last update: Sep 15, 2010 at 11:55 AM
-- 

CREATE TABLE `annotations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `createdBy` int(11) unsigned NOT NULL,
  `file_id` varchar(255) collate utf8_unicode_ci default NULL,
  `top` mediumint(8) unsigned NOT NULL,
  `left` mediumint(8) unsigned NOT NULL,
  `width` mediumint(8) unsigned NOT NULL,
  `height` mediumint(8) unsigned NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `created` datetime default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `approveReject`
-- 
-- Creation: Sep 09, 2010 at 09:13 AM
-- Last update: Dec 07, 2011 at 02:21 PM
-- 

CREATE TABLE `approveReject` (
  `id` int(11) NOT NULL auto_increment,
  `status` enum('Approved','Rejected') collate utf8_unicode_ci default NULL,
  `message` text collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=180 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Approved and rejected accounts';

-- --------------------------------------------------------

-- 
-- Table structure for table `bibliography`
-- 
-- Creation: Nov 22, 2010 at 10:31 PM
-- Last update: Dec 13, 2011 at 09:46 AM
-- Last check: Nov 22, 2010 at 10:32 PM
-- 

CREATE TABLE `bibliography` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `old_publicationID` varchar(50) collate utf8_unicode_ci default NULL,
  `findID` varchar(50) collate utf8_unicode_ci default NULL,
  `pages_plates` varchar(50) collate utf8_unicode_ci default NULL,
  `vol_no` varchar(30) collate utf8_unicode_ci default NULL,
  `reference` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `pubID` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `pubID` (`pubID`),
  KEY `findID` (`findID`),
  KEY `secuid` (`secuid`)
) ENGINE=MyISAM AUTO_INCREMENT=77818 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `bookmarks`
-- 
-- Creation: Sep 15, 2010 at 11:56 AM
-- Last update: Sep 15, 2010 at 11:56 AM
-- 

CREATE TABLE `bookmarks` (
  `id` int(4) NOT NULL auto_increment,
  `url` varchar(255) collate utf8_unicode_ci default NULL,
  `service` varchar(255) collate utf8_unicode_ci default NULL,
  `image` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(1) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Social Bookmarks';

-- --------------------------------------------------------

-- 
-- Table structure for table `categoriescoins`
-- 
-- Creation: Nov 25, 2010 at 04:40 PM
-- Last update: Nov 25, 2010 at 04:40 PM
-- Last check: Nov 25, 2010 at 04:40 PM
-- 

CREATE TABLE `categoriescoins` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `periodID` int(10) unsigned default NULL,
  `valid` enum('0','1') collate utf8_unicode_ci default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `periodID` (`periodID`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Categories for medieval coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `certaintytypes`
-- 
-- Creation: Sep 03, 2010 at 09:26 PM
-- Last update: Sep 03, 2010 at 09:26 PM
-- 

CREATE TABLE `certaintytypes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(11) unsigned default '1',
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `coinclassifications`
-- 
-- Creation: Sep 15, 2010 at 11:57 AM
-- Last update: Feb 10, 2011 at 03:40 PM
-- 

CREATE TABLE `coinclassifications` (
  `id` int(11) NOT NULL auto_increment,
  `period` tinyint(2) default NULL,
  `referenceName` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `valid` tinyint(4) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman & iron age coins classifications';

-- --------------------------------------------------------

-- 
-- Table structure for table `coincountry_origin`
-- 
-- Creation: Sep 20, 2010 at 11:58 AM
-- Last update: Sep 20, 2010 at 11:58 AM
-- 

CREATE TABLE `coincountry_origin` (
  `id` int(11) NOT NULL auto_increment,
  `country` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `createdby` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Countries of origin for coin groups';

-- --------------------------------------------------------

-- 
-- Table structure for table `coins`
-- 
-- Creation: Feb 21, 2011 at 11:04 PM
-- Last update: Dec 12, 2011 at 11:44 PM
-- Last check: Feb 21, 2011 at 11:05 PM
-- 

CREATE TABLE `coins` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `findID` varchar(50) collate utf8_unicode_ci default NULL,
  `geographyID` int(10) unsigned default NULL,
  `geography_qualifier` tinyint(1) default NULL,
  `greekstateID` int(10) unsigned default NULL,
  `ruler_id` int(11) unsigned default NULL,
  `ruler2_id` int(10) unsigned default NULL,
  `ruler2_qualifier` tinyint(1) default NULL,
  `tribe` int(3) default NULL,
  `tribe_qualifier` tinyint(1) default NULL,
  `ruler_qualifier` tinyint(10) unsigned default NULL,
  `denomination` int(4) unsigned default NULL,
  `denomination_qualifier` varchar(10) collate utf8_unicode_ci default NULL,
  `mint_id` int(11) unsigned default NULL,
  `mint_qualifier` tinyint(10) unsigned default NULL,
  `categoryID` int(10) unsigned default NULL,
  `typeID` int(10) unsigned default NULL,
  `type` text collate utf8_unicode_ci,
  `status` varchar(50) collate utf8_unicode_ci default NULL,
  `status_qualifier` tinyint(10) unsigned default NULL,
  `moneyer` varchar(50) collate utf8_unicode_ci default NULL,
  `reeceID` int(10) unsigned default NULL,
  `obverse_description` text collate utf8_unicode_ci,
  `obverse_inscription` varchar(255) collate utf8_unicode_ci default NULL,
  `initial_mark` varchar(50) collate utf8_unicode_ci default NULL,
  `reverse_description` text collate utf8_unicode_ci,
  `reverse_inscription` varchar(255) collate utf8_unicode_ci default NULL,
  `reverse_mintmark` varchar(50) collate utf8_unicode_ci default NULL,
  `revtypeID` int(5) default NULL,
  `revTypeID_qualifier` tinyint(1) default NULL,
  `degree_of_wear` varchar(50) collate utf8_unicode_ci default NULL,
  `die_axis_measurement` tinyint(2) unsigned default NULL,
  `die_axis_certainty` tinyint(4) unsigned default NULL,
  `cciNumber` varchar(8) collate utf8_unicode_ci default NULL,
  `allen_type` varchar(10) collate utf8_unicode_ci default NULL,
  `mack_type` float default NULL,
  `bmc_type` float default NULL,
  `rudd_type` float default NULL,
  `va_type` varchar(5) collate utf8_unicode_ci default NULL,
  `phase_date_1` varchar(200) collate utf8_unicode_ci default NULL,
  `phase_date_2` varchar(200) collate utf8_unicode_ci default NULL,
  `context` text collate utf8_unicode_ci,
  `depositionDate` varchar(255) collate utf8_unicode_ci default NULL,
  `numChiab` varchar(100) collate utf8_unicode_ci default NULL,
  `classification` float default NULL,
  `volume` float default NULL,
  `reference` float default NULL,
  `created` datetime default NULL,
  `createdBy` int(10) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(10) unsigned default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `tribe` (`tribe`),
  KEY `revtypeID` (`revtypeID`),
  KEY `denomination` (`denomination`),
  KEY `ruler_id` (`ruler_id`),
  KEY `ruler2_id` (`ruler2_id`),
  KEY `reeceID` (`reeceID`),
  KEY `die_axis_measurement` (`die_axis_measurement`),
  KEY `categoryID` (`categoryID`),
  KEY `greekstateID` (`greekstateID`),
  KEY `geographyID` (`geographyID`),
  KEY `allen_type` (`allen_type`),
  KEY `mack_type` (`mack_type`),
  KEY `bmc_type` (`bmc_type`),
  KEY `rudd_type` (`rudd_type`),
  KEY `va_type` (`va_type`),
  KEY `typeID` (`typeID`),
  KEY `findID` (`findID`),
  KEY `mint_id` (`mint_id`),
  KEY `createdBy` (`createdBy`),
  KEY `moneyer` (`moneyer`),
  KEY `status` (`status`),
  KEY `cciNumber` (`cciNumber`),
  FULLTEXT KEY `reverse_description` (`reverse_description`),
  FULLTEXT KEY `obverse_inscription` (`obverse_inscription`),
  FULLTEXT KEY `obverse_description` (`obverse_description`),
  FULLTEXT KEY `reverse_inscription` (`reverse_inscription`)
) ENGINE=MyISAM AUTO_INCREMENT=267854 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `coinsAudit`
-- 
-- Creation: Nov 25, 2010 at 05:46 PM
-- Last update: Dec 12, 2011 at 08:26 PM
-- Last check: Nov 25, 2010 at 05:46 PM
-- 

CREATE TABLE `coinsAudit` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `coinID` int(11) NOT NULL,
  `findID` int(11) default '0',
  `editID` varchar(25) collate utf8_unicode_ci default NULL,
  `fieldName` varchar(255) collate utf8_unicode_ci default NULL,
  `beforeValue` mediumtext collate utf8_unicode_ci,
  `afterValue` mediumtext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `coinID` (`coinID`),
  KEY `findID` (`findID`)
) ENGINE=MyISAM AUTO_INCREMENT=75677 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `coins_denomxruler`
-- 
-- Creation: Nov 25, 2010 at 05:37 PM
-- Last update: Nov 25, 2010 at 05:37 PM
-- Last check: Nov 25, 2010 at 05:37 PM
-- 

CREATE TABLE `coins_denomxruler` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `denomID` int(3) default NULL,
  `rulerID` int(10) unsigned NOT NULL default '0',
  `periodID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `denomID` (`denomID`),
  KEY `rulerID` (`rulerID`),
  KEY `periodID` (`periodID`)
) ENGINE=MyISAM AUTO_INCREMENT=1731 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Realtions between the Rulers and Denominations for coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `coins_rulers`
-- 
-- Creation: Sep 20, 2010 at 11:59 AM
-- Last update: Sep 20, 2010 at 11:59 AM
-- Last check: Sep 20, 2010 at 11:59 AM
-- 

CREATE TABLE `coins_rulers` (
  `id` int(11) NOT NULL auto_increment,
  `old_period` char(255) collate utf8_unicode_ci default NULL,
  `period` int(11) default NULL,
  `sortorder` int(11) default NULL,
  `old_sortorder` int(11) default NULL,
  `name` char(255) collate utf8_unicode_ci default NULL,
  `place` int(11) default NULL,
  `region` char(255) collate utf8_unicode_ci default NULL,
  `old_date1qual` char(255) character set latin1 default NULL,
  `date1qual` smallint(6) default NULL,
  `date1` smallint(6) default NULL,
  `old_date2qual` char(255) collate utf8_unicode_ci default NULL,
  `date2qual` smallint(6) default NULL,
  `date2` smallint(6) default NULL,
  `valid` smallint(6) default NULL,
  `created_on` datetime default NULL,
  `created_by` char(255) collate utf8_unicode_ci default NULL,
  `last_updated` datetime default NULL,
  `last_udpated_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `place` (`place`)
) ENGINE=MyISAM AUTO_INCREMENT=1058 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `coinxclass`
-- 
-- Creation: Nov 22, 2010 at 10:26 PM
-- Last update: Dec 12, 2011 at 11:56 PM
-- Last check: Nov 22, 2010 at 10:26 PM
-- 

CREATE TABLE `coinxclass` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `findID` varchar(50) collate utf8_unicode_ci default NULL,
  `classID` int(10) unsigned NOT NULL default '0',
  `vol_no` varchar(255) collate utf8_unicode_ci default NULL,
  `reference` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` varchar(11) collate utf8_unicode_ci default NULL,
  `updated` datetime default '0000-00-00 00:00:00',
  `updatedBy` int(10) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `findID` (`findID`),
  KEY `classID` (`classID`)
) ENGINE=MyISAM AUTO_INCREMENT=11529 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `comments`
-- 
-- Creation: Sep 20, 2010 at 12:01 PM
-- Last update: Dec 13, 2011 at 09:57 AM
-- Last check: Sep 06, 2011 at 02:31 PM
-- 

CREATE TABLE `comments` (
  `comment_ID` bigint(20) unsigned NOT NULL auto_increment,
  `comment_findID` int(11) default '0',
  `comment_author` tinytext collate utf8_unicode_ci,
  `comment_author_email` varchar(100) collate utf8_unicode_ci default NULL,
  `comment_author_url` varchar(200) collate utf8_unicode_ci default NULL,
  `user_ip` varchar(100) character set latin1 default NULL,
  `comment_date` datetime default '0000-00-00 00:00:00',
  `comment_date_gmt` datetime default '0000-00-00 00:00:00',
  `comment_content` text collate utf8_unicode_ci,
  `comment_approved` enum('moderation','approved','spam') collate utf8_unicode_ci default 'moderation',
  `user_agent` varchar(255) collate utf8_unicode_ci default NULL,
  `comment_type` varchar(20) collate utf8_unicode_ci default NULL,
  `comment_parent` bigint(20) default '0',
  `user_id` bigint(20) default '0',
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`comment_ID`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_post_ID` (`comment_findID`)
) ENGINE=MyISAM AUTO_INCREMENT=1203 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `completeness`
-- 
-- Creation: Sep 20, 2010 at 12:01 PM
-- Last update: Sep 20, 2010 at 12:01 PM
-- 

CREATE TABLE `completeness` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `content`
-- 
-- Creation: Nov 25, 2010 at 05:24 PM
-- Last update: Oct 20, 2011 at 11:40 PM
-- Last check: Nov 25, 2010 at 05:24 PM
-- 

CREATE TABLE `content` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `menuTitle` varchar(255) collate utf8_unicode_ci default NULL,
  `excerpt` text collate utf8_unicode_ci,
  `body` text collate utf8_unicode_ci,
  `section` varchar(55) collate utf8_unicode_ci default NULL,
  `category` varchar(55) collate utf8_unicode_ci default NULL,
  `author` int(11) default NULL,
  `frontPage` int(11) default NULL,
  `publishState` int(1) default NULL,
  `metaDescription` text collate utf8_unicode_ci,
  `metaKeywords` text collate utf8_unicode_ci,
  `slug` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `frontPage` (`frontPage`),
  KEY `author` (`author`),
  KEY `publishState` (`publishState`),
  KEY `slug` (`slug`),
  KEY `section` (`section`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=214 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Site content';

-- --------------------------------------------------------

-- 
-- Table structure for table `copyrights`
-- 
-- Creation: Sep 20, 2010 at 12:02 PM
-- Last update: Sep 01, 2011 at 11:33 AM
-- 

CREATE TABLE `copyrights` (
  `id` int(11) NOT NULL auto_increment,
  `copyright` text collate utf8_unicode_ci,
  `createdBy` int(11) default NULL,
  `created` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Image copyrights';

-- --------------------------------------------------------

-- 
-- Table structure for table `coroners`
-- 
-- Creation: Nov 25, 2010 at 06:00 PM
-- Last update: Dec 06, 2011 at 10:59 AM
-- Last check: Jul 19, 2011 at 05:30 PM
-- 

CREATE TABLE `coroners` (
  `id` int(3) NOT NULL auto_increment,
  `firstname` varchar(155) collate utf8_unicode_ci default NULL,
  `lastname` varchar(155) collate utf8_unicode_ci default NULL,
  `email` varchar(155) collate utf8_unicode_ci default NULL,
  `telephone` varchar(100) collate utf8_unicode_ci default NULL,
  `fax` varchar(100) collate utf8_unicode_ci default NULL,
  `regionID` int(3) default NULL,
  `region_name` varchar(55) collate utf8_unicode_ci default NULL,
  `address_1` varchar(255) collate utf8_unicode_ci default NULL,
  `address_2` varchar(255) collate utf8_unicode_ci default NULL,
  `town` varchar(100) collate utf8_unicode_ci default NULL,
  `postcode` varchar(100) collate utf8_unicode_ci default NULL,
  `county` varchar(100) collate utf8_unicode_ci default NULL,
  `country` varchar(155) collate utf8_unicode_ci default NULL,
  `latitude` double default NULL,
  `longitude` double default NULL,
  `woeid` int(11) default NULL,
  `createdBy` int(3) default '3',
  `created` datetime default NULL,
  `updatedBy` int(3) default '0',
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `regionID` (`regionID`),
  KEY `woeid` (`woeid`)
) ENGINE=MyISAM AUTO_INCREMENT=126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `counties`
-- 
-- Creation: Nov 25, 2010 at 03:13 PM
-- Last update: Nov 25, 2010 at 03:13 PM
-- 

CREATE TABLE `counties` (
  `ID` int(11) NOT NULL auto_increment,
  `county` char(255) collate utf8_unicode_ci default NULL,
  `regionID` int(10) unsigned default NULL,
  `valid` tinyint(4) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`),
  KEY `county` (`county`),
  KEY `valid` (`valid`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM AUTO_INCREMENT=78 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `countries`
-- 
-- Creation: Nov 25, 2010 at 10:37 AM
-- Last update: Nov 25, 2010 at 10:37 AM
-- Last check: Nov 25, 2010 at 10:37 AM
-- 

CREATE TABLE `countries` (
  `iso` char(2) collate utf8_unicode_ci NOT NULL default '',
  `name` varchar(80) collate utf8_unicode_ci default NULL,
  `printable_name` varchar(80) collate utf8_unicode_ci default NULL,
  `iso3` char(3) collate utf8_unicode_ci default NULL,
  `numcode` smallint(6) default NULL,
  PRIMARY KEY  (`iso`),
  KEY `printable_name` (`printable_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `countyToFlo`
-- 
-- Creation: May 11, 2010 at 10:59 AM
-- Last update: May 11, 2010 at 10:59 AM
-- Last check: May 11, 2010 at 10:59 AM
-- 

CREATE TABLE `countyToFlo` (
  `id` int(11) NOT NULL auto_increment,
  `institutionID` int(11) NOT NULL,
  `countyID` int(11) NOT NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `institutionID` (`institutionID`),
  KEY `countyID` (`countyID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='County to recording institutions';

-- --------------------------------------------------------

-- 
-- Table structure for table `crimeTypes`
-- 
-- Creation: Jun 15, 2010 at 01:45 PM
-- Last update: Jul 20, 2010 at 03:34 PM
-- 

CREATE TABLE `crimeTypes` (
  `id` int(3) NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `createdBy` int(11) default NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Crime typologies';

-- --------------------------------------------------------

-- 
-- Table structure for table `cultures`
-- 
-- Creation: Sep 20, 2010 at 12:04 PM
-- Last update: Dec 01, 2011 at 01:51 PM
-- 

CREATE TABLE `cultures` (
  `id` int(2) NOT NULL auto_increment,
  `term` varchar(55) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` enum('1','0') collate utf8_unicode_ci default NULL,
  `createdBy` int(3) default NULL,
  `created` datetime default NULL,
  `updatedBy` int(3) default NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `datequalifiers`
-- 
-- Creation: Sep 20, 2010 at 12:09 PM
-- Last update: Jun 30, 2011 at 01:42 PM
-- 

CREATE TABLE `datequalifiers` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` varchar(50) collate utf8_unicode_ci default NULL,
  `valid` tinyint(11) unsigned default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `decmethods`
-- 
-- Creation: Sep 20, 2010 at 12:10 PM
-- Last update: Dec 10, 2010 at 05:40 PM
-- Last check: Dec 10, 2010 at 05:40 PM
-- 

CREATE TABLE `decmethods` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` enum('1','2') collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `decstyles`
-- 
-- Creation: Sep 20, 2010 at 12:10 PM
-- Last update: Sep 20, 2010 at 12:10 PM
-- 

CREATE TABLE `decstyles` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` enum('1','2') collate utf8_unicode_ci default '1',
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `denominations`
-- 
-- Creation: Nov 25, 2010 at 03:15 PM
-- Last update: Jul 19, 2011 at 05:32 PM
-- Last check: Jul 19, 2011 at 05:32 PM
-- 

CREATE TABLE `denominations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `period` int(10) unsigned default NULL,
  `denomination` varchar(255) collate utf8_unicode_ci default NULL,
  `rarity` text collate utf8_unicode_ci,
  `description` text collate utf8_unicode_ci,
  `weight` varchar(100) collate utf8_unicode_ci default NULL,
  `diameter` varchar(100) collate utf8_unicode_ci default NULL,
  `thickness` varchar(100) collate utf8_unicode_ci default NULL,
  `design` text collate utf8_unicode_ci,
  `obverse` text collate utf8_unicode_ci,
  `notes` text collate utf8_unicode_ci,
  `old_material` varchar(255) collate utf8_unicode_ci default NULL,
  `material` int(10) unsigned default NULL,
  `valid` enum('1','0') collate utf8_unicode_ci default '1',
  `created` datetime default '0000-00-00 00:00:00',
  `createdBy` int(10) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `denomination` (`denomination`),
  KEY `period` (`period`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=557 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `denominations_rulers`
-- 
-- Creation: Sep 20, 2010 at 12:29 PM
-- Last update: Nov 25, 2011 at 06:03 PM
-- Last check: Jul 19, 2011 at 05:31 PM
-- 

CREATE TABLE `denominations_rulers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `denomination_id` int(10) unsigned default NULL,
  `ruler_id` int(10) unsigned default NULL,
  `period_id` int(10) unsigned default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  `updated` datetime default NULL,
  `updatedBy` int(11) default '56',
  PRIMARY KEY  (`id`),
  KEY `ruler_id` (`ruler_id`),
  KEY `denomination_id` (`denomination_id`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=3375 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Realtions between the Rulers and Denominations for coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `dieaxes`
-- 
-- Creation: Sep 20, 2010 at 12:28 PM
-- Last update: Sep 20, 2010 at 12:28 PM
-- 

CREATE TABLE `dieaxes` (
  `id` int(2) NOT NULL auto_increment,
  `die_axis_name` varchar(50) collate utf8_unicode_ci default NULL,
  `valid` int(1) default NULL,
  `createdBy` int(1) default NULL,
  `created` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

-- 
-- Table structure for table `discmethods`
-- 
-- Creation: Oct 26, 2011 at 03:46 PM
-- Last update: Oct 26, 2011 at 03:46 PM
-- 

CREATE TABLE `discmethods` (
  `id` int(50) unsigned NOT NULL default '0',
  `method` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` smallint(1) default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `documents`
-- 
-- Creation: Sep 20, 2010 at 01:26 PM
-- Last update: Sep 20, 2010 at 01:26 PM
-- 

CREATE TABLE `documents` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `instock` tinyint(1) NOT NULL,
  `mimetype` varchar(50) collate utf8_unicode_ci default NULL,
  `filesize` varchar(50) collate utf8_unicode_ci default NULL,
  `downloads` int(11) NOT NULL default '0',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Scheme pubications for download and reqest';

-- --------------------------------------------------------

-- 
-- Table structure for table `dynasties`
-- 
-- Creation: Sep 20, 2010 at 12:27 PM
-- Last update: Sep 23, 2010 at 04:07 PM
-- 

CREATE TABLE `dynasties` (
  `id` int(2) NOT NULL auto_increment,
  `dynasty` varchar(25) collate utf8_unicode_ci default NULL,
  `wikipedia` varchar(255) collate utf8_unicode_ci default NULL,
  `date_from` int(4) NOT NULL default '0',
  `date_to` int(4) NOT NULL default '0',
  `description` text collate utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL,
  `createdBy` int(3) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updatedBy` int(3) NOT NULL default '0',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `wikipedia` (`wikipedia`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `edm`
-- 
-- Creation: May 20, 2010 at 12:08 PM
-- Last update: May 20, 2010 at 12:08 PM
-- 

CREATE TABLE `edm` (
  `id` int(11) NOT NULL,
  `member_id` int(10) default NULL,
  `name` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Edm signatories';

-- --------------------------------------------------------

-- 
-- Table structure for table `emperors`
-- 
-- Creation: Feb 01, 2011 at 01:19 PM
-- Last update: Mar 14, 2011 at 01:49 PM
-- Last check: Feb 01, 2011 at 01:19 PM
-- 

CREATE TABLE `emperors` (
  `id` int(4) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `reeceID` int(3) default NULL,
  `pasID` int(11) default NULL,
  `date_from` varchar(5) character set latin1 default NULL,
  `date_to` varchar(5) collate utf8_unicode_ci default NULL,
  `biography` text collate utf8_unicode_ci,
  `image` varchar(100) collate utf8_unicode_ci default NULL,
  `zoomfolder` varchar(55) collate utf8_unicode_ci default NULL,
  `dynasty` int(2) default NULL,
  `murdoch` text collate utf8_unicode_ci,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pasID` (`pasID`),
  KEY `date_from` (`date_from`),
  KEY `reeceID` (`reeceID`),
  KEY `dynasty` (`dynasty`)
) ENGINE=MyISAM AUTO_INCREMENT=160 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `errorreports`
-- 
-- Creation: Sep 15, 2010 at 05:44 PM
-- Last update: Dec 12, 2011 at 07:26 PM
-- Last check: Sep 15, 2010 at 05:44 PM
-- 

CREATE TABLE `errorreports` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `comment_findID` int(11) default NULL,
  `comment_subject` text collate utf8_unicode_ci,
  `comment_author` tinytext collate utf8_unicode_ci,
  `comment_author_email` varchar(100) collate utf8_unicode_ci default NULL,
  `comment_author_url` varchar(200) collate utf8_unicode_ci default NULL,
  `user_ip` varchar(100) collate utf8_unicode_ci default NULL,
  `comment_date` datetime default NULL,
  `comment_date_gmt` datetime default NULL,
  `comment_content` text collate utf8_unicode_ci,
  `comment_karma` int(11) default NULL,
  `comment_approved` enum('1','2','spam') collate utf8_unicode_ci default '1',
  `user_agent` varchar(255) collate utf8_unicode_ci default NULL,
  `comment_type` varchar(20) collate utf8_unicode_ci default NULL,
  `comment_parent` bigint(20) default NULL,
  `user_id` bigint(20) default NULL,
  PRIMARY KEY  (`id`),
  KEY `comment_approved` (`comment_approved`),
  KEY `comment_post_ID` (`comment_findID`)
) ENGINE=MyISAM AUTO_INCREMENT=640 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `events`
-- 
-- Creation: Mar 15, 2011 at 08:50 AM
-- Last update: Dec 13, 2011 at 09:55 AM
-- Last check: Mar 15, 2011 at 08:50 AM
-- 

CREATE TABLE `events` (
  `id` mediumint(9) NOT NULL auto_increment,
  `eventTitle` varchar(255) collate utf8_unicode_ci default NULL,
  `eventDescription` text collate utf8_unicode_ci,
  `eventType` int(2) default NULL,
  `eventLocation` varchar(255) collate utf8_unicode_ci default NULL,
  `eventStartDate` date default NULL,
  `eventStartTime` time default NULL,
  `eventEndDate` date default NULL,
  `eventEndTime` time default NULL,
  `eventRegion` int(11) default NULL,
  `accessLevel` varchar(255) collate utf8_unicode_ci default 'public',
  `adultsAttend` int(11) NOT NULL,
  `childrenAttend` int(11) NOT NULL,
  `latitude` float default NULL,
  `longitude` float default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `organisation` varchar(55) character set latin1 default 'PAS',
  PRIMARY KEY  (`id`),
  KEY `organisation` (`organisation`),
  KEY `createdBy` (`createdBy`),
  KEY `eventRegion` (`eventRegion`),
  KEY `eventStartDate` (`eventStartDate`),
  KEY `eventEndDate` (`eventEndDate`),
  KEY `createdBy_2` (`createdBy`),
  KEY `eventType` (`eventType`)
) ENGINE=MyISAM AUTO_INCREMENT=703 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `eventtypes`
-- 
-- Creation: Sep 20, 2010 at 12:30 PM
-- Last update: Feb 28, 2011 at 10:48 AM
-- 

CREATE TABLE `eventtypes` (
  `id` int(2) NOT NULL auto_increment,
  `type` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of events';

-- --------------------------------------------------------

-- 
-- Table structure for table `faqs`
-- 
-- Creation: Sep 20, 2010 at 12:31 PM
-- Last update: Sep 20, 2010 at 12:31 PM
-- 

CREATE TABLE `faqs` (
  `id` int(3) NOT NULL auto_increment,
  `question` text collate utf8_unicode_ci,
  `answer` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `createdBy` int(3) default '0',
  `created` datetime default '0000-00-00 00:00:00',
  `updatedBy` int(3) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `findofnotereasons`
-- 
-- Creation: Sep 20, 2010 at 12:31 PM
-- Last update: Mar 15, 2011 at 12:12 PM
-- 

CREATE TABLE `findofnotereasons` (
  `id` int(2) NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` timestamp NULL default NULL,
  `createdBy` int(5) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(5) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `finds`
-- 
-- Creation: Jan 21, 2011 at 03:33 PM
-- Last update: Dec 13, 2011 at 09:59 AM
-- Last check: Jan 21, 2011 at 03:38 PM
-- 

CREATE TABLE `finds` (
  `id` int(11) NOT NULL auto_increment,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `old_findID` varchar(250) collate utf8_unicode_ci default NULL,
  `old_finderID` varchar(250) collate utf8_unicode_ci default NULL,
  `finderID` varchar(50) collate utf8_unicode_ci default NULL,
  `finder2ID` varchar(50) collate utf8_unicode_ci default NULL,
  `smr_ref` varchar(250) collate utf8_unicode_ci default NULL,
  `other_ref` varchar(250) collate utf8_unicode_ci default NULL,
  `datefound1qual` tinyint(1) default NULL,
  `datefound1` date default NULL,
  `datefound1flag` char(3) collate utf8_unicode_ci default NULL,
  `datefound2` date default NULL,
  `datefound2flag` char(3) collate utf8_unicode_ci default NULL,
  `datefound2qual` tinyint(1) default NULL,
  `culture` varchar(250) collate utf8_unicode_ci default NULL,
  `discmethod` tinyint(2) default NULL,
  `disccircum` varchar(250) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `objecttype` varchar(250) collate utf8_unicode_ci default NULL,
  `objecttypecert` tinyint(1) default NULL,
  `old_candidate` varchar(250) collate utf8_unicode_ci default NULL,
  `classification` varchar(250) collate utf8_unicode_ci default NULL,
  `subclass` varchar(250) collate utf8_unicode_ci default NULL,
  `inscription` varchar(255) collate utf8_unicode_ci default NULL,
  `objdate1cert` int(11) default NULL,
  `objdate1subperiod_old` varchar(250) collate utf8_unicode_ci default NULL,
  `objdate1period` tinyint(2) unsigned default NULL,
  `objdate2cert` tinyint(1) default NULL,
  `objdate2subperiod_old` varchar(250) collate utf8_unicode_ci default NULL,
  `objdate2period` tinyint(2) unsigned default NULL,
  `objdate1subperiod` tinyint(1) unsigned default NULL,
  `objdate2subperiod` tinyint(1) unsigned default NULL,
  `broadperiod` varchar(255) collate utf8_unicode_ci default NULL,
  `numdate1qual` tinyint(1) default NULL,
  `numdate1` int(11) default NULL,
  `numdate2qual` tinyint(1) default NULL,
  `numdate2` int(11) default NULL,
  `material1` tinyint(2) unsigned default NULL,
  `material2` tinyint(2) unsigned default NULL,
  `manmethod` tinyint(2) default NULL,
  `decmethod` tinyint(2) default NULL,
  `surftreat` tinyint(2) default NULL,
  `decstyle` tinyint(2) default NULL,
  `wear` tinyint(2) default NULL,
  `preservation` tinyint(2) default NULL,
  `completeness` tinyint(2) default NULL,
  `reuse` varchar(255) collate utf8_unicode_ci default NULL,
  `reuse_period` tinyint(2) default NULL,
  `length` double unsigned default NULL,
  `width` double unsigned default NULL,
  `height` double unsigned default NULL,
  `thickness` double unsigned default NULL,
  `diameter` double unsigned default NULL,
  `weight` double unsigned default NULL,
  `quantity` smallint(6) unsigned default NULL,
  `curr_loc` varchar(250) collate utf8_unicode_ci default NULL,
  `recorderID` varchar(50) collate utf8_unicode_ci default NULL,
  `identifier1ID` varchar(50) collate utf8_unicode_ci default NULL,
  `identifier2ID` varchar(50) collate utf8_unicode_ci default NULL,
  `smrrefno` varchar(250) collate utf8_unicode_ci default NULL,
  `musaccno` varchar(250) collate utf8_unicode_ci default NULL,
  `subs_action` varchar(250) collate utf8_unicode_ci default NULL,
  `notes` text collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(10) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` varchar(20) collate utf8_unicode_ci default NULL,
  `sectag` int(11) unsigned default NULL,
  `secowner` int(11) unsigned default NULL,
  `secwfstage` tinyint(1) unsigned default NULL,
  `findofnote` tinyint(3) default NULL,
  `findofnotereason` tinyint(2) default NULL,
  `treasure` tinyint(1) default NULL,
  `treasureID` varchar(15) collate utf8_unicode_ci default NULL,
  `rally` tinyint(1) default NULL,
  `rallyID` int(11) default NULL,
  `hoard` tinyint(1) default NULL,
  `hoardID` int(11) default NULL,
  `institution` varchar(10) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `objecttype` (`objecttype`),
  KEY `objdate1period` (`objdate1period`),
  KEY `objdate2period` (`objdate2period`),
  KEY `old_findID` (`old_findID`),
  KEY `updatedBy` (`updatedBy`),
  KEY `createdBy` (`createdBy`),
  KEY `rallyID` (`rallyID`),
  KEY `treasureID` (`treasureID`),
  KEY `finderID` (`finderID`),
  KEY `finder2ID` (`finder2ID`),
  KEY `hoardID` (`hoardID`),
  KEY `findofnotereason` (`findofnotereason`),
  KEY `recorderID` (`recorderID`),
  KEY `identifier1ID` (`identifier1ID`),
  KEY `broadperiod` (`broadperiod`),
  KEY `manmethod` (`manmethod`),
  KEY `decmethod` (`decmethod`),
  KEY `surftreat` (`surftreat`),
  KEY `material1` (`material1`),
  KEY `material2` (`material2`),
  KEY `preservation` (`preservation`),
  KEY `secuid` (`secuid`),
  KEY `quantity` (`quantity`),
  KEY `other_ref` (`other_ref`),
  KEY `findofnote` (`findofnote`),
  KEY `secwfstage` (`secwfstage`),
  KEY `created` (`created`),
  KEY `identifier2ID` (`identifier2ID`),
  KEY `completeness` (`completeness`),
  KEY `discmethod` (`discmethod`),
  KEY `institution` (`institution`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `classification` (`classification`)
) ENGINE=MyISAM AUTO_INCREMENT=477428 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `finds2myresearch`
-- 
-- Creation: Sep 20, 2010 at 12:32 PM
-- Last update: Sep 20, 2010 at 12:32 PM
-- 

CREATE TABLE `finds2myresearch` (
  `id` int(11) NOT NULL auto_increment,
  `findID` varchar(50) collate utf8_unicode_ci default NULL,
  `researchID` varchar(50) collate utf8_unicode_ci default NULL,
  `createdBy` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Link finds to research catalogues';

-- --------------------------------------------------------

-- 
-- Table structure for table `finds_audit`
-- 
-- Creation: Oct 16, 2010 at 11:14 AM
-- Last update: Dec 13, 2011 at 09:54 AM
-- Last check: Oct 16, 2010 at 11:14 AM
-- 

CREATE TABLE `finds_audit` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `findID` int(11) default '0',
  `editID` varchar(25) collate utf8_unicode_ci default NULL,
  `fieldName` varchar(255) collate utf8_unicode_ci default NULL,
  `beforeValue` mediumtext collate utf8_unicode_ci,
  `afterValue` mediumtext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `findID` (`findID`)
) ENGINE=MyISAM AUTO_INCREMENT=464350 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `finds_images`
-- 
-- Creation: Mar 04, 2011 at 02:34 PM
-- Last update: Dec 13, 2011 at 09:58 AM
-- Last check: Mar 04, 2011 at 02:34 PM
-- 

CREATE TABLE `finds_images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `image_id` varchar(50) collate utf8_unicode_ci NOT NULL,
  `find_id` varchar(50) collate utf8_unicode_ci NOT NULL,
  `created` datetime default NULL,
  `createdBy` int(10) unsigned default '0',
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `image_id` (`image_id`),
  KEY `find_id` (`find_id`)
) ENGINE=MyISAM AUTO_INCREMENT=351604 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `finds_publications`
-- 
-- Creation: Sep 20, 2010 at 01:27 PM
-- Last update: Sep 20, 2010 at 01:27 PM
-- Last check: Sep 20, 2010 at 01:27 PM
-- 

CREATE TABLE `finds_publications` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `publication_id` varchar(50) collate utf8_unicode_ci default NULL,
  `old_publicationID` varchar(50) collate utf8_unicode_ci default NULL,
  `find_id` varchar(50) collate utf8_unicode_ci default NULL,
  `pages_plates` varchar(50) collate utf8_unicode_ci default NULL,
  `vol_no` varchar(30) collate utf8_unicode_ci default NULL,
  `reference` varchar(100) collate utf8_unicode_ci default NULL,
  `created_on` datetime default NULL,
  `created_by` int(11) default NULL,
  `last_updated` datetime default NULL,
  `last_updated_by` int(11) default NULL,
  `exported` tinyint(4) default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `secreplica` varchar(30) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `IDX_secuid` (`secuid`),
  KEY `IDX_findID` (`find_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29366 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `findspots`
-- 
-- Creation: Oct 19, 2011 at 10:33 AM
-- Last update: Dec 13, 2011 at 09:44 AM
-- Last check: Oct 19, 2011 at 10:34 AM
-- 

CREATE TABLE `findspots` (
  `id` int(11) NOT NULL auto_increment,
  `findID` varchar(50) collate utf8_unicode_ci default NULL,
  `old_findspotid` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `address` text collate utf8_unicode_ci,
  `postcode` varchar(20) collate utf8_unicode_ci default NULL,
  `accuracy` smallint(2) unsigned default NULL,
  `gridlen` tinyint(2) default NULL,
  `gridref` varchar(18) collate utf8_unicode_ci default NULL,
  `fourFigure` varchar(6) collate utf8_unicode_ci default NULL,
  `gridrefsrc` tinyint(10) unsigned default NULL,
  `gridrefcert` tinyint(3) unsigned default NULL,
  `easting` int(11) default NULL,
  `northing` int(11) default NULL,
  `declong` double default NULL,
  `declat` double default NULL,
  `woeid` int(11) default NULL,
  `elevation` double default NULL,
  `knownas` varchar(255) collate utf8_unicode_ci default NULL,
  `disccircum` text collate utf8_unicode_ci,
  `comments` text collate utf8_unicode_ci,
  `landusevalue` tinyint(2) unsigned default NULL,
  `landusecode` smallint(6) unsigned default NULL,
  `depthdiscovery` tinyint(2) default NULL,
  `soiltype` tinyint(2) default NULL,
  `highsensitivity` tinyint(1) default NULL,
  `old_occupierid` varchar(50) collate utf8_unicode_ci default NULL,
  `occupier` varchar(50) character set latin1 default NULL,
  `smrref` varchar(20) collate utf8_unicode_ci default NULL,
  `otherref` varchar(50) collate utf8_unicode_ci default NULL,
  `date` datetime default NULL,
  `createdBy` int(11) default NULL,
  `created` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `updated` datetime default NULL,
  `landowner` varchar(50) collate utf8_unicode_ci default NULL,
  `map25k` varchar(255) collate utf8_unicode_ci default NULL,
  `map10k` varchar(255) collate utf8_unicode_ci default NULL,
  `parish` varchar(255) collate utf8_unicode_ci default NULL,
  `regionID` tinyint(2) unsigned default NULL,
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `district` varchar(255) collate utf8_unicode_ci default NULL,
  `country` varchar(25) collate utf8_unicode_ci default NULL,
  `institution` varchar(10) collate utf8_unicode_ci default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `parish` (`parish`),
  KEY `declong` (`declong`),
  KEY `declat` (`declat`),
  KEY `county` (`county`),
  KEY `district` (`district`),
  KEY `findID` (`findID`),
  KEY `gridref` (`gridref`),
  KEY `knownas` (`knownas`),
  KEY `fourFigure` (`fourFigure`),
  KEY `country` (`country`),
  KEY `secuid` (`secuid`),
  KEY `woeid` (`woeid`),
  KEY `landusevalue` (`landusevalue`),
  KEY `landusecode` (`landusecode`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=460012 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `findspotsAudit`
-- 
-- Creation: Oct 16, 2010 at 11:14 AM
-- Last update: Dec 13, 2011 at 09:23 AM
-- Last check: Oct 16, 2010 at 11:14 AM
-- 

CREATE TABLE `findspotsAudit` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `findID` int(11) default '0',
  `findspotID` int(11) default NULL,
  `editID` varchar(25) collate utf8_unicode_ci default NULL,
  `fieldName` varchar(255) collate utf8_unicode_ci default NULL,
  `beforeValue` mediumtext collate utf8_unicode_ci,
  `afterValue` mediumtext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`),
  KEY `findspotID` (`findspotID`),
  KEY `findID` (`findID`)
) ENGINE=MyISAM AUTO_INCREMENT=228846 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `findxfind`
-- 
-- Creation: Sep 20, 2010 at 12:37 PM
-- Last update: Sep 20, 2010 at 12:37 PM
-- Last check: Sep 20, 2010 at 12:37 PM
-- 

CREATE TABLE `findxfind` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `find1ID` varchar(50) collate utf8_unicode_ci default NULL,
  `find2ID` varchar(50) collate utf8_unicode_ci default NULL,
  `relationship` varchar(100) collate utf8_unicode_ci default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `sectag` int(10) unsigned NOT NULL default '0',
  `secowner` int(10) unsigned NOT NULL default '0',
  `updatedBy` int(10) unsigned default '0',
  `createdBy` int(10) unsigned default '0',
  `updated` timestamp NOT NULL default '0000-00-00 00:00:00',
  `created` date default '0000-00-00',
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `secreplica` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `find1ID` (`find1ID`),
  KEY `find2ID` (`find2ID`)
) ENGINE=MyISAM AUTO_INCREMENT=15288 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `geographyironage`
-- 
-- Creation: Nov 28, 2010 at 09:38 AM
-- Last update: Nov 28, 2010 at 09:38 AM
-- Last check: Nov 28, 2010 at 09:38 AM
-- 

CREATE TABLE `geographyironage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `area` varchar(255) collate utf8_unicode_ci default NULL,
  `region` varchar(255) collate utf8_unicode_ci default NULL,
  `tribe` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(1) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Geography data for the Iron Age coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `geoplanetadjacent`
-- 
-- Creation: Sep 20, 2010 at 12:24 PM
-- Last update: Sep 20, 2010 at 12:24 PM
-- Last check: Sep 20, 2010 at 12:24 PM
-- 

CREATE TABLE `geoplanetadjacent` (
  `PLACE_WOE_ID` int(11) NOT NULL,
  `ISO` varchar(3) collate utf8_unicode_ci default NULL,
  `NEIGHBOUR_WOE_ID` int(11) NOT NULL,
  KEY `PLACE_WOE_ID` (`PLACE_WOE_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `geoplanetaliases`
-- 
-- Creation: Sep 20, 2010 at 12:33 PM
-- Last update: Sep 20, 2010 at 12:33 PM
-- 

CREATE TABLE `geoplanetaliases` (
  `WOE_ID` int(11) NOT NULL,
  `alias` varchar(255) collate utf8_unicode_ci default NULL,
  `type` varchar(30) collate utf8_unicode_ci default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `geoplanetplaces`
-- 
-- Creation: Sep 20, 2010 at 12:13 PM
-- Last update: Sep 20, 2010 at 12:14 PM
-- 

CREATE TABLE `geoplanetplaces` (
  `WOE_ID` int(11) NOT NULL,
  `ISO` varchar(5) collate utf8_unicode_ci default NULL,
  `Name` varchar(200) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`WOE_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `greekstates`
-- 
-- Creation: Sep 20, 2010 at 12:34 PM
-- Last update: Sep 20, 2010 at 12:34 PM
-- 

CREATE TABLE `greekstates` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `state` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=807 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='State dropdown values for Greek and Roman Provincial period ';

-- --------------------------------------------------------

-- 
-- Table structure for table `gridrefsources`
-- 
-- Creation: Sep 20, 2010 at 12:34 PM
-- Last update: Sep 20, 2010 at 12:34 PM
-- Last check: Jan 10, 2011 at 09:55 PM
-- 

CREATE TABLE `gridrefsources` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` char(255) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `help`
-- 
-- Creation: Sep 20, 2010 at 12:35 PM
-- Last update: Nov 30, 2011 at 10:25 AM
-- 

CREATE TABLE `help` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `menuTitle` varchar(255) collate utf8_unicode_ci default NULL,
  `excerpt` text collate utf8_unicode_ci,
  `body` text collate utf8_unicode_ci,
  `section` varchar(55) collate utf8_unicode_ci default NULL,
  `category` varchar(55) collate utf8_unicode_ci default NULL,
  `author` int(11) default NULL,
  `frontPage` int(11) default NULL,
  `publishState` int(1) default NULL,
  `metaDescription` text collate utf8_unicode_ci,
  `metaKeywords` text collate utf8_unicode_ci,
  `slug` tinytext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `frontPage` (`frontPage`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `heritagecrime`
-- 
-- Creation: Jul 20, 2010 at 03:15 PM
-- Last update: May 16, 2011 at 12:45 PM
-- 

CREATE TABLE `heritagecrime` (
  `id` int(11) NOT NULL auto_increment,
  `crimeType` tinyint(1) default NULL,
  `subject` varchar(255) collate utf8_unicode_ci default NULL,
  `reporterID` varchar(25) collate utf8_unicode_ci default NULL,
  `incidentDate` date default NULL,
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `district` varchar(255) collate utf8_unicode_ci default NULL,
  `parish` varchar(255) collate utf8_unicode_ci default NULL,
  `gridref` varchar(20) collate utf8_unicode_ci default NULL,
  `fourFigure` varchar(6) collate utf8_unicode_ci default NULL,
  `elevation` int(11) default NULL,
  `easting` int(6) default NULL,
  `northing` int(6) default NULL,
  `map10k` varchar(20) collate utf8_unicode_ci default NULL,
  `map25k` varchar(20) collate utf8_unicode_ci default NULL,
  `latitude` float default NULL,
  `longitude` float default NULL,
  `woeid` int(11) default NULL,
  `description` text collate utf8_unicode_ci,
  `reliability` tinyint(1) default NULL,
  `evaluation` text collate utf8_unicode_ci,
  `samID` int(11) NOT NULL,
  `intellEvaluation` text collate utf8_unicode_ci,
  `reportSubject` varchar(255) collate utf8_unicode_ci default NULL,
  `subjectDetails` text collate utf8_unicode_ci NOT NULL,
  `reportingPerson` text collate utf8_unicode_ci NOT NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `crimeType` (`crimeType`),
  KEY `samID` (`samID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Heritage crime reports';

-- --------------------------------------------------------

-- 
-- Table structure for table `hers`
-- 
-- Creation: Sep 20, 2010 at 12:37 PM
-- Last update: Sep 20, 2010 at 12:37 PM
-- 

CREATE TABLE `hers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `contact_name` varchar(255) collate utf8_unicode_ci default NULL,
  `createdBy` int(3) default NULL,
  `created` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `hitlog`
-- 
-- Creation: Sep 20, 2010 at 12:38 PM
-- Last update: Sep 20, 2010 at 12:38 PM
-- 

CREATE TABLE `hitlog` (
  `id` int(11) NOT NULL auto_increment,
  `findID` varchar(255) collate utf8_unicode_ci default NULL,
  `userID` int(11) default NULL,
  `visited` datetime default NULL,
  `ipAddress` varchar(16) collate utf8_unicode_ci default NULL,
  `userAgent` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `hoards`
-- 
-- Creation: Nov 25, 2010 at 05:06 PM
-- Last update: Mar 21, 2011 at 03:06 PM
-- Last check: Nov 25, 2010 at 05:06 PM
-- 

CREATE TABLE `hoards` (
  `id` int(11) NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `period` int(11) NOT NULL,
  `termdesc` text collate utf8_unicode_ci,
  `created_by` int(11) NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `period` (`period`),
  KEY `updated_by` (`updated_by`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `imagetypes`
-- 
-- Creation: Sep 20, 2010 at 12:40 PM
-- Last update: Sep 20, 2010 at 12:40 PM
-- 

CREATE TABLE `imagetypes` (
  `id` int(2) NOT NULL auto_increment,
  `type` varchar(55) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `created_by` int(2) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `instLogos`
-- 
-- Creation: Nov 25, 2010 at 04:03 PM
-- Last update: Jan 25, 2011 at 02:05 PM
-- 

CREATE TABLE `instLogos` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `image` varchar(55) collate utf8_unicode_ci default NULL,
  `instID` varchar(6) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `instID` (`instID`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Institutional logos for partners';

-- --------------------------------------------------------

-- 
-- Table structure for table `institutions`
-- 
-- Creation: Nov 26, 2010 at 02:55 PM
-- Last update: Sep 01, 2011 at 11:36 AM
-- Last check: Nov 26, 2010 at 02:55 PM
-- 

CREATE TABLE `institutions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `institution` varchar(10) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `institution` (`institution`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=60 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Recording institutions';

-- --------------------------------------------------------

-- 
-- Table structure for table `ironagedenomxregion`
-- 
-- Creation: Sep 20, 2010 at 12:41 PM
-- Last update: Sep 20, 2010 at 12:41 PM
-- 

CREATE TABLE `ironagedenomxregion` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `denomID` int(10) unsigned NOT NULL default '0',
  `regionID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=321 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Denomination-to-Region relaations for the Iron Age coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `ironageregionstribes`
-- 
-- Creation: Sep 20, 2010 at 12:41 PM
-- Last update: Sep 20, 2010 at 12:41 PM
-- 

CREATE TABLE `ironageregionstribes` (
  `id` int(3) NOT NULL,
  `regionID` int(3) NOT NULL,
  `tribeID` int(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Region to tribe lookup table';

-- --------------------------------------------------------

-- 
-- Table structure for table `ironagerulerxregion`
-- 
-- Creation: Sep 20, 2010 at 12:41 PM
-- Last update: Sep 20, 2010 at 12:41 PM
-- 

CREATE TABLE `ironagerulerxregion` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `rulerID` int(10) unsigned NOT NULL default '0',
  `regionID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ruler-to-Region relations for the Iron Age coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `ironagetribes`
-- 
-- Creation: Sep 20, 2010 at 12:41 PM
-- Last update: Sep 20, 2010 at 12:41 PM
-- 

CREATE TABLE `ironagetribes` (
  `id` int(2) NOT NULL auto_increment,
  `tribe` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `valid` enum('0','1') collate utf8_unicode_ci default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Iron Age tribes ';

-- --------------------------------------------------------

-- 
-- Table structure for table `issuers`
-- 
-- Creation: Sep 20, 2010 at 12:11 PM
-- Last update: Sep 20, 2010 at 12:11 PM
-- 

CREATE TABLE `issuers` (
  `id` int(4) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `pasID` int(3) default NULL,
  `period` int(2) default NULL,
  `date_from` varchar(5) collate utf8_unicode_ci default NULL,
  `date_to` varchar(5) collate utf8_unicode_ci default NULL,
  `biography` text collate utf8_unicode_ci,
  `image` varchar(100) collate utf8_unicode_ci default NULL,
  `zoomfolder` varchar(55) collate utf8_unicode_ci default NULL,
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_by` int(3) NOT NULL default '0',
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `created_by` int(3) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=860 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `landuses`
-- 
-- Creation: Nov 25, 2010 at 04:37 PM
-- Last update: Nov 25, 2010 at 04:37 PM
-- Last check: Nov 25, 2010 at 04:37 PM
-- 

CREATE TABLE `landuses` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `oldID` varchar(5) collate utf8_unicode_ci default NULL,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `belongsto` int(6) default NULL,
  `valid` tinyint(6) unsigned default '1',
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `term` (`term`),
  KEY `belongsto` (`belongsto`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `links`
-- 
-- Creation: Sep 20, 2010 at 12:09 PM
-- Last update: Sep 20, 2010 at 12:09 PM
-- 

CREATE TABLE `links` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `url` varchar(255) collate utf8_unicode_ci default NULL,
  `summary` text collate utf8_unicode_ci,
  `type` int(2) NOT NULL default '0',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `logins`
-- 
-- Creation: Apr 12, 2011 at 02:34 PM
-- Last update: Dec 13, 2011 at 09:57 AM
-- Last check: Apr 12, 2011 at 02:34 PM
-- 

CREATE TABLE `logins` (
  `id` int(11) NOT NULL auto_increment,
  `loginDate` datetime default NULL,
  `ipAddress` varchar(16) collate utf8_unicode_ci default NULL,
  `userAgent` varchar(255) collate utf8_unicode_ci default NULL,
  `username` varchar(40) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `loginDate` (`loginDate`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=88367 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Login user history';

-- --------------------------------------------------------

-- 
-- Table structure for table `macktypes`
-- 
-- Creation: Nov 26, 2010 at 02:55 PM
-- Last update: Nov 26, 2010 at 02:55 PM
-- Last check: Nov 26, 2010 at 02:55 PM
-- 

CREATE TABLE `macktypes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  `updated` datetime default NULL,
  `updatedBy` int(11) default '56',
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=526 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mack Types for Iron Age coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `mailinglist`
-- 
-- Creation: Sep 20, 2010 at 12:47 PM
-- Last update: Sep 21, 2011 at 10:08 PM
-- 

CREATE TABLE `mailinglist` (
  `id` int(11) NOT NULL auto_increment,
  `fullname` varchar(255) collate utf8_unicode_ci default NULL,
  `email` varchar(255) collate utf8_unicode_ci default NULL,
  `tel` varchar(100) collate utf8_unicode_ci default NULL,
  `address` varchar(255) collate utf8_unicode_ci default NULL,
  `town_city` varchar(255) collate utf8_unicode_ci default NULL,
  `county` varchar(80) collate utf8_unicode_ci default NULL,
  `postcode` varchar(30) collate utf8_unicode_ci default NULL,
  `country` varchar(5) collate utf8_unicode_ci default NULL,
  `ip_address` varchar(50) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mailing list sign ups';

-- --------------------------------------------------------

-- 
-- Table structure for table `manufactures`
-- 
-- Creation: Sep 20, 2010 at 12:48 PM
-- Last update: Jul 11, 2011 at 10:36 AM
-- 

CREATE TABLE `manufactures` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` smallint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `term` (`term`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `maporigins`
-- 
-- Creation: Sep 20, 2010 at 12:50 PM
-- Last update: Jan 21, 2011 at 03:29 PM
-- 

CREATE TABLE `maporigins` (
  `id` int(11) NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Origins of grid references';

-- --------------------------------------------------------

-- 
-- Table structure for table `materials`
-- 
-- Creation: Nov 23, 2010 at 05:15 PM
-- Last update: Nov 23, 2010 at 05:15 PM
-- 

CREATE TABLE `materials` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `parentID` int(50) unsigned default NULL,
  `valid` tinyint(6) unsigned NOT NULL default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `valid` (`valid`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `mda_obj_prefs`
-- 
-- Creation: Sep 20, 2010 at 01:19 PM
-- Last update: Sep 20, 2010 at 01:19 PM
-- 

CREATE TABLE `mda_obj_prefs` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `THE_TE_UID_1` int(11) unsigned default NULL,
  `THE_TE_UID_2` int(11) unsigned default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=494 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `mda_obj_rels`
-- 
-- Creation: Sep 20, 2010 at 01:19 PM
-- Last update: Sep 20, 2010 at 01:19 PM
-- 

CREATE TABLE `mda_obj_rels` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `TH_T_U_UID_1` int(11) unsigned default NULL,
  `TH_T_U_UID_2` int(11) unsigned default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `mda_obj_uses`
-- 
-- Creation: Sep 20, 2010 at 01:19 PM
-- Last update: Sep 20, 2010 at 01:19 PM
-- 

CREATE TABLE `mda_obj_uses` (
  `ID` int(11) unsigned NOT NULL auto_increment,
  `TH_T_U_UID` int(11) unsigned default NULL,
  `TERM` char(50) collate utf8_unicode_ci default NULL,
  `CLA_GR_UID` smallint(6) unsigned default NULL,
  `BROAD_TERM_U_UID` int(11) unsigned default NULL,
  `TOP_TERM_U_UID` int(11) unsigned default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=1851 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `medievalcategories`
-- 
-- Creation: Sep 20, 2010 at 12:47 PM
-- Last update: Sep 20, 2010 at 12:47 PM
-- 

CREATE TABLE `medievalcategories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` varchar(255) collate utf8_unicode_ci default NULL,
  `periodID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `medievaltypes`
-- 
-- Creation: Nov 25, 2010 at 04:34 PM
-- Last update: Jan 19, 2011 at 02:37 PM
-- Last check: Dec 10, 2010 at 05:41 PM
-- 

CREATE TABLE `medievaltypes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `rulerID` int(10) unsigned NOT NULL default '0',
  `periodID` int(10) unsigned NOT NULL default '0',
  `datefrom` int(11) NOT NULL default '0',
  `dateto` int(11) NOT NULL default '0',
  `categoryID` int(10) unsigned NOT NULL default '0',
  `type` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rulerID` (`rulerID`),
  KEY `categoryID` (`categoryID`)
) ENGINE=MyISAM AUTO_INCREMENT=3474 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Coin types';

-- --------------------------------------------------------

-- 
-- Table structure for table `messages`
-- 
-- Creation: Sep 20, 2010 at 01:01 PM
-- Last update: Dec 10, 2011 at 01:03 PM
-- 

CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `comment_author` varchar(255) collate utf8_unicode_ci default NULL,
  `comment_type` varchar(100) collate utf8_unicode_ci default NULL,
  `comment_content` text collate utf8_unicode_ci,
  `comment_author_email` varchar(255) collate utf8_unicode_ci default NULL,
  `comment_date` datetime default NULL,
  `comment_approved` varchar(50) collate utf8_unicode_ci default NULL,
  `user_id` int(11) default NULL,
  `user_ip` varchar(50) collate utf8_unicode_ci default NULL,
  `user_agent` varchar(255) collate utf8_unicode_ci default NULL,
  `replied` tinyint(4) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=371 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Log of messages from contact us form';

-- --------------------------------------------------------

-- 
-- Table structure for table `mint_reversetype`
-- 
-- Creation: Nov 25, 2010 at 05:14 PM
-- Last update: Nov 25, 2010 at 05:14 PM
-- Last check: Nov 25, 2010 at 05:14 PM
-- 

CREATE TABLE `mint_reversetype` (
  `id` int(11) NOT NULL auto_increment,
  `mintID` int(4) default NULL,
  `reverseID` int(4) default NULL,
  PRIMARY KEY  (`id`),
  KEY `mintID` (`mintID`),
  KEY `reverseID` (`reverseID`)
) ENGINE=MyISAM AUTO_INCREMENT=1552 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mint to reverse link table';

-- --------------------------------------------------------

-- 
-- Table structure for table `mints`
-- 
-- Creation: Nov 22, 2010 at 10:38 PM
-- Last update: Dec 22, 2010 at 12:04 PM
-- Last check: Nov 22, 2010 at 10:38 PM
-- 

CREATE TABLE `mints` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `period` int(10) unsigned NOT NULL default '0',
  `old_period` varchar(255) collate utf8_unicode_ci default NULL,
  `mint_name` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(4) unsigned NOT NULL default '1',
  `created` datetime default '0000-00-00 00:00:00',
  `createdBy` int(11) default NULL,
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `mint_name` (`mint_name`),
  KEY `valid` (`valid`),
  KEY `period` (`period`)
) ENGINE=MyISAM AUTO_INCREMENT=1521 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `mints_rulers`
-- 
-- Creation: Nov 26, 2010 at 02:47 PM
-- Last update: Feb 24, 2011 at 09:28 AM
-- Last check: Nov 26, 2010 at 02:47 PM
-- 

CREATE TABLE `mints_rulers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ruler_id` int(10) unsigned NOT NULL default '0',
  `mint_id` int(10) unsigned NOT NULL default '0',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `mint_id` (`mint_id`),
  KEY `ruler_id` (`ruler_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3248 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `monarchs`
-- 
-- Creation: Sep 20, 2010 at 12:53 PM
-- Last update: Sep 20, 2010 at 12:53 PM
-- 

CREATE TABLE `monarchs` (
  `id` int(2) NOT NULL auto_increment,
  `dbaseID` int(11) default NULL,
  `name` varchar(200) collate utf8_unicode_ci default NULL,
  `styled` varchar(255) collate utf8_unicode_ci default NULL,
  `alias` varchar(100) collate utf8_unicode_ci default NULL,
  `date_from` varchar(100) collate utf8_unicode_ci default NULL,
  `date_to` varchar(100) collate utf8_unicode_ci default NULL,
  `biography` text collate utf8_unicode_ci,
  `born` varchar(255) collate utf8_unicode_ci default NULL,
  `died` varchar(255) collate utf8_unicode_ci default NULL,
  `dynasty` int(3) default NULL,
  `publishState` int(11) default NULL,
  `created` datetime default NULL,
  `createdby` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedby` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `dbaseID` (`dbaseID`),
  KEY `createdby` (`createdby`),
  KEY `publishState` (`publishState`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `moneyers`
-- 
-- Creation: Sep 20, 2010 at 01:00 PM
-- Last update: Sep 20, 2010 at 01:00 PM
-- 

CREATE TABLE `moneyers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `wikipediaEntry` varchar(100) collate utf8_unicode_ci default NULL,
  `period` int(2) default '21',
  `alt_name` varchar(255) collate utf8_unicode_ci default NULL,
  `date_1` varchar(15) collate utf8_unicode_ci default NULL,
  `date_2` varchar(15) collate utf8_unicode_ci default NULL,
  `mint` varchar(55) collate utf8_unicode_ci default NULL,
  `bio` text collate utf8_unicode_ci,
  `RRC` varchar(15) collate utf8_unicode_ci default NULL,
  `appear` varchar(25) collate utf8_unicode_ci default NULL,
  `valid` tinyint(1) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=373 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Roman Republic Moneyers';

-- --------------------------------------------------------

-- 
-- Table structure for table `myresearch`
-- 
-- Creation: Sep 20, 2010 at 01:07 PM
-- Last update: Sep 20, 2010 at 01:07 PM
-- 

CREATE TABLE `myresearch` (
  `id` int(11) NOT NULL auto_increment,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `public` int(1) NOT NULL default '0',
  `createdBy` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `secuid` (`secuid`),
  KEY `public` (`public`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Research catalogues';

-- --------------------------------------------------------

-- 
-- Table structure for table `news`
-- 
-- Creation: Apr 15, 2011 at 04:06 PM
-- Last update: Dec 11, 2011 at 05:57 PM
-- Last check: Apr 15, 2011 at 04:06 PM
-- 

CREATE TABLE `news` (
  `id` int(4) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `summary` text collate utf8_unicode_ci,
  `link` varchar(255) collate utf8_unicode_ci default NULL,
  `datePublished` datetime default NULL,
  `author` varchar(255) collate utf8_unicode_ci default NULL,
  `contactName` varchar(255) collate utf8_unicode_ci default NULL,
  `contactTel` varchar(255) collate utf8_unicode_ci default NULL,
  `contactEmail` varchar(255) collate utf8_unicode_ci default NULL,
  `editorNotes` varchar(255) collate utf8_unicode_ci default NULL,
  `contents` text collate utf8_unicode_ci,
  `keywords` varchar(255) collate utf8_unicode_ci default NULL,
  `regionID` varchar(255) collate utf8_unicode_ci default NULL,
  `typeID` varchar(255) character set latin1 default NULL,
  `publish_state` tinyint(1) default '0',
  `golive` datetime default '0000-00-00 00:00:00',
  `primaryNewsLocation` varchar(255) collate utf8_unicode_ci default NULL,
  `latitude` float default NULL,
  `longitude` float default NULL,
  `woeid` int(11) NOT NULL,
  `created` datetime default '0000-00-00 00:00:00',
  `createdBy` int(3) default '0',
  `updatedBy` int(3) default '0',
  `updated` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `woeid` (`woeid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `created` (`created`)
) ENGINE=MyISAM AUTO_INCREMENT=226 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `oai_pmh_repository_tokens`
-- 
-- Creation: Oct 27, 2010 at 12:09 PM
-- Last update: Dec 11, 2011 at 07:10 PM
-- Last check: Nov 04, 2011 at 10:21 PM
-- 

CREATE TABLE `oai_pmh_repository_tokens` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `verb` enum('ListIdentifiers','ListRecords','ListSets') collate utf8_unicode_ci NOT NULL,
  `metadata_prefix` text collate utf8_unicode_ci NOT NULL,
  `cursor` int(10) unsigned NOT NULL default '0',
  `from` datetime default NULL,
  `until` datetime default NULL,
  `set` varchar(10) collate utf8_unicode_ci default NULL,
  `expiration` datetime NOT NULL,
  `ipaddress` varchar(55) collate utf8_unicode_ci default NULL,
  `useragent` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `expiration` (`expiration`)
) ENGINE=MyISAM AUTO_INCREMENT=22935 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `oauthTokens`
-- 
-- Creation: Nov 25, 2010 at 05:45 PM
-- Last update: Dec 13, 2011 at 09:09 AM
-- Last check: Jul 19, 2011 at 05:32 PM
-- 

CREATE TABLE `oauthTokens` (
  `id` int(11) NOT NULL auto_increment,
  `accessToken` text collate utf8_unicode_ci NOT NULL,
  `tokenSecret` text collate utf8_unicode_ci NOT NULL,
  `service` varchar(50) collate utf8_unicode_ci NOT NULL,
  `sessionHandle` text collate utf8_unicode_ci NOT NULL,
  `guid` text collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `expires` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `expires` (`expires`),
  KEY `service` (`service`)
) ENGINE=MyISAM AUTO_INCREMENT=13252 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Oauth tokens';

-- --------------------------------------------------------

-- 
-- Table structure for table `objectterms`
-- 
-- Creation: Nov 25, 2010 at 03:49 PM
-- Last update: Dec 07, 2010 at 10:27 AM
-- Last check: Nov 25, 2010 at 03:49 PM
-- 

CREATE TABLE `objectterms` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `uid` int(11) unsigned default NULL,
  `term` char(50) collate utf8_unicode_ci default NULL,
  `indexTerm` char(1) collate utf8_unicode_ci default NULL,
  `scopeNote` char(255) collate utf8_unicode_ci default NULL,
  `claUid` smallint(6) unsigned default NULL,
  `status` char(1) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `term` (`term`),
  KEY `indexTerm` (`indexTerm`)
) ENGINE=MyISAM AUTO_INCREMENT=2126 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `opencalais`
-- 
-- Creation: Sep 15, 2010 at 05:52 PM
-- Last update: Nov 29, 2010 at 10:30 AM
-- Last check: Nov 29, 2010 at 10:30 AM
-- 

CREATE TABLE `opencalais` (
  `id` int(11) NOT NULL auto_increment,
  `contentID` int(11) default NULL,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `type` varchar(255) collate utf8_unicode_ci default NULL,
  `contenttype` varchar(25) collate utf8_unicode_ci default NULL,
  `origin` varchar(25) collate utf8_unicode_ci default NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `woeid` varchar(12) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `creator` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `woeid` (`woeid`),
  KEY `contenttype` (`contenttype`),
  KEY `origin` (`origin`),
  KEY `contentID` (`contentID`)
) ENGINE=MyISAM AUTO_INCREMENT=901646 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Opencalais tagged content';

-- --------------------------------------------------------

-- 
-- Table structure for table `organisations`
-- 
-- Creation: Nov 25, 2010 at 04:59 PM
-- Last update: Nov 09, 2011 at 11:32 AM
-- Last check: Nov 25, 2010 at 04:59 PM
-- 

CREATE TABLE `organisations` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci default NULL,
  `website` varchar(255) collate utf8_unicode_ci default NULL,
  `address1` varchar(100) collate utf8_unicode_ci default NULL,
  `address2` varchar(100) collate utf8_unicode_ci default NULL,
  `address3` varchar(100) collate utf8_unicode_ci default NULL,
  `address` text collate utf8_unicode_ci,
  `town_city` varchar(50) collate utf8_unicode_ci default NULL,
  `county` varchar(50) collate utf8_unicode_ci default NULL,
  `country` varchar(50) collate utf8_unicode_ci default NULL,
  `postcode` varchar(50) collate utf8_unicode_ci default NULL,
  `woeid` int(11) default NULL,
  `lat` float default NULL,
  `lon` float default NULL,
  `contactpersonID` varchar(50) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(20) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(20) unsigned default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `woeid` (`woeid`),
  KEY `secuid` (`secuid`)
) ENGINE=MyISAM AUTO_INCREMENT=392 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `organisationsAudit`
-- 
-- Creation: Sep 20, 2010 at 12:54 PM
-- Last update: Nov 09, 2011 at 11:32 AM
-- Last check: Sep 20, 2010 at 12:54 PM
-- 

CREATE TABLE `organisationsAudit` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `orgID` int(11) default '0',
  `editID` varchar(25) collate utf8_unicode_ci default NULL,
  `fieldName` varchar(255) collate utf8_unicode_ci default NULL,
  `beforeValue` mediumtext collate utf8_unicode_ci,
  `afterValue` mediumtext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM AUTO_INCREMENT=281 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `osdata`
-- 
-- Creation: Nov 23, 2010 at 05:09 PM
-- Last update: Nov 23, 2010 at 05:09 PM
-- Last check: Nov 23, 2010 at 05:09 PM
-- 

CREATE TABLE `osdata` (
  `id` int(11) NOT NULL,
  `km_ref` char(6) collate utf8_unicode_ci default NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `name` char(60) collate utf8_unicode_ci default NULL,
  `tile_ref` char(4) collate utf8_unicode_ci default NULL,
  `lat_degrees` int(2) default NULL,
  `lat_minutes` float default NULL,
  `lon_degrees` int(2) default NULL,
  `lon_minutes` float default NULL,
  `northing` int(7) default NULL,
  `easting` int(7) default NULL,
  `gmt` char(1) collate utf8_unicode_ci default NULL,
  `county_code` char(2) collate utf8_unicode_ci default NULL,
  `county` char(20) collate utf8_unicode_ci default NULL,
  `full_county` char(60) collate utf8_unicode_ci default NULL,
  `district` varchar(100) collate utf8_unicode_ci default NULL,
  `parish` varchar(100) collate utf8_unicode_ci default NULL,
  `f_code` char(3) collate utf8_unicode_ci default NULL,
  `e_date` char(11) collate utf8_unicode_ci default NULL,
  `update_code` char(1) collate utf8_unicode_ci default NULL,
  `sheet1` int(3) default NULL,
  `sheet2` int(3) default NULL,
  `sheet3` int(3) default NULL,
  PRIMARY KEY  (`id`),
  KEY `latitude` (`latitude`),
  KEY `longitude` (`longitude`),
  KEY `f_code` (`f_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='OSDATA 1:50000';

-- --------------------------------------------------------

-- 
-- Table structure for table `people`
-- 
-- Creation: Jan 21, 2011 at 03:43 PM
-- Last update: Dec 12, 2011 at 11:47 PM
-- Last check: Jan 21, 2011 at 03:43 PM
-- 

CREATE TABLE `people` (
  `id` int(11) NOT NULL auto_increment,
  `organisationID` varchar(50) collate utf8_unicode_ci default NULL,
  `surname` varchar(90) collate utf8_unicode_ci default NULL,
  `forename` varchar(50) collate utf8_unicode_ci default NULL,
  `fullname` varchar(250) collate utf8_unicode_ci default NULL,
  `title` varchar(20) collate utf8_unicode_ci default NULL,
  `address` text collate utf8_unicode_ci,
  `town_city` varchar(50) collate utf8_unicode_ci default NULL,
  `county` varchar(50) collate utf8_unicode_ci default NULL,
  `country` varchar(50) collate utf8_unicode_ci default NULL,
  `postcode` varchar(50) collate utf8_unicode_ci default NULL,
  `hometel` varchar(50) collate utf8_unicode_ci default NULL,
  `worktel` varchar(50) collate utf8_unicode_ci default NULL,
  `email` varchar(50) collate utf8_unicode_ci default NULL,
  `faxno` varchar(50) collate utf8_unicode_ci default NULL,
  `comments` text collate utf8_unicode_ci,
  `type` smallint(6) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `secreplica` varchar(50) collate utf8_unicode_ci default NULL,
  `primary_activity` int(11) default NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `woeid` int(11) NOT NULL,
  `dbaseID` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `primary_activity` (`primary_activity`),
  KEY `woeid` (`woeid`),
  KEY `secuid` (`secuid`),
  KEY `dbaseID` (`dbaseID`),
  KEY `organisationID` (`organisationID`),
  KEY `fullname` (`fullname`)
) ENGINE=MyISAM AUTO_INCREMENT=23485 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `peopleAudit`
-- 
-- Creation: Sep 20, 2010 at 01:08 PM
-- Last update: Dec 12, 2011 at 02:53 PM
-- Last check: Sep 20, 2010 at 01:08 PM
-- 

CREATE TABLE `peopleAudit` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `personID` int(11) default '0',
  `editID` varchar(25) collate utf8_unicode_ci default NULL,
  `fieldName` varchar(255) collate utf8_unicode_ci default NULL,
  `beforeValue` mediumtext collate utf8_unicode_ci,
  `afterValue` mediumtext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM AUTO_INCREMENT=21316 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `peopletypes`
-- 
-- Creation: Sep 20, 2010 at 01:08 PM
-- Last update: Sep 20, 2010 at 01:08 PM
-- 

CREATE TABLE `peopletypes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `modified` datetime NOT NULL,
  KEY `ID` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `periods`
-- 
-- Creation: Sep 20, 2010 at 01:08 PM
-- Last update: Feb 25, 2011 at 11:46 AM
-- 

CREATE TABLE `periods` (
  `id` int(11) unsigned NOT NULL default '0',
  `term` char(50) collate utf8_unicode_ci default NULL,
  `notes` text collate utf8_unicode_ci,
  `parent` int(11) NOT NULL default '0',
  `broadterm` int(10) unsigned default NULL,
  `type` int(11) default NULL,
  `fromdate` int(11) default NULL,
  `todate` int(11) default NULL,
  `old_sortorder` int(10) unsigned NOT NULL default '0',
  `sortorder` int(10) unsigned NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `preferred` tinyint(4) unsigned NOT NULL default '1',
  `valid` tinyint(4) unsigned NOT NULL default '0',
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `places`
-- 
-- Creation: Mar 16, 2011 at 09:13 AM
-- Last update: Mar 16, 2011 at 09:13 AM
-- Last check: Mar 16, 2011 at 09:13 AM
-- 

CREATE TABLE `places` (
  `old_county` varchar(255) collate utf8_unicode_ci default NULL,
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `district` varchar(255) collate utf8_unicode_ci default NULL,
  `parish` varchar(255) collate utf8_unicode_ci default NULL,
  `placeID` int(11) unsigned default NULL,
  `parentID` int(11) default NULL,
  `adln_type` varchar(255) collate utf8_unicode_ci default NULL,
  `npl_flag` varchar(255) collate utf8_unicode_ci default NULL,
  `ID` int(11) unsigned NOT NULL auto_increment,
  `active` tinyint(4) unsigned default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `IDX_placeID` (`placeID`),
  KEY `parish` (`parish`),
  KEY `county` (`county`),
  KEY `district` (`district`)
) ENGINE=MyISAM AUTO_INCREMENT=12303 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `places2`
-- 
-- Creation: Sep 20, 2010 at 01:19 PM
-- Last update: Sep 20, 2010 at 01:19 PM
-- Last check: Sep 20, 2010 at 01:19 PM
-- 

CREATE TABLE `places2` (
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `district` varchar(255) collate utf8_unicode_ci default NULL,
  `parish` varchar(255) collate utf8_unicode_ci default NULL,
  `placeID` int(11) unsigned default NULL,
  `parentID` int(11) default NULL,
  `adln_type` varchar(255) collate utf8_unicode_ci default NULL,
  `npl_flag` varchar(255) collate utf8_unicode_ci default NULL,
  `id` int(11) unsigned NOT NULL auto_increment,
  `active` tinyint(4) unsigned default NULL,
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `county` (`county`)
) ENGINE=MyISAM AUTO_INCREMENT=12297 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `preservations`
-- 
-- Creation: Nov 25, 2010 at 05:01 PM
-- Last update: Jul 13, 2011 at 05:26 PM
-- Last check: Nov 25, 2010 at 05:02 PM
-- 

CREATE TABLE `preservations` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` enum('1','0') collate utf8_unicode_ci default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  `updatedBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `primaryactivities`
-- 
-- Creation: Sep 20, 2010 at 01:10 PM
-- Last update: Nov 16, 2010 at 10:17 AM
-- 

CREATE TABLE `primaryactivities` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(11) unsigned default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `projecttypes`
-- 
-- Creation: Sep 20, 2010 at 01:11 PM
-- Last update: Sep 24, 2010 at 01:55 PM
-- 

CREATE TABLE `projecttypes` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types of research project';

-- --------------------------------------------------------

-- 
-- Table structure for table `publications`
-- 
-- Creation: Nov 23, 2010 at 04:34 PM
-- Last update: Dec 09, 2011 at 11:03 AM
-- Last check: Nov 23, 2010 at 04:34 PM
-- 

CREATE TABLE `publications` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `publication_type` varchar(20) collate utf8_unicode_ci default NULL,
  `authors` varchar(255) collate utf8_unicode_ci default NULL,
  `editors` varchar(255) collate utf8_unicode_ci default NULL,
  `reprint_year` smallint(6) default NULL,
  `in_publication` int(11) default NULL,
  `article_pages` varchar(20) collate utf8_unicode_ci default NULL,
  `edition` varchar(50) collate utf8_unicode_ci default NULL,
  `publisher` varchar(150) collate utf8_unicode_ci default NULL,
  `publication_place` varchar(20) collate utf8_unicode_ci default NULL,
  `publication_year` smallint(6) default NULL,
  `vol_no` varchar(30) collate utf8_unicode_ci default NULL,
  `ISBN` varchar(20) collate utf8_unicode_ci default NULL,
  `url` varchar(255) collate utf8_unicode_ci default NULL,
  `accessedDate` date default NULL,
  `medium` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) unsigned default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `in_publication` (`in_publication`),
  KEY `publication_type` (`publication_type`),
  KEY `secuid` (`secuid`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `authors` (`authors`)
) ENGINE=MyISAM AUTO_INCREMENT=2652 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `publicationtypes`
-- 
-- Creation: Sep 20, 2010 at 01:14 PM
-- Last update: Sep 20, 2010 at 01:14 PM
-- 

CREATE TABLE `publicationtypes` (
  `id` int(11) NOT NULL auto_increment,
  `term` varchar(50) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `quotes`
-- 
-- Creation: Nov 25, 2010 at 05:29 PM
-- Last update: Apr 14, 2011 at 04:02 PM
-- Last check: Nov 25, 2010 at 05:29 PM
-- 

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL auto_increment,
  `quote` text collate utf8_unicode_ci,
  `quotedBy` text collate utf8_unicode_ci,
  `type` varchar(155) collate utf8_unicode_ci default 'quote',
  `status` int(1) default '1',
  `expire` date default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `status` (`status`),
  KEY `expire` (`expire`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Quotes about the Scheme';

-- --------------------------------------------------------

-- 
-- Table structure for table `rallies`
-- 
-- Creation: Nov 25, 2010 at 05:43 PM
-- Last update: Dec 12, 2011 at 02:34 PM
-- Last check: Nov 25, 2010 at 05:43 PM
-- 

CREATE TABLE `rallies` (
  `id` int(11) NOT NULL auto_increment,
  `rally_name` mediumtext collate utf8_unicode_ci,
  `parish` varchar(255) collate utf8_unicode_ci default NULL,
  `district` varchar(255) collate utf8_unicode_ci default NULL,
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `gridref` varchar(15) collate utf8_unicode_ci default NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `easting` int(11) default NULL,
  `northing` int(11) default NULL,
  `map25k` varchar(11) collate utf8_unicode_ci default NULL,
  `map10k` varchar(11) collate utf8_unicode_ci default NULL,
  `fourFigure` varchar(6) collate utf8_unicode_ci default NULL,
  `comments` text collate utf8_unicode_ci,
  `record_method` text collate utf8_unicode_ci,
  `organiser` varchar(30) collate utf8_unicode_ci default NULL,
  `date_from` date NOT NULL,
  `date_to` date default NULL,
  `createdBy` int(11) default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `date_from` (`date_from`)
) ENGINE=MyISAM AUTO_INCREMENT=195 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Rally locations';

-- --------------------------------------------------------

-- 
-- Table structure for table `rallyXflo`
-- 
-- Creation: Sep 20, 2010 at 01:14 PM
-- Last update: Dec 12, 2011 at 02:35 PM
-- 

CREATE TABLE `rallyXflo` (
  `id` int(11) NOT NULL auto_increment,
  `rallyID` int(11) default NULL,
  `staffID` int(11) default NULL,
  `dateFrom` date default NULL,
  `dateTo` date default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rallyID` (`rallyID`),
  KEY `staffID` (`staffID`)
) ENGINE=MyISAM AUTO_INCREMENT=146 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Flos attending a rally';

-- --------------------------------------------------------

-- 
-- Table structure for table `reeceperiods`
-- 
-- Creation: Nov 25, 2010 at 03:48 PM
-- Last update: Nov 25, 2010 at 03:48 PM
-- 

CREATE TABLE `reeceperiods` (
  `id` int(11) NOT NULL auto_increment,
  `period_name` varchar(255) collate utf8_unicode_ci default NULL,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  `date_range` varchar(255) collate utf8_unicode_ci default NULL,
  `old_period` varchar(255) collate utf8_unicode_ci default NULL,
  `period` int(11) default NULL,
  `valid` smallint(6) default NULL,
  `createdBy` tinyint(6) default NULL,
  `created` datetime default NULL,
  `updatedBy` tinyint(6) default NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `valid` (`valid`),
  KEY `updatedBy` (`updatedBy`),
  KEY `period_name` (`period_name`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `reeceperiods_rulers`
-- 
-- Creation: Sep 20, 2010 at 01:07 PM
-- Last update: Sep 20, 2010 at 01:07 PM
-- Last check: Sep 20, 2010 at 01:07 PM
-- 

CREATE TABLE `reeceperiods_rulers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ruler_id` int(10) unsigned NOT NULL default '0',
  `reeceperiod_id` int(10) unsigned NOT NULL default '0',
  `periodID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `idx_rulerperiod` (`ruler_id`,`periodID`)
) ENGINE=MyISAM AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `regions`
-- 
-- Creation: Sep 20, 2010 at 12:36 PM
-- Last update: Sep 20, 2010 at 12:36 PM
-- 

CREATE TABLE `regions` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `region` varchar(50) collate utf8_unicode_ci default NULL,
  `valid` tinyint(4) NOT NULL default '0',
  `modified` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `region` (`region`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `reliability`
-- 
-- Creation: Sep 04, 2010 at 10:08 AM
-- Last update: Sep 04, 2010 at 10:08 AM
-- 

CREATE TABLE `reliability` (
  `id` int(1) NOT NULL auto_increment,
  `term` varchar(25) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Reliability of evidence';

-- --------------------------------------------------------

-- 
-- Table structure for table `replies`
-- 
-- Creation: May 06, 2010 at 02:46 PM
-- Last update: Dec 06, 2011 at 10:49 PM
-- 

CREATE TABLE `replies` (
  `id` int(11) NOT NULL auto_increment,
  `messagetext` text collate utf8_unicode_ci,
  `messageID` int(11) default NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `messageID` (`messageID`)
) ENGINE=MyISAM AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Replies to submitted messages';

-- --------------------------------------------------------

-- 
-- Table structure for table `researchprojects`
-- 
-- Creation: Nov 25, 2010 at 05:11 PM
-- Last update: Dec 07, 2011 at 02:21 PM
-- Last check: Nov 25, 2010 at 05:11 PM
-- 

CREATE TABLE `researchprojects` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` text collate utf8_unicode_ci,
  `description` text collate utf8_unicode_ci,
  `investigator` varchar(255) collate utf8_unicode_ci default NULL,
  `level` tinyint(1) default NULL,
  `startDate` date default NULL,
  `endDate` date default NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) unsigned default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) unsigned default NULL,
  `valid` tinyint(1) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `level` (`level`),
  KEY `valid` (`valid`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=299 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='List of research projects';

-- --------------------------------------------------------

-- 
-- Table structure for table `reverses`
-- 
-- Creation: Sep 20, 2010 at 11:52 AM
-- Last update: Oct 12, 2010 at 04:51 PM
-- 

CREATE TABLE `reverses` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(25) collate utf8_unicode_ci default NULL,
  `wikipediaName` varchar(55) collate utf8_unicode_ci default NULL,
  `zoomer` varchar(55) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `image` varchar(55) collate utf8_unicode_ci default NULL,
  `updated` datetime default '0000-00-00 00:00:00',
  `type` varchar(25) collate utf8_unicode_ci default NULL,
  `attrib1` varchar(25) collate utf8_unicode_ci default NULL,
  `attrib2` varchar(25) collate utf8_unicode_ci default NULL,
  `attrib3` varchar(25) collate utf8_unicode_ci default NULL,
  `greek` varchar(55) collate utf8_unicode_ci default NULL,
  `updatedBy` int(11) default NULL,
  `created` datetime default '0000-00-00 00:00:00',
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `revtypes`
-- 
-- Creation: Nov 25, 2010 at 05:13 PM
-- Last update: Nov 25, 2010 at 05:13 PM
-- Last check: Nov 25, 2010 at 05:13 PM
-- 

CREATE TABLE `revtypes` (
  `id` int(11) NOT NULL auto_increment,
  `type` text collate utf8_unicode_ci,
  `translation` tinytext collate utf8_unicode_ci,
  `description` text collate utf8_unicode_ci,
  `gendate` varchar(100) collate utf8_unicode_ci default NULL,
  `reeceID` int(2) NOT NULL,
  `common` enum('1','2') collate utf8_unicode_ci default NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` varchar(11) character set latin1 NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `common` (`common`),
  KEY `reeceID` (`reeceID`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=708 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fourth Century reverse types, Roman coins';

-- --------------------------------------------------------

-- 
-- Table structure for table `roles`
-- 
-- Creation: Sep 20, 2010 at 01:07 PM
-- Last update: May 30, 2011 at 05:20 PM
-- 

CREATE TABLE `roles` (
  `id` int(2) unsigned NOT NULL auto_increment,
  `role` varchar(50) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  `updated` datetime default NULL,
  `updatedBy` int(11) default '56',
  PRIMARY KEY  (`id`),
  KEY `role` (`role`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='User roles';

-- --------------------------------------------------------

-- 
-- Table structure for table `romandenoms`
-- 
-- Creation: Sep 20, 2010 at 01:16 PM
-- Last update: Sep 20, 2010 at 01:16 PM
-- 

CREATE TABLE `romandenoms` (
  `id` int(3) NOT NULL auto_increment,
  `pasID` int(11) NOT NULL,
  `denomination` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `rarity` text collate utf8_unicode_ci,
  `weight` varchar(100) collate utf8_unicode_ci default NULL,
  `metal` varchar(100) collate utf8_unicode_ci default NULL,
  `diameter` varchar(100) collate utf8_unicode_ci default NULL,
  `thickness` varchar(100) collate utf8_unicode_ci default NULL,
  `design` varchar(100) collate utf8_unicode_ci default NULL,
  `obverse` varchar(255) collate utf8_unicode_ci default NULL,
  `reverse` varchar(255) collate utf8_unicode_ci default NULL,
  `category` varchar(100) collate utf8_unicode_ci default NULL,
  `created_by` int(3) NOT NULL default '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_by` int(3) NOT NULL default '0',
  `updated_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `romanmints`
-- 
-- Creation: Nov 22, 2010 at 10:34 PM
-- Last update: Nov 25, 2010 at 05:15 PM
-- Last check: Nov 25, 2010 at 05:15 PM
-- 

CREATE TABLE `romanmints` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `pasID` int(3) NOT NULL default '0',
  `latitude` double default NULL,
  `longitude` double default NULL,
  `abbrev` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(3) NOT NULL default '0',
  `updated_by` int(3) NOT NULL default '0',
  `updated_on` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `pasID` (`pasID`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `rulerImages`
-- 
-- Creation: Nov 25, 2010 at 04:37 PM
-- Last update: Nov 25, 2010 at 04:37 PM
-- Last check: Nov 25, 2010 at 04:37 PM
-- 

CREATE TABLE `rulerImages` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `filename` varchar(255) collate utf8_unicode_ci default NULL,
  `caption` text collate utf8_unicode_ci,
  `rulerID` int(11) default NULL,
  `zoomroute` varchar(255) collate utf8_unicode_ci default NULL,
  `filesize` varchar(100) collate utf8_unicode_ci default NULL,
  `mimetype` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `rulerID` (`rulerID`)
) ENGINE=MyISAM AUTO_INCREMENT=156 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Ruler images';

-- --------------------------------------------------------

-- 
-- Table structure for table `ruler_reversetype`
-- 
-- Creation: Nov 23, 2010 at 05:22 PM
-- Last update: Nov 23, 2010 at 05:22 PM
-- Last check: Nov 23, 2010 at 05:22 PM
-- 

CREATE TABLE `ruler_reversetype` (
  `id` int(11) NOT NULL auto_increment,
  `reverseID` int(4) default NULL,
  `rulerID` int(4) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  PRIMARY KEY  (`id`),
  KEY `rulerID` (`rulerID`),
  KEY `reverseID` (`reverseID`)
) ENGINE=MyISAM AUTO_INCREMENT=2221 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0 COMMENT='Reverse types to ruler link table';

-- --------------------------------------------------------

-- 
-- Table structure for table `rulers`
-- 
-- Creation: Nov 25, 2010 at 04:35 PM
-- Last update: Dec 04, 2011 at 11:22 AM
-- Last check: Nov 25, 2010 at 04:35 PM
-- 

CREATE TABLE `rulers` (
  `id` int(11) NOT NULL auto_increment,
  `period` int(11) default NULL,
  `issuer` char(255) default NULL,
  `country` int(11) default NULL,
  `region` char(255) default NULL,
  `date1` smallint(6) default NULL,
  `date2` smallint(6) default NULL,
  `valid` smallint(6) default NULL,
  `display` tinyint(1) NOT NULL default '1',
  `created` datetime default NULL,
  `createdBy` char(255) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `issuer` (`issuer`),
  KEY `country` (`country`),
  KEY `display` (`display`),
  KEY `date1` (`date1`),
  KEY `date2` (`date2`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=2244 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `savedSearches`
-- 
-- Creation: Nov 25, 2010 at 04:08 PM
-- Last update: Dec 12, 2011 at 10:31 PM
-- Last check: Nov 25, 2010 at 04:08 PM
-- 

CREATE TABLE `savedSearches` (
  `id` int(11) NOT NULL auto_increment,
  `searchString` text collate utf8_unicode_ci,
  `title` text collate utf8_unicode_ci,
  `description` text collate utf8_unicode_ci,
  `public` tinyint(1) default NULL,
  `userID` int(11) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `userID` (`userID`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `public` (`public`)
) ENGINE=MyISAM AUTO_INCREMENT=690 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Saved searchs referenced to users';

-- --------------------------------------------------------

-- 
-- Table structure for table `scheduledMonuments`
-- 
-- Creation: Nov 23, 2010 at 05:07 PM
-- Last update: Nov 23, 2010 at 05:07 PM
-- Last check: Nov 23, 2010 at 05:07 PM
-- 

CREATE TABLE `scheduledMonuments` (
  `id` int(11) NOT NULL auto_increment,
  `county` varchar(100) collate utf8_unicode_ci default NULL,
  `district` varchar(100) collate utf8_unicode_ci default NULL,
  `parish` varchar(100) collate utf8_unicode_ci default NULL,
  `monumentNumber` int(11) default NULL,
  `monumentName` text collate utf8_unicode_ci,
  `dateScheduled` date default NULL,
  `gridref` varchar(18) collate utf8_unicode_ci default NULL,
  `fourFigure` varchar(8) collate utf8_unicode_ci default NULL,
  `map25k` varchar(10) collate utf8_unicode_ci default NULL,
  `map10k` varchar(10) collate utf8_unicode_ci default NULL,
  `easting` int(10) default NULL,
  `northing` int(10) default NULL,
  `lat` double default NULL,
  `lon` double default NULL,
  `woeid` int(11) default NULL,
  `elevation` int(11) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `gridref` (`gridref`),
  KEY `fourFigure` (`fourFigure`),
  KEY `lat` (`lat`),
  KEY `lon` (`lon`)
) ENGINE=MyISAM AUTO_INCREMENT=25047 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Scheduled monuments';

-- --------------------------------------------------------

-- 
-- Table structure for table `searches`
-- 
-- Creation: Sep 20, 2010 at 12:59 PM
-- Last update: Dec 13, 2011 at 09:59 AM
-- 

CREATE TABLE `searches` (
  `id` int(11) NOT NULL auto_increment,
  `searchString` text collate utf8_unicode_ci,
  `date` datetime default NULL,
  `userid` int(11) NOT NULL,
  `ipaddress` varchar(16) collate utf8_unicode_ci default NULL,
  `useragent` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6582583 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `slides`
-- 
-- Creation: Feb 16, 2011 at 08:37 AM
-- Last update: Dec 13, 2011 at 09:58 AM
-- Last check: Feb 16, 2011 at 08:37 AM
-- 

CREATE TABLE `slides` (
  `imageID` int(11) unsigned NOT NULL auto_increment,
  `type` varchar(30) collate utf8_unicode_ci default NULL,
  `filename` varchar(100) collate utf8_unicode_ci default NULL,
  `filesize` int(10) unsigned default NULL,
  `filedate` datetime default NULL,
  `label` text collate utf8_unicode_ci,
  `period` varchar(30) collate utf8_unicode_ci default NULL,
  `country` varchar(20) collate utf8_unicode_ci default NULL,
  `keywords` varchar(50) collate utf8_unicode_ci default NULL,
  `filecreated` datetime default NULL,
  `imagecreated` smallint(4) unsigned default NULL,
  `imagerights` varchar(100) collate utf8_unicode_ci default NULL,
  `imagesite` varchar(100) collate utf8_unicode_ci default NULL,
  `fileowner` int(10) unsigned NOT NULL default '0',
  `attrmodified` datetime default NULL,
  `filecopyright` varchar(100) collate utf8_unicode_ci default NULL,
  `imagetitle` varchar(100) collate utf8_unicode_ci default NULL,
  `county` varchar(100) collate utf8_unicode_ci default NULL,
  `imagecreator` varchar(100) collate utf8_unicode_ci default NULL,
  `imagesource` varchar(100) collate utf8_unicode_ci default NULL,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `updated` datetime default '0000-00-00 00:00:00',
  `updatedBy` int(10) unsigned default '0',
  `createdBy` int(10) unsigned default '0',
  `created` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`imageID`),
  KEY `imagecreator` (`imagecreator`),
  KEY `county` (`county`),
  KEY `filename` (`filename`),
  KEY `secuid` (`secuid`),
  KEY `createdBy` (`createdBy`),
  KEY `period` (`period`),
  FULLTEXT KEY `label` (`label`)
) ENGINE=MyISAM AUTO_INCREMENT=360351 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `socialcount`
-- 
-- Creation: Sep 20, 2010 at 12:46 PM
-- Last update: Sep 20, 2010 at 12:46 PM
-- 

CREATE TABLE `socialcount` (
  `id` mediumint(9) NOT NULL auto_increment,
  `type` varchar(15) collate utf8_unicode_ci default NULL,
  `time` bigint(11) NOT NULL default '0',
  `url` varchar(150) collate utf8_unicode_ci default NULL,
  `alturl` varchar(150) collate utf8_unicode_ci default NULL,
  `count` mediumint(9) NOT NULL,
  `altcount` mediumint(9) NOT NULL,
  `optcount` mediumint(9) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `staff`
-- 
-- Creation: Sep 20, 2010 at 12:45 PM
-- Last update: Nov 22, 2011 at 11:33 AM
-- 

CREATE TABLE `staff` (
  `id` int(4) NOT NULL auto_increment,
  `firstname` varchar(255) collate utf8_unicode_ci default NULL,
  `lastname` varchar(255) collate utf8_unicode_ci default NULL,
  `role` varchar(255) collate utf8_unicode_ci default NULL,
  `dbaseID` int(11) default '0',
  `email_one` varchar(255) collate utf8_unicode_ci default NULL,
  `email_two` varchar(255) collate utf8_unicode_ci default NULL,
  `address_1` varchar(255) collate utf8_unicode_ci default NULL,
  `address_2` varchar(255) collate utf8_unicode_ci default NULL,
  `town` varchar(255) collate utf8_unicode_ci default NULL,
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `postcode` varchar(15) collate utf8_unicode_ci default NULL,
  `country` varchar(15) collate utf8_unicode_ci default NULL,
  `longitude` double default '0',
  `latitude` double default '0',
  `woeid` int(11) default NULL,
  `identifier` varchar(10) collate utf8_unicode_ci default NULL,
  `region` int(2) default '0',
  `telephone` varchar(255) collate utf8_unicode_ci default NULL,
  `fax` varchar(255) collate utf8_unicode_ci default NULL,
  `website` varchar(255) collate utf8_unicode_ci default NULL,
  `profile` text collate utf8_unicode_ci,
  `image` varchar(100) collate utf8_unicode_ci default NULL,
  `updatedBy` int(3) default '0',
  `updated` datetime default '0000-00-00 00:00:00',
  `createdBy` int(11) default '0',
  `created` datetime default '0000-00-00 00:00:00',
  `alumni` enum('1') collate utf8_unicode_ci default NULL,
  `blog_path` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `dbaseID` (`dbaseID`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `staffregions`
-- 
-- Creation: Nov 25, 2010 at 04:07 PM
-- Last update: Nov 25, 2010 at 04:07 PM
-- Last check: Nov 25, 2010 at 04:07 PM
-- 

CREATE TABLE `staffregions` (
  `ID` int(4) NOT NULL auto_increment,
  `regionID` int(11) NOT NULL default '0',
  `prefix` varchar(6) collate utf8_unicode_ci default NULL,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  `notes` varchar(255) collate utf8_unicode_ci default NULL,
  `county_map` varchar(100) collate utf8_unicode_ci default NULL,
  `kml_file` varchar(100) collate utf8_unicode_ci default NULL,
  `host` varchar(100) collate utf8_unicode_ci default NULL,
  `created_by` int(11) NOT NULL default '0',
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_by` int(11) NOT NULL default '0',
  `updated_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `rssfeed` text collate utf8_unicode_ci,
  PRIMARY KEY  (`ID`),
  KEY `prefix` (`prefix`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `staffroles`
-- 
-- Creation: Sep 20, 2010 at 12:09 PM
-- Last update: Sep 20, 2010 at 12:09 PM
-- 

CREATE TABLE `staffroles` (
  `id` int(11) NOT NULL auto_increment,
  `role` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `valid` tinyint(4) default NULL,
  `createdBy` int(11) default NULL,
  `created` datetime default NULL,
  `updatedBy` int(11) default NULL,
  `updated` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `statuses`
-- 
-- Creation: Sep 20, 2010 at 12:43 PM
-- Last update: Sep 20, 2010 at 12:43 PM
-- 

CREATE TABLE `statuses` (
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `subperiods`
-- 
-- Creation: Sep 09, 2010 at 09:03 AM
-- Last update: Sep 09, 2010 at 09:03 AM
-- 

CREATE TABLE `subperiods` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(11) unsigned default '1',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Table structure for table `subsequentActions`
-- 
-- Creation: Sep 20, 2010 at 12:53 PM
-- Last update: Oct 21, 2011 at 05:37 PM
-- 

CREATE TABLE `subsequentActions` (
  `id` int(11) NOT NULL auto_increment,
  `action` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `action` (`action`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Subsequent actions by flos';

-- --------------------------------------------------------

-- 
-- Table structure for table `suggestedResearch`
-- 
-- Creation: Aug 24, 2010 at 10:20 PM
-- Last update: Dec 05, 2011 at 09:59 AM
-- 

CREATE TABLE `suggestedResearch` (
  `id` int(11) NOT NULL auto_increment,
  `title` text collate utf8_unicode_ci,
  `description` text collate utf8_unicode_ci,
  `period` int(2) default NULL,
  `level` int(2) default NULL,
  `taken` tinyint(1) NOT NULL,
  `created` datetime default NULL,
  `createdBy` int(4) default NULL,
  `updated` datetime default NULL,
  `updatedBy` bigint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Suggested research topice from the Scheme';

-- --------------------------------------------------------

-- 
-- Table structure for table `surftreatments`
-- 
-- Creation: Nov 23, 2010 at 05:19 PM
-- Last update: Nov 23, 2010 at 05:19 PM
-- 

CREATE TABLE `surftreatments` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` enum('1','2') collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default '56',
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `valid` (`valid`),
  KEY `updatedBy` (`updatedBy`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `systemroles`
-- 
-- Creation: Sep 20, 2010 at 12:46 PM
-- Last update: Sep 20, 2010 at 12:46 PM
-- 

CREATE TABLE `systemroles` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `role` varchar(50) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='System roles on database';

-- --------------------------------------------------------

-- 
-- Table structure for table `taggedcontent`
-- 
-- Creation: Sep 20, 2010 at 01:15 PM
-- Last update: Sep 20, 2010 at 01:15 PM
-- 

CREATE TABLE `taggedcontent` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(255) collate utf8_unicode_ci default NULL,
  `type` varchar(100) collate utf8_unicode_ci default NULL,
  `path` text collate utf8_unicode_ci,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Content tags';

-- --------------------------------------------------------

-- 
-- Table structure for table `tempfindspots`
-- 
-- Creation: Sep 20, 2010 at 01:15 PM
-- Last update: Sep 20, 2010 at 01:15 PM
-- 

CREATE TABLE `tempfindspots` (
  `id` int(6) NOT NULL auto_increment,
  `knownas` varchar(255) collate utf8_unicode_ci default NULL,
  `parish` varchar(255) collate utf8_unicode_ci default NULL,
  `district` varchar(255) collate utf8_unicode_ci default NULL,
  `county` varchar(255) collate utf8_unicode_ci default NULL,
  `gridref` varchar(50) collate utf8_unicode_ci default NULL,
  `easting` varchar(50) collate utf8_unicode_ci default NULL,
  `northing` varchar(50) collate utf8_unicode_ci default NULL,
  `smr_ref` varchar(50) collate utf8_unicode_ci default NULL,
  `findspot_desc` text collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1173 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `thes_chronuk2`
-- 
-- Creation: Sep 20, 2010 at 01:15 PM
-- Last update: Sep 20, 2010 at 01:15 PM
-- 

CREATE TABLE `thes_chronuk2` (
  `id` int(11) NOT NULL auto_increment,
  `termID` int(11) NOT NULL default '0',
  `partof` int(11) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `treasureActionTypes`
-- 
-- Creation: Nov 01, 2010 at 03:36 PM
-- Last update: Nov 01, 2010 at 03:36 PM
-- 

CREATE TABLE `treasureActionTypes` (
  `id` int(3) NOT NULL auto_increment,
  `action` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actions that can be used in Treasure management';

-- --------------------------------------------------------

-- 
-- Table structure for table `treasureActions`
-- 
-- Creation: Oct 28, 2010 at 02:56 PM
-- Last update: Nov 11, 2010 at 03:00 PM
-- Last check: Oct 28, 2010 at 02:56 PM
-- 

CREATE TABLE `treasureActions` (
  `id` int(11) NOT NULL auto_increment,
  `treasureID` varchar(25) collate utf8_unicode_ci NOT NULL,
  `actionID` int(2) NOT NULL,
  `actionTaken` text collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Actions associated with Treasure case';

-- --------------------------------------------------------

-- 
-- Table structure for table `treasureAssignations`
-- 
-- Creation: Oct 28, 2010 at 03:03 PM
-- Last update: Nov 11, 2010 at 03:18 PM
-- 

CREATE TABLE `treasureAssignations` (
  `id` int(11) NOT NULL auto_increment,
  `treasureID` varchar(25) collate utf8_unicode_ci NOT NULL,
  `curatorID` varchar(55) collate utf8_unicode_ci NOT NULL,
  `chaseDate` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Assignations for Treasure cases';

-- --------------------------------------------------------

-- 
-- Table structure for table `treasureStatus`
-- 
-- Creation: Nov 03, 2010 at 02:44 PM
-- Last update: Nov 03, 2010 at 02:44 PM
-- Last check: Nov 03, 2010 at 02:44 PM
-- 

CREATE TABLE `treasureStatus` (
  `id` tinyint(2) NOT NULL auto_increment,
  `treasureID` varchar(25) collate utf8_unicode_ci NOT NULL,
  `status` varchar(255) collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `treasureID` (`treasureID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Status of Treasure case';

-- --------------------------------------------------------

-- 
-- Table structure for table `treasureStatusTypes`
-- 
-- Creation: Nov 11, 2010 at 02:22 PM
-- Last update: Nov 17, 2010 at 12:55 PM
-- 

CREATE TABLE `treasureStatusTypes` (
  `id` int(3) NOT NULL auto_increment,
  `action` varchar(255) collate utf8_unicode_ci default NULL,
  `description` text collate utf8_unicode_ci,
  `valid` tinyint(1) NOT NULL default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `valid` (`valid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure management status list';

-- --------------------------------------------------------

-- 
-- Table structure for table `treasureValuations`
-- 
-- Creation: Nov 11, 2010 at 03:19 PM
-- Last update: Nov 11, 2010 at 03:43 PM
-- 

CREATE TABLE `treasureValuations` (
  `id` int(11) NOT NULL auto_increment,
  `treasureID` varchar(25) collate utf8_unicode_ci NOT NULL,
  `valuerID` varchar(55) collate utf8_unicode_ci NOT NULL,
  `value` double unsigned default NULL,
  `comments` text collate utf8_unicode_ci NOT NULL,
  `dateOfValuation` date NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Valuations for Treasure cases';

-- --------------------------------------------------------

-- 
-- Table structure for table `tvcDates`
-- 
-- Creation: Nov 08, 2010 at 01:37 PM
-- Last update: Nov 17, 2010 at 02:28 PM
-- Last check: Nov 08, 2010 at 01:37 PM
-- 

CREATE TABLE `tvcDates` (
  `id` int(11) NOT NULL auto_increment,
  `secuid` varchar(50) collate utf8_unicode_ci default NULL,
  `date` date default NULL,
  `location` varchar(100) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `secuid` (`secuid`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure Valuation Committe dates';

-- --------------------------------------------------------

-- 
-- Table structure for table `tvcDatesToCases`
-- 
-- Creation: Nov 08, 2010 at 01:36 PM
-- Last update: Nov 11, 2010 at 03:17 PM
-- 

CREATE TABLE `tvcDatesToCases` (
  `id` int(11) NOT NULL auto_increment,
  `treasureID` varchar(50) collate utf8_unicode_ci NOT NULL,
  `tvcID` varchar(50) collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `treasureID` (`treasureID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Treasure Valuation committe to object ';

-- --------------------------------------------------------

-- 
-- Table structure for table `userOnlineAccounts`
-- 
-- Creation: Nov 22, 2010 at 11:40 PM
-- Last update: Oct 16, 2011 at 11:41 AM
-- 

CREATE TABLE `userOnlineAccounts` (
  `id` int(11) NOT NULL auto_increment,
  `account` varchar(50) collate utf8_unicode_ci default NULL,
  `accountName` varchar(200) collate utf8_unicode_ci default NULL,
  `userID` int(11) default NULL,
  `public` tinyint(1) NOT NULL default '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `public` (`public`),
  KEY `userID` (`userID`),
  KEY `accountName` (`accountName`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='online accounts for users for foaf';

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 
-- Creation: Nov 26, 2010 at 02:43 PM
-- Last update: Dec 13, 2011 at 09:57 AM
-- Last check: Nov 26, 2010 at 02:43 PM
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(255) collate utf8_unicode_ci default NULL,
  `role` varchar(10) collate utf8_unicode_ci default NULL,
  `seclevel` smallint(6) unsigned default '0',
  `password` varchar(250) collate utf8_unicode_ci default NULL,
  `institution` varchar(100) collate utf8_unicode_ci default NULL,
  `copyright` text collate utf8_unicode_ci,
  `phone` varchar(60) collate utf8_unicode_ci default NULL,
  `email` varchar(60) collate utf8_unicode_ci default NULL,
  `fax` varchar(60) collate utf8_unicode_ci default NULL,
  `lastvisit` varchar(60) collate utf8_unicode_ci default NULL,
  `fullname` varchar(60) collate utf8_unicode_ci default NULL,
  `first_name` varchar(255) collate utf8_unicode_ci default NULL,
  `last_name` varchar(255) collate utf8_unicode_ci default NULL,
  `activationKey` varchar(34) collate utf8_unicode_ci default NULL,
  `higherLevel` tinyint(1) default NULL,
  `researchOutline` text collate utf8_unicode_ci,
  `already` tinyint(1) default NULL,
  `reference` varchar(255) collate utf8_unicode_ci default NULL,
  `referenceEmail` varchar(255) collate utf8_unicode_ci default NULL,
  `session` varchar(60) collate utf8_unicode_ci default NULL,
  `visits` int(11) unsigned default '0',
  `imagedir` varchar(60) collate utf8_unicode_ci default 'images/',
  `path` varchar(60) collate utf8_unicode_ci default NULL,
  `webaddr` varchar(60) collate utf8_unicode_ci default NULL,
  `valid` tinyint(3) unsigned default '1',
  `peopleID` varchar(50) collate utf8_unicode_ci default NULL,
  `lastLogin` datetime default NULL,
  `avatar` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime default NULL,
  `createdBy` varchar(50) collate utf8_unicode_ci default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `role` (`role`),
  KEY `institution` (`institution`),
  KEY `lastLogin` (`lastLogin`),
  KEY `visits` (`visits`),
  KEY `email` (`email`),
  KEY `higherLevel` (`higherLevel`)
) ENGINE=MyISAM AUTO_INCREMENT=4057 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `usersAudit`
-- 
-- Creation: Sep 20, 2010 at 12:55 PM
-- Last update: Sep 20, 2010 at 12:55 PM
-- Last check: Sep 20, 2010 at 12:55 PM
-- 

CREATE TABLE `usersAudit` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `findID` int(11) default '0',
  `editID` varchar(25) collate utf8_unicode_ci default NULL,
  `fieldName` varchar(255) collate utf8_unicode_ci default NULL,
  `beforeValue` mediumtext collate utf8_unicode_ci,
  `afterValue` mediumtext collate utf8_unicode_ci,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `editID` (`editID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `usersEducation`
-- 
-- Creation: Sep 20, 2010 at 12:52 PM
-- Last update: Dec 05, 2011 at 05:08 PM
-- 

CREATE TABLE `usersEducation` (
  `id` int(11) NOT NULL auto_increment,
  `school` varchar(255) collate utf8_unicode_ci default NULL,
  `schoolUrl` varchar(255) collate utf8_unicode_ci default NULL,
  `subject` varchar(255) collate utf8_unicode_ci default NULL,
  `level` int(3) default NULL,
  `dateFrom` date default NULL,
  `dateTo` date default NULL,
  `userID` int(11) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `school` (`school`)
) ENGINE=MyISAM AUTO_INCREMENT=115 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users education for cross link and foaf';

-- --------------------------------------------------------

-- 
-- Table structure for table `usersInterests`
-- 
-- Creation: Sep 20, 2010 at 12:43 PM
-- Last update: Dec 05, 2011 at 05:09 PM
-- Last check: Sep 20, 2010 at 12:43 PM
-- 

CREATE TABLE `usersInterests` (
  `id` int(11) NOT NULL auto_increment,
  `interest` varchar(255) collate utf8_unicode_ci default NULL,
  `userID` int(11) default NULL,
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `interest` (`interest`)
) ENGINE=MyISAM AUTO_INCREMENT=436 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Users interests for cross linking and foad';

-- --------------------------------------------------------

-- 
-- Table structure for table `vacancies`
-- 
-- Creation: Nov 25, 2010 at 04:06 PM
-- Last update: Oct 20, 2011 at 02:04 PM
-- Last check: Nov 25, 2010 at 04:06 PM
-- 

CREATE TABLE `vacancies` (
  `id` int(2) NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci default NULL,
  `regionID` int(2) NOT NULL default '0',
  `specification` text collate utf8_unicode_ci,
  `salary` varchar(20) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('1','2') collate utf8_unicode_ci default '1',
  `live` date NOT NULL default '0000-00-00',
  `expire` date NOT NULL default '0000-00-00',
  `createdBy` int(3) NOT NULL default '0',
  `updatedBy` int(3) NOT NULL default '0',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `live` (`live`),
  KEY `expire` (`expire`),
  KEY `createdBy` (`createdBy`),
  KEY `updatedBy` (`updatedBy`),
  KEY `status` (`status`),
  KEY `regionID` (`regionID`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `vanarsdelltypes`
-- 
-- Creation: Mar 31, 2010 at 03:53 PM
-- Last update: Mar 31, 2010 at 03:53 PM
-- Last check: Mar 31, 2010 at 03:53 PM
-- 

CREATE TABLE `vanarsdelltypes` (
  `id` int(11) NOT NULL auto_increment,
  `type` float default NULL,
  `created` datetime default NULL,
  `createBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=844 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Van Arsdell types';

-- --------------------------------------------------------

-- 
-- Table structure for table `volunteers`
-- 
-- Creation: Sep 20, 2010 at 12:03 PM
-- Last update: Sep 20, 2010 at 12:03 PM
-- 

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL auto_increment,
  `title` text collate utf8_unicode_ci,
  `description` text collate utf8_unicode_ci,
  `managedBy` int(11) default NULL,
  `suitableFor` int(2) default NULL,
  `length` varchar(255) collate utf8_unicode_ci default NULL,
  `location` varchar(255) collate utf8_unicode_ci default NULL,
  `assignedTo` varchar(255) collate utf8_unicode_ci default NULL,
  `status` tinyint(1) default NULL,
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Volunteer opportunities with the scheme';

-- --------------------------------------------------------

-- 
-- Table structure for table `weartypes`
-- 
-- Creation: Nov 23, 2010 at 05:01 PM
-- Last update: Nov 23, 2010 at 05:01 PM
-- 

CREATE TABLE `weartypes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `term` varchar(255) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` tinyint(1) default '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `createdBy` (`createdBy`),
  KEY `valid` (`valid`),
  KEY `createdBy_2` (`createdBy`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `webServices`
-- 
-- Creation: Nov 22, 2010 at 11:40 PM
-- Last update: Oct 16, 2011 at 11:41 AM
-- 

CREATE TABLE `webServices` (
  `id` int(3) NOT NULL auto_increment,
  `service` varchar(255) collate utf8_unicode_ci default NULL,
  `serviceUrl` varchar(255) collate utf8_unicode_ci default NULL,
  `valid` tinyint(1) default '1',
  `created` datetime default NULL,
  `createdBy` int(11) default NULL,
  `updated` datetime default NULL,
  `updatedBy` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `service` (`service`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Web services for social networking';

-- --------------------------------------------------------

-- 
-- Table structure for table `workflowstages`
-- 
-- Creation: Aug 24, 2010 at 10:18 PM
-- Last update: Aug 24, 2010 at 10:18 PM
-- 

CREATE TABLE `workflowstages` (
  `id` int(2) NOT NULL auto_increment,
  `workflowstage` varchar(75) collate utf8_unicode_ci default NULL,
  `termdesc` text collate utf8_unicode_ci,
  `valid` enum('1','0') collate utf8_unicode_ci default '1',
  `created` datetime NOT NULL,
  `createdBy` int(11) NOT NULL,
  `updated` datetime NOT NULL,
  `updatedBy` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `workflowstage` (`workflowstage`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
