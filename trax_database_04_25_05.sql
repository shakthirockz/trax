-- phpMyAdmin SQL Dump
-- version 2.6.1-rc2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 24, 2005 at 02:26 AM
-- Server version: 4.0.24
-- PHP Version: 5.0.3
-- 
-- trax Backup 4/24/05
-- 
-- 
-- Database: `trax`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `AREAS`
-- 

CREATE TABLE `AREAS` (
  `NAME` varchar(128) NOT NULL default '',
  `ABBREV` varchar(12) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `AREAS`
-- 

INSERT INTO `AREAS` VALUES ('FT', '');
INSERT INTO `AREAS` VALUES ('Main', '');
INSERT INTO `AREAS` VALUES ('POD4', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `COMPLAINTS`
-- 

CREATE TABLE `COMPLAINTS` (
  `COMPLAINT` varchar(50) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `COMPLAINTS`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `MD_names`
-- 

CREATE TABLE `MD_names` (
  `FIRST_NAME` varchar(10) default NULL,
  `LAST_NAME` varchar(20) default NULL
) TYPE=MyISAM;

-- 
-- Dumping data for table `MD_names`
-- 

INSERT INTO `MD_names` VALUES ('Test', 'Doctor');

-- --------------------------------------------------------

-- 
-- Table structure for table `RN_assign_current`
-- 

CREATE TABLE `RN_assign_current` (
  `INT` int(10) unsigned NOT NULL auto_increment,
  `NAME` varchar(50) NOT NULL default '',
  `ROOM` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`INT`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `RN_assign_current`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `ROOMS`
-- 

CREATE TABLE `ROOMS` (
  `NAME` varchar(10) NOT NULL default 'UNK',
  `ER_TYPE` varchar(128) NOT NULL default '',
  `STATUS` varchar(10) default NULL,
  `MRN` varchar(20) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `ROOMS`
-- 

INSERT INTO `ROOMS` VALUES ('FT1', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('FT3', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('FT2', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('FT4', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('FT5', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('FT6', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('FT7', 'FT', '', '');
INSERT INTO `ROOMS` VALUES ('Tr_A', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Tr_B', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Tr_C', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('1A', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('1B', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('1C', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('1D', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('2C', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('2D', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('MS1', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('MS2', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('MS3', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('MS4', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('3A', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('3B', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('3D', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('ENT', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('OB/GYN', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Bed8', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Bed6', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Bed4', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Bed3', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Bed2', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Bed1', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Hall_1', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Hall_2', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Hall_3', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Hall_4', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Hall_5', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Hall_6', 'Main', '', '');
INSERT INTO `ROOMS` VALUES ('Bed7', 'POD4', '', '');
INSERT INTO `ROOMS` VALUES ('Bed5', 'POD4', '', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `complaints`
-- 

CREATE TABLE `complaints` (
  `COMP` varchar(50) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `complaints`
-- 

INSERT INTO `complaints` VALUES ('Chest Pain');
INSERT INTO `complaints` VALUES ('SOB');
INSERT INTO `complaints` VALUES ('ABD Pain');
INSERT INTO `complaints` VALUES ('Vag Bleed');
INSERT INTO `complaints` VALUES ('Laceration');
INSERT INTO `complaints` VALUES ('Head Injury');
INSERT INTO `complaints` VALUES ('Nausea/Vomiting');

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

CREATE TABLE `config` (
  `IND` int(11) NOT NULL auto_increment,
  `TRIAGE_INTERVAL` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`IND`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `config`
-- 

INSERT INTO `config` VALUES (2, 1800);

-- --------------------------------------------------------

-- 
-- Table structure for table `lobby`
-- 

CREATE TABLE `lobby` (
  `IND` int(10) unsigned NOT NULL auto_increment,
  `MRN` varchar(20) NOT NULL default '',
  `reg_time` timestamp(14) NOT NULL,
  `walkout` enum('yes','no') NOT NULL default 'no',
  `call_count` tinyint(4) NOT NULL default '0',
  `last_call_time` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`IND`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `lobby`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `message`
-- 

CREATE TABLE `message` (
  `IND` int(10) unsigned NOT NULL auto_increment,
  `MESSAGE` varchar(250) NOT NULL default '',
  `TARGET` enum('RN','MD','SECY') NOT NULL default 'RN',
  `STATUS` enum('new','read') NOT NULL default 'new',
  `MRN` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`IND`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `message`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `pt_status`
-- 

CREATE TABLE `pt_status` (
  `REC_NUM` bigint(20) NOT NULL auto_increment,
  `PATIENT_LAST_NAME` varchar(25) default NULL,
  `PATIENT_FIRST_NAME` varchar(15) default NULL,
  `MRN` varchar(12) default NULL,
  `ER_TYPE` varchar(56) NOT NULL default '',
  `ER_ROOM_NUMBER` varchar(6) default NULL,
  `PMD` varchar(12) default NULL,
  `CURRENT_STATUS` varchar(20) default NULL,
  `COMMENT` varchar(50) default NULL,
  `REG_TIME` datetime default NULL,
  `ADMIT_ROOM_NUM` varchar(10) default NULL,
  `CURRENT_LOCATION` varchar(15) default NULL,
  `ER_MD` varchar(15) default NULL,
  `EKG_STATUS` varchar(10) default NULL,
  `LAB_STATUS` varchar(15) NOT NULL default '',
  `CT_STATUS` varchar(15) NOT NULL default '',
  `SONO_STATUS` varchar(15) NOT NULL default '',
  `xray_STATUS` varchar(15) NOT NULL default '',
  `LOBBY_TYPE` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`REC_NUM`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- 
-- Dumping data for table `pt_status`
-- 

INSERT INTO `pt_status` VALUES (27, 'Smith', 'Bob', '1114288885', '', '', NULL, 'Discharged', NULL, '2005-04-23 15:41:50', NULL, NULL, '', '', '', '', '', '', 'NULL');

-- --------------------------------------------------------

-- 
-- Table structure for table `sign_in`
-- 

CREATE TABLE `sign_in` (
  `IND` int(10) unsigned NOT NULL auto_increment,
  `MRN` varchar(20) NOT NULL default '',
  `FIRST_NAME` varchar(15) NOT NULL default '',
  `LAST_NAME` varchar(20) NOT NULL default '',
  `TSTAMP` datetime NOT NULL default '0000-00-00 00:00:00',
  `TRIAGED` enum('yes','no') NOT NULL default 'yes',
  `WALKOUT` enum('yes','no') NOT NULL default 'yes',
  `CALL_COUNT` tinyint(4) NOT NULL default '0',
  `LAST_TRIAGE` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`IND`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sign_in`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `status_flags`
-- 

CREATE TABLE `status_flags` (
  `NAME` varchar(50) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `status_flags`
-- 

INSERT INTO `status_flags` VALUES ('Needs a Ride');
INSERT INTO `status_flags` VALUES ('OK to Discharge');

-- --------------------------------------------------------

-- 
-- Table structure for table `time_track`
-- 

CREATE TABLE `time_track` (
  `IND` bigint(20) unsigned NOT NULL auto_increment,
  `MRN` varchar(12) NOT NULL default '',
  `TM` timestamp(14) NOT NULL,
  `EVENT` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`IND`)
) TYPE=MyISAM AUTO_INCREMENT=53 ;

-- 
-- Dumping data for table `time_track`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `triage_data`
-- 

CREATE TABLE `triage_data` (
  `IND` int(10) unsigned NOT NULL auto_increment,
  `MRN` varchar(12) NOT NULL default '',
  `first_name` varchar(15) NOT NULL default '',
  `last_name` varchar(20) NOT NULL default '',
  `age` smallint(5) unsigned NOT NULL default '0',
  `sex` varchar(6) NOT NULL default '',
  `complaint` varchar(50) NOT NULL default '',
  `temp` smallint(5) unsigned NOT NULL default '0',
  `SBP` smallint(5) unsigned NOT NULL default '0',
  `DBP` smallint(5) unsigned NOT NULL default '0',
  `pulse` smallint(5) unsigned NOT NULL default '0',
  `resp` smallint(5) unsigned NOT NULL default '0',
  `pox` smallint(5) unsigned NOT NULL default '0',
  `glu` int(10) unsigned NOT NULL default '0',
  `pain_scale` tinyint(3) unsigned NOT NULL default '0',
  `pmh` varchar(250) NOT NULL default '',
  `meds` varchar(250) NOT NULL default '',
  `allergies` varchar(250) NOT NULL default '',
  `hx` blob NOT NULL,
  `acuity` tinyint(3) unsigned NOT NULL default '0',
  `LAST_TRIAGE` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`IND`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- 
-- Dumping data for table `triage_data`
-- 

INSERT INTO `triage_data` VALUES (27, '1114288885', 'Bob', 'Smith', 18, 'male', 'SOB', 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 3, '2005-04-23 15:41:50');

-- --------------------------------------------------------

-- 
-- Table structure for table `triage_form_modules`
-- 

CREATE TABLE `triage_form_modules` (
  `NAME` varchar(20) NOT NULL default '',
  `USED` enum('yes','no') NOT NULL default 'yes'
) TYPE=MyISAM;

-- 
-- Dumping data for table `triage_form_modules`
-- 

INSERT INTO `triage_form_modules` VALUES ('NAME', 'yes');
INSERT INTO `triage_form_modules` VALUES ('DEMOGRAPHICS', 'yes');
INSERT INTO `triage_form_modules` VALUES ('CC', 'yes');
INSERT INTO `triage_form_modules` VALUES ('VITALS', 'no');
INSERT INTO `triage_form_modules` VALUES ('PMH', 'no');
INSERT INTO `triage_form_modules` VALUES ('MEDS_ALLERGIES', 'no');
INSERT INTO `triage_form_modules` VALUES ('ASSIGN_WAITING_AREA', 'no');
INSERT INTO `triage_form_modules` VALUES ('HPI', 'no');
INSERT INTO `triage_form_modules` VALUES ('ACUITY', 'yes');
INSERT INTO `triage_form_modules` VALUES ('ROOM_ASSIGNMENT', 'yes');
