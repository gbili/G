-- MySQL dump 10.13  Distrib 5.1.37, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: miner
-- ------------------------------------------------------
-- Server version	5.1.37-1ubuntu5.1

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
-- Table structure for table `Country`
--

DROP TABLE IF EXISTS `Country`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country` (
  `countryId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`countryId`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
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
-- Table structure for table `Country_Matcher`
--

DROP TABLE IF EXISTS `Country_Matcher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country_Matcher` (
  `priority` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `countryId` int(10) unsigned NOT NULL DEFAULT '0',
  `regex` text NOT NULL,
  PRIMARY KEY (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Country_Matcher`
--

LOCK TABLES `Country_Matcher` WRITE;
/*!40000 ALTER TABLE `Country_Matcher` DISABLE KEYS */;
INSERT INTO `Country_Matcher` VALUES (18,4,'/Franp{L}p{M}*(?:ia|[ea]|kreich)/i'),(19,5,'/Austrp{L}p{M}*li(?:a|en)/i'),(20,6,'/u.?s.?a.?|e.?e.?u.?u.?|Vereinigte[- _.]Staa?ten|Estados[- _.]Unp{L}p{M}*dos.*?(?:Amp{L}p{M}*rica)?|United[- _.]states.*?(?:America)?|p{L}p{M}*tats[-_. ]unis[-_. ]d?.?Amp{L}p{M}*rique|Stati[ -_.]Uniti(?:[ -_.]d.america)?/i'),(21,7,'/[CK]anadp{L}p{M}*/i'),(22,8,'/p{L}p{M}*e?sterreich|Au(?:stria|triche)/i'),(23,9,'/German(?:y|ia)|Aleman[ih]a|Allemagne|Deutschland/i'),(24,10,'/Pays-Bas|Pap{L}p{M}*ses-Ba(?:j|ix)os|Paesi-Bassi|Niederlande|Netherlands/i'),(25,11,'/Switzerland|Suisse|Suiza|Svizzera|Schweiz|Sup{L}p{M}*p{L}p{M}*a/i'),(26,12,'/[UV].?K.?|United Kingdom|Britain|Royaume-Uni|Re[ig]no Uni[dt]o|Vereinigtes K(?:p{L}p{M}*|oe)nigreich/i'),(27,13,'/Argentin(?:[ae]|ien)/i'),(28,14,'/Bp{L}p{M}*lgi(?:um|que|ca|en)/i'),(29,15,'/Espap{L}p{M}*[ae]|Spa(?:nien|gna)|Spain/i'),(30,16,'/(?:Itp{L}p{M}*l[yi](?:a|(?:en))?)/i'),(31,17,'/Portugal|Portogallo/i');
/*!40000 ALTER TABLE `Country_Matcher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Country_r_LangISOs`
--

DROP TABLE IF EXISTS `Country_r_LangISOs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Country_r_LangISOs` (
  `countryId` int(10) unsigned NOT NULL DEFAULT '0',
  `langISOId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`countryId`,`langISOId`)
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
  `genreId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`genreId`)
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
-- Table structure for table `LangISO`
--

DROP TABLE IF EXISTS `LangISO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LangISO` (
  `langISOId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`langISOId`)
) ENGINE=MyISAM AUTO_INCREMENT=147 DEFAULT CHARSET=utf8;
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
-- Table structure for table `MIE`
--

DROP TABLE IF EXISTS `MIE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MIE` (
  `mIEId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT 'N/A',
  `countryId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mIEId`)
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
  `roleId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`roleId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MIERole`
--

LOCK TABLES `MIERole` WRITE;
/*!40000 ALTER TABLE `MIERole` DISABLE KEYS */;
/*!40000 ALTER TABLE `MIERole` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MIE_Description`
--

DROP TABLE IF EXISTS `MIE_Description`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MIE_Description` (
  `MIEId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `langISOId` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`MIEId`,`langISOId`)
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
-- Table structure for table `Parent_r_VideoEntitys`
--

DROP TABLE IF EXISTS `Parent_r_VideoEntitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Parent_r_VideoEntitys` (
  `videoEntityId` int(10) unsigned NOT NULL DEFAULT '0',
  `parentId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`videoEntityId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Parent_r_VideoEntitys`
--

LOCK TABLES `Parent_r_VideoEntitys` WRITE;
/*!40000 ALTER TABLE `Parent_r_VideoEntitys` DISABLE KEYS */;
/*!40000 ALTER TABLE `Parent_r_VideoEntitys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SourceHost`
--

DROP TABLE IF EXISTS `SourceHost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SourceHost` (
  `sourceHostId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `authority` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sourceHostId`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
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
  `sourceQualityId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sourceQualityId`)
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
  `sourceTypeId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `registrationRequired` int(10) NOT NULL DEFAULT '0',
  `costFree` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sourceTypeId`)
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
  `sourceHostId` int(10) unsigned NOT NULL DEFAULT '0',
  `regex` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sourceHostId`)
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
  `videoEntityId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `langISOId` int(10) NOT NULL DEFAULT '0',
  `countryId` int(10) NOT NULL DEFAULT '0',
  `dateReleased` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`videoEntityId`)
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
-- Table structure for table `VideoEntity_Sources`
--

DROP TABLE IF EXISTS `VideoEntity_Sources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_Sources` (
  `sourceId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `videoEntityId` int(10) unsigned NOT NULL DEFAULT '0',
  `sourceHostId` int(10) unsigned NOT NULL DEFAULT '0',
  `sourceTypeId` int(10) unsigned NOT NULL DEFAULT '0',
  `sourceQualityId` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sourceId`)
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
-- Table structure for table `VideoEntity_Synopsis`
--

DROP TABLE IF EXISTS `VideoEntity_Synopsis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_Synopsis` (
  `videoEntityId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `synopsys` text NOT NULL,
  PRIMARY KEY (`videoEntityId`)
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
  `videoEntityId` int(10) unsigned NOT NULL DEFAULT '0',
  `countryId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`videoEntityId`,`countryId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_r_Countries`
--

LOCK TABLES `VideoEntity_r_Countries` WRITE;
/*!40000 ALTER TABLE `VideoEntity_r_Countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_r_Countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_r_Genre`
--

DROP TABLE IF EXISTS `VideoEntity_r_Genre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_r_Genre` (
  `videoEntityId` int(10) unsigned NOT NULL DEFAULT '0',
  `genreId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`videoEntityId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_r_Genre`
--

LOCK TABLES `VideoEntity_r_Genre` WRITE;
/*!40000 ALTER TABLE `VideoEntity_r_Genre` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_r_Genre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VideoEntity_r_Participants`
--

DROP TABLE IF EXISTS `VideoEntity_r_Participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VideoEntity_r_Participants` (
  `videoEntityId` int(10) unsigned NOT NULL DEFAULT '0',
  `movieIndustryEntityId` int(10) unsigned NOT NULL DEFAULT '0',
  `roleId` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`videoEntityId`,`movieIndustryEntityId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VideoEntity_r_Participants`
--

LOCK TABLES `VideoEntity_r_Participants` WRITE;
/*!40000 ALTER TABLE `VideoEntity_r_Participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `VideoEntity_r_Participants` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-03-29  3:17:55
