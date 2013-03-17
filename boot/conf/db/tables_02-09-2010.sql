-- MySQL dump 10.11
--
-- Host: localhost    Database: Miner
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
  `inputParentRegexGroupNumber` int(10) unsigned NOT NULL default '0',
  `type` int(10) unsigned NOT NULL default '0',
  `useMatchAll` int(10) unsigned NOT NULL default '0',
  `isOpt` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction`
--

LOCK TABLES `BPAction` WRITE;
/*!40000 ALTER TABLE `BPAction` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_RegexGroup_r_Const`
-- maps every entity to the group where it is found

DROP TABLE IF EXISTS `BPAction_RegexGroup_r_Const`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_RegexGroup_r_Const` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `regexGroup` int(10) unsigned NOT NULL default '0',
  `const` int(10) unsigned NOT NULL default '0',
  `isOpt` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`, `regexGroup`)
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
-- Table structure for table `BluePrint_CMPaths`
-- The blue prints must have a directory where
-- the method and callback implementations files
-- are located. There are 3 type of paths :
-- -base path : enough for finding both method and callback
-- -method path
-- -callback path
-- base can go alone whereas the other two must have
-- one of their peers along

DROP TABLE IF EXISTS `BluePrint_CMPaths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BluePrint_CMPaths` (
  `bPId` int(10) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `pathType` int(10) unsigned NOT NULL default '0',
  `classType` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPId`, `pathType`, `classType`)
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
-- Table structure for table `BPAction_CallbackMethod`
-- associates the methods in blueprint's callback class
-- to each get contents action.
-- The filosofy is different here, the methods are created
-- custom to each action, that's why they don't deserve an
-- id

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
/*!40000 ALTER TABLE `BPAction_CallbackMethod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_RegexGroup_r_CallbackMethod_ParamNum`
-- as the callback methods are tied to their bpaction, we can
-- uniquely identify the method by the bPActionId
-- the param numbers for each method are unique; there not
-- one method with two param numbers that are the same

DROP TABLE IF EXISTS `BPAction_RegexGroup_r_CallbackMethod_ParamNum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_RegexGroup_r_CallbackMethod_ParamNum` (
  `bPActionId` int(10) unsigned NOT NULL default '0',
  `paramNum` int(10) unsigned NOT NULL default '0',
  `regexGroup` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bPActionId`, `paramNum`)
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
-- Table structure for table `BluePrint_MethodMethod`
-- contains the name of the methods in bluePrint's Method class
-- note that methods can be used by many different actions

DROP TABLE IF EXISTS `BluePrint_MethodMethod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BluePrint_MethodMethod` (
  `methodId` int(10) unsigned NOT NULL auto_increment,
  `bPId` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`methodId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BluePrint_MethodMethod`
--

LOCK TABLES `BluePrint_MethodMethod` WRITE;
/*!40000 ALTER TABLE `BluePrint_MethodMethod` DISABLE KEYS */;
/*!40000 ALTER TABLE `BluePrint_MethodMethod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_RegexGroup_r_MethodMethod`
-- there can be many methods per action

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BPAction_RegexGroup_r_MethodMethod`
--

LOCK TABLES `BPAction_RegexGroup_r_MethodMethod` WRITE;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_MethodMethod` DISABLE KEYS */;
/*!40000 ALTER TABLE `BPAction_RegexGroup_r_MethodMethod` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BPAction_Data`
--

DROP TABLE IF EXISTS `BPAction_Data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BPAction_Data` (
  `bPActionId` int(10) unsigned NOT NULL default 0,
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BluePrint`
--

LOCK TABLES `BluePrint` WRITE;
/*!40000 ALTER TABLE `BluePrint` DISABLE KEYS */;
/*!40000 ALTER TABLE `BluePrint` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Country`
--

DROP TABLE IF EXISTS `Country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country` (
  `countryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`countryId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Country`
--

LOCK TABLES `Country` WRITE;
/*!40000 ALTER TABLE `Country` DISABLE KEYS */;
INSERT INTO `Country` VALUES (4,'France','france'),(5,'Australia','australia'),(6,'Usa','usa'),(7,'Canada','canada'),(8,'Austria','austria'),(9,'Germany','germany'),(10,'Netherlands','netherlands'),(11,'Switzerland','switzerland'),(12,'Uk','uk'),(13,'Argentina','argentina'),(14,'Belgium','belgium'),(15,'Spain','spain'),(16,'Italy','italy'),(17,'Portugal','portugal');
/*!40000 ALTER TABLE `Country` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DirtyCountry`
-- When a country string can not be normalized it should
-- be added to this table
-- 

DROP TABLE IF EXISTS `DirtyCountry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DirtyCountry` (
  `dirtyCountryId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`dirtyCountryId`)
) ENGINE=MyISAM AUTO_INCREMENT=200 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DirtyCountry`
--

LOCK TABLES `DirtyCountry` WRITE;
/*!40000 ALTER TABLE `DirtyCountry` DISABLE KEYS */;
/*!40000 ALTER TABLE `DirtyCountry` ENABLE KEYS */;
UNLOCK TABLES;



--
-- Table structure for table `Country_Matcher`
--

DROP TABLE IF EXISTS `Country_Matcher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country_Matcher` (
  `priority` int(10) unsigned NOT NULL auto_increment,
  `countryId` int(10) unsigned NOT NULL default '0',
  `regex` text NOT NULL,
  PRIMARY KEY  (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Country_Matcher`
--

LOCK TABLES `Country_Matcher` WRITE;
/*!40000 ALTER TABLE `Country_Matcher` DISABLE KEYS */;
INSERT INTO `Country_Matcher` VALUES (18,4,'/Fran\\p{L}\\p{M}*(?:ia|[ea]|kreich)/iu'),(19,5,'/Austr\\p{L}\\p{M}*li(?:a|en)/iu'),(20,6,'/u.?s.?a.?|e.?e.?u.?u.?|Vereinigte[- _.]Staa?ten|Estados[- _.]Un\\p{L}\\p{M}*dos.*?(?:Am\\p{L}\\p{M}*rica)?|United[- _.]states.*?(?:America)?|\\p{L}\\p{M}*tats[-_. ]unis[-_. ]d?.?Am\\p{L}\\p{M}*rique|Stati[ -_.]Uniti(?:[ -_.]d.america)?/iu'),(21,7,'/[CK]anad\\p{L}\\p{M}*/iu'),(22,8,'/\\p{L}\\p{M}*e?sterreich|Au(?:stria|triche)/iu'),(23,9,'/German(?:y|ia)|Aleman[ih]a|Allemagne|Deutschland/iu'),(24,10,'/Pays-Bas|Pa\\p{L}\\p{M}*ses-Ba(?:j|ix)os|Paesi-Bassi|Niederlande|Netherlands/iu'),(25,11,'/Switzerland|Suisse|Suiza|Svizzera|Schweiz|Su\\p{L}\\p{M}*\\p{L}\\p{M}*a/iu'),(26,12,'/[UV].?K.?|United Kingdom|Britain|Royaume-Uni|Re[ig]no Uni[dt]o|Vereinigtes K(?:\\p{L}\\p{M}*|oe)nigreich|Gr\\p{L}\\p{M}*n(?:de)?[ -]Breta(?:gne|\\p{L}\\p{M}*a)|Great Britain/iu'),(27,13,'/Argentin(?:[ae]|ien)/iu'),(28,14,'/B\\p{L}\\p{M}*lgi(?:um|que|ca|en)/iu'),(29,15,'/Espa\\p{L}\\p{M}*[ae]|Spa(?:nien|gna)|Spain/iu'),(30,16,'/(?:It\\p{L}\\p{M}*l[yi](?:a|(?:en))?)/iu'),(31,17,'/Portugal|Portogallo/iu');

/*!40000 ALTER TABLE `Country_Matcher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Country_r_LangISOs`
--

DROP TABLE IF EXISTS `Country_r_LangISOs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country_r_LangISOs` (
  `countryId` int(10) unsigned NOT NULL default '0',
  `langISOId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`countryId`,`langISOId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Country_r_LangISOs`
--

LOCK TABLES `Country_r_LangISOs` WRITE;
/*!40000 ALTER TABLE `Country_r_LangISOs` DISABLE KEYS */;
INSERT INTO `Country_r_LangISOs` VALUES (4,38),(5,29),(6,29),(7,29),(7,38),(8,26),(9,26),(10,89),(11,26),(11,38),(11,57),(12,29),(13,31),(14,38),(15,21),(15,31),(15,33),(15,42),(16,57),(17,96);
/*!40000 ALTER TABLE `Country_r_LangISOs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Genre`
--

DROP TABLE IF EXISTS `Genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Genre` (
  `genreId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`genreId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Genre`
--

LOCK TABLES `Genre` WRITE;
/*!40000 ALTER TABLE `Genre` DISABLE KEYS */;
/*!40000 ALTER TABLE `Genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LangDirty`
--

DROP TABLE IF EXISTS `LangDirty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LangDirty` (
  `langDirtyId` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`langDirtyId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LangDirty`
--

LOCK TABLES `LangDirty` WRITE;
/*!40000 ALTER TABLE `LangDirty` DISABLE KEYS */;
INSERT INTO `LangDirty` VALUES (147,'franzosish'),(148,'romanch'),(149,'romanch'),(150,'romanch'),(151,'romanch'),(152,'romanch'),(153,'romanch'),(154,'romanch'),(155,'romanch'),(156,'romanch'),(157,'romanch'),(158,'romanch'),(159,'romanch'),(160,'romanch'),(161,'romanch'),(162,'or'),(163,'or'),(164,'or'),(165,'or'),(166,'or'),(167,'assameses');
/*!40000 ALTER TABLE `LangDirty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LangISO`
--

DROP TABLE IF EXISTS `LangISO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LangISO` (
  `langISOId` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`langISOId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LangISO`
--

LOCK TABLES `LangISO` WRITE;
/*!40000 ALTER TABLE `LangISO` DISABLE KEYS */;
INSERT INTO `LangISO` VALUES (4,'or','oriya'),(5,'as','assamese'),(6,'aa','afar'),(7,'ab','abkhazian'),(8,'af','afrikaans'),(9,'am','amharic'),(10,'ar','arabic'),(11,'ay','aymara'),(12,'az','azerbaijani'),(13,'ba','bashkir'),(14,'be','byelorussian'),(15,'bg','bulgarian'),(16,'bh','bihari'),(17,'bi','bislama'),(18,'bn','bengali'),(19,'bo','tibetan'),(20,'br','breton'),(21,'ca','catalan'),(22,'co','corsican'),(23,'cs','czech'),(24,'cy','welsh'),(25,'da','danish'),(26,'de','german'),(27,'dz','bhutani'),(28,'el','greek'),(29,'en','english'),(30,'eo','esperanto'),(31,'es','spanish'),(32,'et','estonian'),(33,'eu','basque'),(34,'fa','persian'),(35,'fi','finnish'),(36,'fj','fiji'),(37,'fo','faroese'),(38,'fr','french'),(39,'fy','frisian'),(40,'ga','irish'),(41,'gd','scots'),(42,'gl','galician'),(43,'gn','guarani'),(44,'gu','gujarati'),(45,'gv','manx'),(46,'ha','hausa'),(47,'he','hebrew'),(48,'hi','hindi'),(49,'hr','croatian'),(50,'hu','hungarian'),(51,'hy','armenian'),(52,'ia','interlingua'),(53,'id','indonesian'),(54,'ie','interlingue'),(55,'ik','inupiak'),(56,'is','icelandic'),(57,'it','italian'),(58,'iu','inuktitut'),(59,'ja','japanese'),(60,'jw','javanese'),(61,'ka','georgian'),(62,'kk','kazakh'),(63,'kl','greenlandic'),(64,'km','cambodian'),(65,'kn','kannada'),(66,'ko','korean'),(67,'ks','kashmiri'),(68,'ku','kurdish'),(69,'kw','cornish'),(70,'ky','kirghiz'),(71,'la','latin'),(72,'lb','luxemburgish'),(73,'ln','lingala'),(74,'lo','laothian'),(75,'lt','lithuanian'),(76,'lv','latvian'),(77,'mg','malagasy'),(78,'mi','maori'),(79,'mk','macedonian'),(80,'ml','malayalam'),(81,'mn','mongolian'),(82,'mo','moldavian'),(83,'mr','marathi'),(84,'ms','malay'),(85,'mt','maltese'),(86,'my','burmese'),(87,'na','nauru'),(88,'ne','nepali'),(89,'nl','dutch'),(90,'no','norwegian'),(91,'oc','occitan'),(92,'om','oromo'),(93,'pa','punjabi'),(94,'pl','polish'),(95,'ps','pashto'),(96,'pt','portuguese'),(97,'qu','quechua'),(98,'rm','rhaeto'),(99,'rn','kirundi'),(100,'ro','romanian'),(101,'ru','russian'),(102,'rw','kinyarwanda'),(103,'sa','sanskrit'),(104,'sd','sindhi'),(105,'se','northern'),(106,'sg','sangho'),(107,'sh','serbo'),(108,'si','singhalese'),(109,'sk','slovak'),(110,'sl','slovenian'),(111,'sm','samoan'),(112,'sn','shona'),(113,'so','somali'),(114,'sq','albanian'),(115,'sr','serbian'),(116,'ss','siswati'),(117,'st','sesotho'),(118,'su','sundanese'),(119,'sv','swedish'),(120,'sw','swahili'),(121,'ta','tamil'),(122,'te','telugu'),(123,'tg','tajik'),(124,'th','thai'),(125,'ti','tigrinya'),(126,'tk','turkmen'),(127,'tl','tagalog'),(128,'tn','setswana'),(129,'to','tonga'),(130,'tr','turkish'),(131,'ts','tsonga'),(132,'tt','tatar'),(133,'tw','twi'),(134,'ug','uigur'),(135,'uk','ukrainian'),(136,'ur','urdu'),(137,'uz','uzbek'),(138,'vi','vietnamese'),(139,'vo','volapuk'),(140,'wo','wolof'),(141,'xh','xhosa'),(142,'yi','yiddish'),(143,'yo','yoruba'),(144,'za','zhuang'),(145,'zh','chinese'),(146,'zu','zulu');
/*!40000 ALTER TABLE `LangISO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LangISO_Matcher`
--

DROP TABLE IF EXISTS `LangISO_Matcher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LangISO_Matcher` (
  `priority` int(10) unsigned NOT NULL auto_increment,
  `langISOId` int(10) unsigned NOT NULL default '0',
  `regex` text NOT NULL,
  PRIMARY KEY  (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LangISO_Matcher`
--

LOCK TABLES `LangISO_Matcher` WRITE;
/*!40000 ALTER TABLE `LangISO_Matcher` DISABLE KEYS */;
INSERT INTO `LangISO_Matcher` VALUES (318,4,'/oriya/ui'),(319,5,'/assamese/ui'),(320,6,'/afar/ui'),(321,7,'/abkhazian/ui'),(322,8,'/afrikaans/ui'),(323,9,'/amharic/ui'),(324,10,'/arabic/ui'),(325,11,'/aymara/ui'),(326,12,'/azerbaijani/ui'),(327,13,'/bashkir/ui'),(328,14,'/byelorussian/ui'),(329,15,'/bulgarian/ui'),(330,16,'/bihari/ui'),(331,17,'/bislama/ui'),(332,18,'/bengali/ui'),(333,19,'/tibetan/ui'),(334,20,'/breton/ui'),(335,21,'/catalan/ui'),(336,22,'/corsican/ui'),(337,23,'/czech/ui'),(338,24,'/welsh/ui'),(339,25,'/danish/ui'),(340,26,'/german/ui'),(341,27,'/bhutani/ui'),(342,28,'/greek/ui'),(343,29,'/english/ui'),(344,30,'/esperanto/ui'),(345,31,'/spanish/ui'),(346,32,'/estonian/ui'),(347,33,'/basque/ui'),(348,34,'/persian/ui'),(349,35,'/finnish/ui'),(350,36,'/fiji/ui'),(351,37,'/faroese/ui'),(352,38,'/french/ui'),(353,39,'/frisian/ui'),(354,40,'/uirish/ui'),(355,41,'/scots/ui'),(356,42,'/galician/ui'),(357,43,'/guarani/ui'),(358,44,'/gujarati/ui'),(359,45,'/manx/ui'),(360,46,'/hausa/ui'),(361,47,'/hebrew/ui'),(362,48,'/hindi/ui'),(363,49,'/croatian/ui'),(364,50,'/hungarian/ui'),(365,51,'/armenian/ui'),(366,52,'/uinterlingua/ui'),(367,53,'/uindonesian/ui'),(368,54,'/uinterlingue/ui'),(369,55,'/uinupiak/ui'),(370,56,'/uicelandic/ui'),(371,57,'/uitalian/ui'),(372,58,'/uinuktitut/ui'),(373,59,'/japanese/ui'),(374,60,'/javanese/ui'),(375,61,'/georgian/ui'),(376,62,'/kazakh/ui'),(377,63,'/greenlandic/ui'),(378,64,'/cambodian/ui'),(379,65,'/kannada/ui'),(380,66,'/korean/ui'),(381,67,'/kashmiri/ui'),(382,68,'/kurdish/ui'),(383,69,'/cornish/ui'),(384,70,'/kirghiz/ui'),(385,71,'/latin/ui'),(386,72,'/luxemburgish/ui'),(387,73,'/lingala/ui'),(388,74,'/laothian/ui'),(389,75,'/lithuanian/ui'),(390,76,'/latvian/ui'),(391,77,'/malagasy/ui'),(392,78,'/maori/ui'),(393,79,'/macedonian/ui'),(394,80,'/malayalam/ui'),(395,81,'/mongolian/ui'),(396,82,'/moldavian/ui'),(397,83,'/marathi/ui'),(398,84,'/malay/ui'),(399,85,'/maltese/ui'),(400,86,'/burmese/ui'),(401,87,'/nauru/ui'),(402,88,'/nepali/ui'),(403,89,'/dutch/ui'),(404,90,'/norwegian/ui'),(405,91,'/occitan/ui'),(406,92,'/oromo/ui'),(407,93,'/punjabi/ui'),(408,94,'/polish/ui'),(409,95,'/pashto/ui'),(410,96,'/portuguese/ui'),(411,97,'/quechua/ui'),(412,98,'/rhaeto/ui'),(413,99,'/kirundi/ui'),(414,100,'/romanian/ui'),(415,101,'/russian/ui'),(416,102,'/kinyarwanda/ui'),(417,103,'/sanskrit/ui'),(418,104,'/sindhi/ui'),(419,105,'/northern/ui'),(420,106,'/sangho/ui'),(421,107,'/serbo/ui'),(422,108,'/singhalese/ui'),(423,109,'/slovak/ui'),(424,110,'/slovenian/ui'),(425,111,'/samoan/ui'),(426,112,'/shona/ui'),(427,113,'/somali/ui'),(428,114,'/albanian/ui'),(429,115,'/serbian/ui'),(430,116,'/siswati/ui'),(431,117,'/sesotho/ui'),(432,118,'/sundanese/ui'),(433,119,'/swedish/ui'),(434,120,'/swahili/ui'),(435,121,'/tamil/ui'),(436,122,'/telugu/ui'),(437,123,'/tajik/ui'),(438,124,'/thai/ui'),(439,125,'/tigrinya/ui'),(440,126,'/turkmen/ui'),(441,127,'/tagalog/ui'),(442,128,'/setswana/ui'),(443,129,'/tonga/ui'),(444,130,'/turkish/ui'),(445,131,'/tsonga/ui'),(446,132,'/tatar/ui'),(447,133,'/twi/ui'),(448,134,'/uigur/ui'),(449,135,'/ukrainian/ui'),(450,136,'/urdu/ui'),(451,137,'/uzbek/ui'),(452,138,'/vietnamese/ui'),(453,139,'/volapuk/ui'),(454,140,'/wolof/ui'),(455,141,'/xhosa/ui'),(456,142,'/yiddish/ui'),(457,143,'/yoruba/ui'),(458,144,'/zhuang/ui'),(459,145,'/chinese/ui'),(460,146,'/zulu/ui');
/*!40000 ALTER TABLE `LangISO_Matcher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MIE`
--

DROP TABLE IF EXISTS `MIE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MIE` (
  `mIEId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default 'N/A',
  `countryId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`mIEId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MIE`
--

LOCK TABLES `MIE` WRITE;
/*!40000 ALTER TABLE `MIE` DISABLE KEYS */;
/*!40000 ALTER TABLE `MIE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MIERole`
--

DROP TABLE IF EXISTS `MIERole`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MIERole` (
  `mIERoleId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`mIERoleId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MIERole`
--

LOCK TABLES `MIERole` WRITE;
/*!40000 ALTER TABLE `MIERole` DISABLE KEYS */;
INSERT INTO `MIERole` (`name`,`slug`) VALUES ('Actor','actor'),('Producer','producer'),('Director','director');
/*!40000 ALTER TABLE `MIERole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MIE_Description`
--

DROP TABLE IF EXISTS `MIE_Description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MIE_Description` (
  `mIEId` int(10) unsigned NOT NULL auto_increment,
  `description` text NOT NULL,
  `langISOId` varchar(255) NOT NULL default '',
  `url` varchar(255) default NULL,
  PRIMARY KEY  (`mIEId`,`langISOId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MIE_Description`
--

LOCK TABLES `MIE_Description` WRITE;
/*!40000 ALTER TABLE `MIE_Description` DISABLE KEYS */;
/*!40000 ALTER TABLE `MIE_Description` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SourceHost`
--

DROP TABLE IF EXISTS `SourceHost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SourceHost` (
  `sourceHostId` int(10) unsigned NOT NULL auto_increment,
  `host` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sourceHostId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SourceHost`
--

LOCK TABLES `SourceHost` WRITE;
/*!40000 ALTER TABLE `SourceHost` DISABLE KEYS */;
INSERT INTO `SourceHost` VALUES (4,'megaupload.com'),(5,'depositfiles.com'),(6,'free.fr'),(7,'gigaup.fr'),(8,'hotfile.com'),(9,'miroriii.com'),(10,'rapidshare.com'),(11,'Db.to'),(12,'terafiles.net'),(13,'uploading.com');
/*!40000 ALTER TABLE `SourceHost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SourceQuality`
--

DROP TABLE IF EXISTS `SourceQuality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SourceQuality` (
  `sourceQualityId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sourceQualityId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SourceQuality`
--

LOCK TABLES `SourceQuality` WRITE;
/*!40000 ALTER TABLE `SourceQuality` DISABLE KEYS */;
/*!40000 ALTER TABLE `SourceQuality` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SourceType`
--

DROP TABLE IF EXISTS `SourceType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SourceType` (
  `sourceTypeId` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `registrationRequired` int(10) NOT NULL default '0',
  `costFree` int(10) NOT NULL default '1',
  PRIMARY KEY  (`sourceTypeId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SourceType`
--

LOCK TABLES `SourceType` WRITE;
/*!40000 ALTER TABLE `SourceType` DISABLE KEYS */;
/*!40000 ALTER TABLE `SourceType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SourceValidator`
--

DROP TABLE IF EXISTS `SourceValidator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SourceValidator` (
  `sourceHostId` int(10) unsigned NOT NULL default '0',
  `regex` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sourceHostId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SourceValidator`
--

LOCK TABLES `SourceValidator` WRITE;
/*!40000 ALTER TABLE `SourceValidator` DISABLE KEYS */;
/*!40000 ALTER TABLE `SourceValidator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity`
--

DROP TABLE IF EXISTS `VideoEntity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity` (
  `videoEntityId` int(10) unsigned NOT NULL auto_increment,
  `vETitleId` int(10) unsigned NOT NULL default '0',
  `vESharedInfoId` int(10) unsigned NOT NULL default '0',
  `langId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`videoEntityId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity`
--

LOCK TABLES `VideoEntity` WRITE;
/*!40000 ALTER TABLE `VideoEntity` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `VideoEntity_Title`
--

DROP TABLE IF EXISTS `VideoEntity_Title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_Title` (
  `vETitleId` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`vETitleId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_Title`
--

LOCK TABLES `VideoEntity_Title` WRITE;
/*!40000 ALTER TABLE `VideoEntity_Title` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_Title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_SharedInfo`
--

DROP TABLE IF EXISTS `VideoEntity_SharedInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_SharedInfo` (
  `vESharedInfoId` int(10) unsigned NOT NULL auto_increment,
  `date` varchar(255) NOT NULL default '',
  `countryId` int(10) NOT NULL default '0',
  `timeLengthHHMMSS` int(10) NOT NULL default '0',
  `originalTitleId` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`vESharedInfoId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_SharedInfo`
--

LOCK TABLES `VideoEntity_SharedInfo` WRITE;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_SharedInfo_r_Image`
--

DROP TABLE IF EXISTS `VideoEntity_SharedInfo_r_Image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_SharedInfo_r_Image` (
  `vESharedInfoId` int(10) unsigned NOT NULL default 0,
  `imageId` int(10) unsigned NOT NULL default 0,
  `isRecycled` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (`vESharedInfoId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_SharedInfo_r_Image`
--

LOCK TABLES `VideoEntity_SharedInfo_r_Image` WRITE;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo_r_Image` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo_r_Image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_SharedInfo_r_Genre`
--

DROP TABLE IF EXISTS `VideoEntity_SharedInfo_r_Genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_SharedInfo_r_Genre` (
  `vESharedInfoId` int(10) unsigned NOT NULL default 0,
  `genreId` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (`vESharedInfoId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_SharedInfo_r_Genre`
--

LOCK TABLES `VideoEntity_SharedInfo_r_Genre` WRITE;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo_r_Genre` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo_r_Genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_SharedInfo_Participant`
--

DROP TABLE IF EXISTS `VideoEntity_SharedInfo_Participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_SharedInfo_Participant` (
  `participantId` int(10) unsigned NOT NULL auto_increment,
  `vESharedInfoId` int(10) unsigned NOT NULL default 0,
  `mIEId` int(10) unsigned NOT NULL default 0,
  `mIERoleId` int(10) unsigned NOT NULL default 0,
  PRIMARY KEY  (`participantId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_SharedInfo_Participant`
--

LOCK TABLES `VideoEntity_SharedInfo_Participant` WRITE;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo_Participant` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_SharedInfo_Participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_Sources`
--

DROP TABLE IF EXISTS `VideoEntity_Sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_Sources` (
  `sourceId` int(10) unsigned NOT NULL auto_increment,
  `videoEntityId` int(10) unsigned NOT NULL default '0',
  `sourceHostId` int(10) unsigned NOT NULL default '0',
  `sourceTypeId` int(10) unsigned NOT NULL default '0',
  `sourceQualityId` int(10) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sourceId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_Sources`
--

LOCK TABLES `VideoEntity_Sources` WRITE;
/*!40000 ALTER TABLE `VideoEntity_Sources` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_Sources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Image`
--

DROP TABLE IF EXISTS `Image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Image` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `localUrl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`imageId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Image`
--

LOCK TABLES `Image` WRITE;
/*!40000 ALTER TABLE `Image` DISABLE KEYS */;
/*!40000 ALTER TABLE `Image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_Synopsis`
--

DROP TABLE IF EXISTS `VideoEntity_Synopsis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_Synopsis` (
  `videoEntityId` int(10) unsigned NOT NULL auto_increment,
  `synopsis` text NOT NULL,
  PRIMARY KEY  (`videoEntityId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_Synopsis`
--

LOCK TABLES `VideoEntity_Synopsis` WRITE;
/*!40000 ALTER TABLE `VideoEntity_Synopsis` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_Synopsis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_r_Countries`
--

DROP TABLE IF EXISTS `VideoEntity_r_Countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_r_Countries` (
  `videoEntityId` int(10) unsigned NOT NULL default '0',
  `countryId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`videoEntityId`,`countryId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_r_Countries`
--

LOCK TABLES `VideoEntity_r_Countries` WRITE;
/*!40000 ALTER TABLE `VideoEntity_r_Countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_r_Countries` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-09-02 23:21:43
