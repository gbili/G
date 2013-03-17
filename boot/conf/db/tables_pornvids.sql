-- MySQL dump 10.11
--
-- Host: localhost    Database: pornvids
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
-- Table structure for table `G_Source`
--

DROP TABLE IF EXISTS `G_Source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Source` (
  `sourceId` int(10) unsigned NOT NULL auto_increment,
  `hostId` int(10) unsigned NOT NULL default '0',
  `path` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sourceId`)
) ENGINE=MyISAM AUTO_INCREMENT=5703 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Source`
--

LOCK TABLES `G_Source` WRITE;
/*!40000 ALTER TABLE `G_Source` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Source` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Source_Host`
--

DROP TABLE IF EXISTS `G_Source_Host`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Source_Host` (
  `hostId` int(10) unsigned NOT NULL auto_increment,
  `host` varchar(255) NOT NULL default '',
  `hFName` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`hostId`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Source_Host`
--

LOCK TABLES `G_Source_Host` WRITE;
/*!40000 ALTER TABLE `G_Source_Host` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Source_Host` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Vid`
--

DROP TABLE IF EXISTS `G_Vid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Vid` (
  `vidId` int(10) unsigned NOT NULL auto_increment,
  `titleId` int(10) unsigned NOT NULL default '0',
  `sourceId` int(10) unsigned NOT NULL default '0',
  `imageId` int(10) unsigned NOT NULL default '0',
  `categoryId` int(10) unsigned NOT NULL default '0',
  `timeLength` int(10) unsigned NOT NULL default '0',
  `date` int(11) unsigned NOT NULL default '0',
  `viewCount` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`vidId`)
) ENGINE=MyISAM AUTO_INCREMENT=5703 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Vid`
--

LOCK TABLES `G_Vid` WRITE;
/*!40000 ALTER TABLE `G_Vid` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Vid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Vid_Category`
--

DROP TABLE IF EXISTS `G_Vid_Category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Vid_Category` (
  `elementId` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  `viewCount` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`elementId`)
) ENGINE=MyISAM AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Vid_Category`
--

LOCK TABLES `G_Vid_Category` WRITE;
/*!40000 ALTER TABLE `G_Vid_Category` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Vid_Category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Vid_Image`
--

DROP TABLE IF EXISTS `G_Vid_Image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Vid_Image` (
  `imageId` int(10) unsigned NOT NULL auto_increment,
  `localUrl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`imageId`)
) ENGINE=MyISAM AUTO_INCREMENT=5494 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Vid_Image`
--

LOCK TABLES `G_Vid_Image` WRITE;
/*!40000 ALTER TABLE `G_Vid_Image` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Vid_Image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Vid_Tag`
--

DROP TABLE IF EXISTS `G_Vid_Tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Vid_Tag` (
  `elementId` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`elementId`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Vid_Tag`
--

LOCK TABLES `G_Vid_Tag` WRITE;
/*!40000 ALTER TABLE `G_Vid_Tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Vid_Tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Vid_Title`
--

DROP TABLE IF EXISTS `G_Vid_Title`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Vid_Title` (
  `elementId` int(10) unsigned NOT NULL auto_increment,
  `value` varchar(255) NOT NULL default '',
  `slug` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`elementId`)
) ENGINE=MyISAM AUTO_INCREMENT=7571 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Vid_Title`
--

LOCK TABLES `G_Vid_Title` WRITE;
/*!40000 ALTER TABLE `G_Vid_Title` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Vid_Title` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `G_Vid_r_Tag`
--

DROP TABLE IF EXISTS `G_Vid_r_Tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `G_Vid_r_Tag` (
  `vidId` int(10) unsigned NOT NULL default '0',
  `tagId` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`vidId`)
) ENGINE=MyISAM AUTO_INCREMENT=337 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `G_Vid_r_Tag`
--

LOCK TABLES `G_Vid_r_Tag` WRITE;
/*!40000 ALTER TABLE `G_Vid_r_Tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `G_Vid_r_Tag` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-01-10 10:46:14
