-- MySQL dump 10.11
--
-- Host: localhost    Database: minerengine
-- ------------------------------------------------------
-- Server version	5.0.88

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `BPAction`
--

DROP TABLE IF EXISTS `BPAction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction` (
  `bPActionId` int(10) unsigned NOT NULL auto_increment,
  `bPParentActionId` int(10) unsigned NOT NULL default '0',
  `bPId` int(10) unsigned NOT NULL default '0',
  `execRank` int(10) unsigned NOT NULL default '0',
  `inputParentRegexGroupNumber` int(10) unsigned NOT NULL default '0',
  `type` int(10) unsigned NOT NULL default '0',
  `useMatchAll` int(10) unsigned NOT NULL default '0',
  `isOpt` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction`
--

LOCK TABLES `BPAction` WRITE;
/*!40000 ALTER TABLE `BPAction` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_r_InjectedBPAction`
--

DROP TABLE IF EXISTS `BPAction_r_InjectedBPAction`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_r_InjectedBPAction` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `injectedActionId` int(10) unsigned NOT NULL default '0',
  `inputGroup` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`, `injectedActionId`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_r_InjectedBPAction`
--

LOCK TABLES `BPAction_r_InjectedBPAction` WRITE;
/*!40000 ALTER TABLE `BPAction_r_InjectedBPAction` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_r_InjectedBPAction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_CallbackMethod`
--

DROP TABLE IF EXISTS `BPAction_CallbackMethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_CallbackMethod` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `methodName` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`bPActionId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_CallbackMethod`
--

LOCK TABLES `BPAction_CallbackMethod` WRITE;
/*!40000 ALTER TABLE `BPAction_CallbackMethod` DISABLE KEYS */;
INSERT INTO `BPAction_CallbackMethod` VALUES (2,'rootLoop'),(5,'rootLoop');
/*!40000 ALTER TABLE `BPAction_CallbackMethod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_Data`
--

DROP TABLE IF EXISTS `BPAction_Data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_Data` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `data` text NOT NULL,
  PRIMARY KEY  (`bPActionId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_Data`
--

LOCK TABLES `BPAction_Data` WRITE;
/*!40000 ALTER TABLE `BPAction_Data` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_Data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_ErrorData`
--

DROP TABLE IF EXISTS `BPAction_ErrorData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_ErrorData` (
  `bPNIGPActionId` int(10) unsigned NOT NULL default '0',
  `nIGPLastInputData` text NOT NULL,
  `errorTriggerActionId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPNIGPActionId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_ErrorData`
--

LOCK TABLES `BPAction_ErrorData` WRITE;
/*!40000 ALTER TABLE `BPAction_ErrorData` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_ErrorData` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_RegexGroup_r_CallbackMethod_ParamNum`
--

DROP TABLE IF EXISTS `BPAction_RegexGroup_r_CallbackMethod_ParamNum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_RegexGroup_r_CallbackMethod_ParamNum` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `paramNum` int(10) unsigned NOT NULL default '0',
  `regexGroup` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`,`paramNum`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_RegexGroup_r_CallbackMethod_ParamNum`
--

LOCK TABLES `BPAction_RegexGroup_r_CallbackMethod_ParamNum` WRITE;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_CallbackMethod_ParamNum` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_CallbackMethod_ParamNum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_RegexGroup_r_Const`
--

DROP TABLE IF EXISTS `BPAction_RegexGroup_r_Const`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_RegexGroup_r_Const` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `regexGroup` int(10) unsigned NOT NULL default '0',
  `const` int(10) unsigned NOT NULL default '0',
  `isOpt` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`,`regexGroup`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_RegexGroup_r_Const`
--

LOCK TABLES `BPAction_RegexGroup_r_Const` WRITE;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_Const` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_Const` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_RegexGroup_r_MethodMethod`
--

DROP TABLE IF EXISTS `BPAction_RegexGroup_r_MethodMethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_RegexGroup_r_MethodMethod` (
  `bPAMMId` int(10) unsigned NOT NULL auto_increment,
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `methodId` int(10) unsigned NOT NULL default '0',
  `regexGroup` int(10) unsigned NOT NULL default '0',
  `interceptType` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPAMMId`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_RegexGroup_r_MethodMethod`
--

LOCK TABLES `BPAction_RegexGroup_r_MethodMethod` WRITE;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_MethodMethod` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_MethodMethod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BluePrint`
--

DROP TABLE IF EXISTS `BluePrint`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BluePrint` (
  `bPId` int(10) unsigned NOT NULL auto_increment,
  `host` varchar(255) NOT NULL default '',
  `newInstanceGeneratingPointActionId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BluePrint`
--

LOCK TABLES `BluePrint` WRITE;
/*!40000 ALTER TABLE `BluePrint` DISABLE KEYS */;
/*!40000 ALTER TABLE `BluePrint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BluePrint_CMPaths`
--

DROP TABLE IF EXISTS `BluePrint_CMPaths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BluePrint_CMPaths` (
  `bPId` int(10) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `pathType` int(10) unsigned NOT NULL default '0',
  `classType` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPId`,`pathType`,`classType`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BluePrint_CMPaths`
--

LOCK TABLES `BluePrint_CMPaths` WRITE;
/*!40000 ALTER TABLE `BluePrint_CMPaths` DISABLE KEYS */;
/*!40000 ALTER TABLE `BluePrint_CMPaths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BluePrint_MethodMethod`
--

DROP TABLE IF EXISTS `BluePrint_MethodMethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BluePrint_MethodMethod` (
  `methodId` int(10) unsigned NOT NULL auto_increment,
  `bPId` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`methodId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BluePrint_MethodMethod`
--

LOCK TABLES `BluePrint_MethodMethod` WRITE;
/*!40000 ALTER TABLE `BluePrint_MethodMethod` DISABLE KEYS */;
/*!40000 ALTER TABLE `BluePrint_MethodMethod` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-11-26  4:28:14
