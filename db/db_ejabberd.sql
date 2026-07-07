/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.16-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: ejabberd
-- ------------------------------------------------------
-- Server version	10.11.16-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `archive`
--

DROP TABLE IF EXISTS `archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `archive` (
  `username` varchar(191) NOT NULL,
  `timestamp` bigint(20) unsigned NOT NULL,
  `peer` varchar(191) NOT NULL,
  `bare_peer` varchar(191) NOT NULL,
  `xml` mediumtext NOT NULL,
  `txt` mediumtext DEFAULT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kind` varchar(10) DEFAULT NULL,
  `nick` varchar(191) DEFAULT NULL,
  `origin_id` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `id` (`id`),
  KEY `i_username_timestamp` (`username`,`timestamp`) USING BTREE,
  KEY `i_username_peer` (`username`,`peer`) USING BTREE,
  KEY `i_username_bare_peer` (`username`,`bare_peer`) USING BTREE,
  KEY `i_timestamp` (`timestamp`) USING BTREE,
  KEY `i_archive_username_origin_id` (`username`,`origin_id`) USING BTREE,
  FULLTEXT KEY `i_text` (`txt`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archive`
--

LOCK TABLES `archive` WRITE;
/*!40000 ALTER TABLE `archive` DISABLE KEYS */;
INSERT INTO `archive` VALUES
('ricardo',1778706189817905,'kewing@netsoluciones.com','kewing@netsoluciones.com','<message xml:lang=\'es\' to=\'kewing@netsoluciones.com\' from=\'ricardo@netsoluciones.com/desktop-o03dkh\' type=\'chat\' id=\'9142f244-5bbe-4cfe-b893-ae45955bb7ed\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'9142f244-5bbe-4cfe-b893-ae45955bb7ed\'/><body>hola</body></message>','hola',1,'chat','','9142f244-5bbe-4cfe-b893-ae45955bb7ed','2026-05-13 21:03:09'),
('kewing',1778706189830769,'ricardo@netsoluciones.com/desktop-o03dkh','ricardo@netsoluciones.com','<message xml:lang=\'es\' to=\'kewing@netsoluciones.com\' from=\'ricardo@netsoluciones.com/desktop-o03dkh\' type=\'chat\' id=\'9142f244-5bbe-4cfe-b893-ae45955bb7ed\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'9142f244-5bbe-4cfe-b893-ae45955bb7ed\'/><body>hola</body></message>','hola',2,'chat','','9142f244-5bbe-4cfe-b893-ae45955bb7ed','2026-05-13 21:03:09'),
('kewing',1778706414869488,'ricardo@netsoluciones.com','ricardo@netsoluciones.com','<message xml:lang=\'en\' to=\'ricardo@netsoluciones.com\' from=\'kewing@netsoluciones.com/desktop-ckyn5a\' type=\'chat\' id=\'b5f38ab2-5d8e-4a11-ada6-f48f2a80f3b9\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'b5f38ab2-5d8e-4a11-ada6-f48f2a80f3b9\'/><body>hola</body></message>','hola',3,'chat','','b5f38ab2-5d8e-4a11-ada6-f48f2a80f3b9','2026-05-13 21:06:54'),
('ricardo',1778706414870350,'kewing@netsoluciones.com/desktop-ckyn5a','kewing@netsoluciones.com','<message xml:lang=\'en\' to=\'ricardo@netsoluciones.com\' from=\'kewing@netsoluciones.com/desktop-ckyn5a\' type=\'chat\' id=\'b5f38ab2-5d8e-4a11-ada6-f48f2a80f3b9\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'b5f38ab2-5d8e-4a11-ada6-f48f2a80f3b9\'/><body>hola</body></message>','hola',4,'chat','','b5f38ab2-5d8e-4a11-ada6-f48f2a80f3b9','2026-05-13 21:06:54'),
('ricardo',1778706426586736,'kewing@netsoluciones.com','kewing@netsoluciones.com','<message xml:lang=\'es\' to=\'kewing@netsoluciones.com\' from=\'ricardo@netsoluciones.com/desktop-o03dkh\' type=\'chat\' id=\'de9f2810-f07e-4730-aebb-ec470e193f5f\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'de9f2810-f07e-4730-aebb-ec470e193f5f\'/><body>prueba</body></message>','prueba',5,'chat','','de9f2810-f07e-4730-aebb-ec470e193f5f','2026-05-13 21:07:06'),
('kewing',1778706426587856,'ricardo@netsoluciones.com/desktop-o03dkh','ricardo@netsoluciones.com','<message xml:lang=\'es\' to=\'kewing@netsoluciones.com\' from=\'ricardo@netsoluciones.com/desktop-o03dkh\' type=\'chat\' id=\'de9f2810-f07e-4730-aebb-ec470e193f5f\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'de9f2810-f07e-4730-aebb-ec470e193f5f\'/><body>prueba</body></message>','prueba',6,'chat','','de9f2810-f07e-4730-aebb-ec470e193f5f','2026-05-13 21:07:06'),
('ricardo',1778706482691888,'kewing@netsoluciones.com','kewing@netsoluciones.com','<message xml:lang=\'es\' to=\'kewing@netsoluciones.com\' from=\'ricardo@netsoluciones.com/desktop-o03dkh\' type=\'chat\' id=\'5da5b06f-a5f9-4ab8-b41b-ee57f861324d\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'5da5b06f-a5f9-4ab8-b41b-ee57f861324d\'/><body>hoy es miercoles</body></message>','hoy es miercoles',7,'chat','','5da5b06f-a5f9-4ab8-b41b-ee57f861324d','2026-05-13 21:08:02'),
('kewing',1778706482693279,'ricardo@netsoluciones.com/desktop-o03dkh','ricardo@netsoluciones.com','<message xml:lang=\'es\' to=\'kewing@netsoluciones.com\' from=\'ricardo@netsoluciones.com/desktop-o03dkh\' type=\'chat\' id=\'5da5b06f-a5f9-4ab8-b41b-ee57f861324d\' xmlns=\'jabber:client\'><active xmlns=\'http://jabber.org/protocol/chatstates\'/><origin-id xmlns=\'urn:xmpp:sid:0\' id=\'5da5b06f-a5f9-4ab8-b41b-ee57f861324d\'/><body>hoy es miercoles</body></message>','hoy es miercoles',8,'chat','','5da5b06f-a5f9-4ab8-b41b-ee57f861324d','2026-05-13 21:08:02');
/*!40000 ALTER TABLE `archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `archive_prefs`
--

DROP TABLE IF EXISTS `archive_prefs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `archive_prefs` (
  `username` varchar(191) NOT NULL,
  `def` text NOT NULL,
  `always` text NOT NULL,
  `never` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archive_prefs`
--

LOCK TABLES `archive_prefs` WRITE;
/*!40000 ALTER TABLE `archive_prefs` DISABLE KEYS */;
/*!40000 ALTER TABLE `archive_prefs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bosh`
--

DROP TABLE IF EXISTS `bosh`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bosh` (
  `sid` text NOT NULL,
  `node` text NOT NULL,
  `pid` text NOT NULL,
  UNIQUE KEY `i_bosh_sid` (`sid`(75))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bosh`
--

LOCK TABLES `bosh` WRITE;
/*!40000 ALTER TABLE `bosh` DISABLE KEYS */;
/*!40000 ALTER TABLE `bosh` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caps_features`
--

DROP TABLE IF EXISTS `caps_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `caps_features` (
  `node` varchar(191) NOT NULL,
  `subnode` varchar(191) NOT NULL,
  `feature` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  KEY `i_caps_features_node_subnode` (`node`(75),`subnode`(75))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caps_features`
--

LOCK TABLES `caps_features` WRITE;
/*!40000 ALTER TABLE `caps_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `caps_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invite_token`
--

DROP TABLE IF EXISTS `invite_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `invite_token` (
  `token` text NOT NULL,
  `username` text NOT NULL,
  `invitee` varchar(191) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` char(1) NOT NULL,
  `account_name` text NOT NULL,
  PRIMARY KEY (`token`(191)),
  KEY `i_invite_token_username` (`username`(191)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invite_token`
--

LOCK TABLES `invite_token` WRITE;
/*!40000 ALTER TABLE `invite_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `invite_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `last`
--

DROP TABLE IF EXISTS `last`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `last` (
  `username` varchar(191) NOT NULL,
  `seconds` text NOT NULL,
  `state` text NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `last`
--

LOCK TABLES `last` WRITE;
/*!40000 ALTER TABLE `last` DISABLE KEYS */;
/*!40000 ALTER TABLE `last` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mix_channel`
--

DROP TABLE IF EXISTS `mix_channel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mix_channel` (
  `channel` text NOT NULL,
  `service` text NOT NULL,
  `username` text NOT NULL,
  `domain` text NOT NULL,
  `jid` text NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `hmac_key` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_mix_channel` (`channel`(191),`service`(191)),
  KEY `i_mix_channel_serv` (`service`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mix_channel`
--

LOCK TABLES `mix_channel` WRITE;
/*!40000 ALTER TABLE `mix_channel` DISABLE KEYS */;
/*!40000 ALTER TABLE `mix_channel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mix_pam`
--

DROP TABLE IF EXISTS `mix_pam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mix_pam` (
  `username` text NOT NULL,
  `channel` text NOT NULL,
  `service` text NOT NULL,
  `id` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_mix_pam` (`username`(191),`channel`(191),`service`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mix_pam`
--

LOCK TABLES `mix_pam` WRITE;
/*!40000 ALTER TABLE `mix_pam` DISABLE KEYS */;
/*!40000 ALTER TABLE `mix_pam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mix_participant`
--

DROP TABLE IF EXISTS `mix_participant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mix_participant` (
  `channel` text NOT NULL,
  `service` text NOT NULL,
  `username` text NOT NULL,
  `domain` text NOT NULL,
  `jid` text NOT NULL,
  `id` text NOT NULL,
  `nick` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_mix_participant` (`channel`(191),`service`(191),`username`(191),`domain`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mix_participant`
--

LOCK TABLES `mix_participant` WRITE;
/*!40000 ALTER TABLE `mix_participant` DISABLE KEYS */;
/*!40000 ALTER TABLE `mix_participant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mix_subscription`
--

DROP TABLE IF EXISTS `mix_subscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mix_subscription` (
  `channel` text NOT NULL,
  `service` text NOT NULL,
  `username` text NOT NULL,
  `domain` text NOT NULL,
  `node` text NOT NULL,
  `jid` text NOT NULL,
  UNIQUE KEY `i_mix_subscription` (`channel`(153),`service`(153),`username`(153),`domain`(153),`node`(153)),
  KEY `i_mix_subscription_chan_serv_node` (`channel`(191),`service`(191),`node`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mix_subscription`
--

LOCK TABLES `mix_subscription` WRITE;
/*!40000 ALTER TABLE `mix_subscription` DISABLE KEYS */;
/*!40000 ALTER TABLE `mix_subscription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motd`
--

DROP TABLE IF EXISTS `motd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `motd` (
  `username` varchar(191) NOT NULL,
  `xml` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motd`
--

LOCK TABLES `motd` WRITE;
/*!40000 ALTER TABLE `motd` DISABLE KEYS */;
/*!40000 ALTER TABLE `motd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mqtt_pub`
--

DROP TABLE IF EXISTS `mqtt_pub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `mqtt_pub` (
  `username` varchar(191) NOT NULL,
  `resource` varchar(191) NOT NULL,
  `topic` text NOT NULL,
  `qos` tinyint(4) NOT NULL,
  `payload` blob NOT NULL,
  `payload_format` tinyint(4) NOT NULL,
  `content_type` text NOT NULL,
  `response_topic` text NOT NULL,
  `correlation_data` blob NOT NULL,
  `user_properties` blob NOT NULL,
  `expiry` int(10) unsigned NOT NULL,
  UNIQUE KEY `i_mqtt_topic` (`topic`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mqtt_pub`
--

LOCK TABLES `mqtt_pub` WRITE;
/*!40000 ALTER TABLE `mqtt_pub` DISABLE KEYS */;
/*!40000 ALTER TABLE `mqtt_pub` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `muc_online_room`
--

DROP TABLE IF EXISTS `muc_online_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `muc_online_room` (
  `name` text NOT NULL,
  `host` text NOT NULL,
  `node` text NOT NULL,
  `pid` text NOT NULL,
  UNIQUE KEY `i_muc_online_room_name_host` (`name`(75),`host`(75)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `muc_online_room`
--

LOCK TABLES `muc_online_room` WRITE;
/*!40000 ALTER TABLE `muc_online_room` DISABLE KEYS */;
/*!40000 ALTER TABLE `muc_online_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `muc_online_users`
--

DROP TABLE IF EXISTS `muc_online_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `muc_online_users` (
  `username` text NOT NULL,
  `server` text NOT NULL,
  `resource` text NOT NULL,
  `name` text NOT NULL,
  `host` text NOT NULL,
  `node` text NOT NULL,
  UNIQUE KEY `i_muc_online_users` (`username`(75),`server`(75),`resource`(75),`name`(75),`host`(75)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `muc_online_users`
--

LOCK TABLES `muc_online_users` WRITE;
/*!40000 ALTER TABLE `muc_online_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `muc_online_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `muc_registered`
--

DROP TABLE IF EXISTS `muc_registered`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `muc_registered` (
  `jid` text NOT NULL,
  `host` text NOT NULL,
  `nick` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_muc_registered_jid_host` (`jid`(75),`host`(75)) USING BTREE,
  KEY `i_muc_registered_nick` (`nick`(75)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `muc_registered`
--

LOCK TABLES `muc_registered` WRITE;
/*!40000 ALTER TABLE `muc_registered` DISABLE KEYS */;
/*!40000 ALTER TABLE `muc_registered` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `muc_room`
--

DROP TABLE IF EXISTS `muc_room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `muc_room` (
  `name` text NOT NULL,
  `host` text NOT NULL,
  `opts` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_muc_room_name_host` (`name`(75),`host`(75)) USING BTREE,
  KEY `i_muc_room_host_created_at` (`host`(75),`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `muc_room`
--

LOCK TABLES `muc_room` WRITE;
/*!40000 ALTER TABLE `muc_room` DISABLE KEYS */;
/*!40000 ALTER TABLE `muc_room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `muc_room_subscribers`
--

DROP TABLE IF EXISTS `muc_room_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `muc_room_subscribers` (
  `room` varchar(191) NOT NULL,
  `host` varchar(191) NOT NULL,
  `jid` varchar(191) NOT NULL,
  `nick` text NOT NULL,
  `nodes` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_muc_room_subscribers_host_room_jid` (`host`,`room`,`jid`),
  KEY `i_muc_room_subscribers_host_jid` (`host`,`jid`) USING BTREE,
  KEY `i_muc_room_subscribers_jid` (`jid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `muc_room_subscribers`
--

LOCK TABLES `muc_room_subscribers` WRITE;
/*!40000 ALTER TABLE `muc_room_subscribers` DISABLE KEYS */;
/*!40000 ALTER TABLE `muc_room_subscribers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_client`
--

DROP TABLE IF EXISTS `oauth_client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_client` (
  `client_id` varchar(191) NOT NULL,
  `client_name` text NOT NULL,
  `grant_type` text NOT NULL,
  `options` text NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_client`
--

LOCK TABLES `oauth_client` WRITE;
/*!40000 ALTER TABLE `oauth_client` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `oauth_token`
--

DROP TABLE IF EXISTS `oauth_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `oauth_token` (
  `token` varchar(191) NOT NULL,
  `jid` text NOT NULL,
  `scope` text NOT NULL,
  `expire` bigint(20) NOT NULL,
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `oauth_token`
--

LOCK TABLES `oauth_token` WRITE;
/*!40000 ALTER TABLE `oauth_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privacy_default_list`
--

DROP TABLE IF EXISTS `privacy_default_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `privacy_default_list` (
  `username` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privacy_default_list`
--

LOCK TABLES `privacy_default_list` WRITE;
/*!40000 ALTER TABLE `privacy_default_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `privacy_default_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privacy_list`
--

DROP TABLE IF EXISTS `privacy_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `privacy_list` (
  `username` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `i_privacy_list_username_name` (`username`(75),`name`(75)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privacy_list`
--

LOCK TABLES `privacy_list` WRITE;
/*!40000 ALTER TABLE `privacy_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `privacy_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privacy_list_data`
--

DROP TABLE IF EXISTS `privacy_list_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `privacy_list_data` (
  `id` bigint(20) DEFAULT NULL,
  `t` char(1) NOT NULL,
  `value` text NOT NULL,
  `action` char(1) NOT NULL,
  `ord` decimal(10,0) NOT NULL,
  `match_all` tinyint(1) NOT NULL,
  `match_iq` tinyint(1) NOT NULL,
  `match_message` tinyint(1) NOT NULL,
  `match_presence_in` tinyint(1) NOT NULL,
  `match_presence_out` tinyint(1) NOT NULL,
  KEY `i_privacy_list_data_id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privacy_list_data`
--

LOCK TABLES `privacy_list_data` WRITE;
/*!40000 ALTER TABLE `privacy_list_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `privacy_list_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `private_storage`
--

DROP TABLE IF EXISTS `private_storage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `private_storage` (
  `username` varchar(191) NOT NULL,
  `namespace` varchar(191) NOT NULL,
  `data` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_private_storage_username_namespace` (`username`(75),`namespace`(75)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `private_storage`
--

LOCK TABLES `private_storage` WRITE;
/*!40000 ALTER TABLE `private_storage` DISABLE KEYS */;
/*!40000 ALTER TABLE `private_storage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proxy65`
--

DROP TABLE IF EXISTS `proxy65`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proxy65` (
  `sid` text NOT NULL,
  `pid_t` text NOT NULL,
  `pid_i` text NOT NULL,
  `node_t` text NOT NULL,
  `node_i` text NOT NULL,
  `jid_i` text NOT NULL,
  UNIQUE KEY `i_proxy65_sid` (`sid`(191)),
  KEY `i_proxy65_jid` (`jid_i`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proxy65`
--

LOCK TABLES `proxy65` WRITE;
/*!40000 ALTER TABLE `proxy65` DISABLE KEYS */;
/*!40000 ALTER TABLE `proxy65` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pubsub_item`
--

DROP TABLE IF EXISTS `pubsub_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pubsub_item` (
  `nodeid` bigint(20) DEFAULT NULL,
  `itemid` text NOT NULL,
  `publisher` text NOT NULL,
  `creation` varchar(32) NOT NULL,
  `modification` varchar(32) NOT NULL,
  `payload` mediumtext NOT NULL,
  UNIQUE KEY `i_pubsub_item_tuple` (`nodeid`,`itemid`(36)),
  KEY `i_pubsub_item_itemid` (`itemid`(36)),
  CONSTRAINT `pubsub_item_ibfk_1` FOREIGN KEY (`nodeid`) REFERENCES `pubsub_node` (`nodeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pubsub_item`
--

LOCK TABLES `pubsub_item` WRITE;
/*!40000 ALTER TABLE `pubsub_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `pubsub_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pubsub_node`
--

DROP TABLE IF EXISTS `pubsub_node`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pubsub_node` (
  `host` text NOT NULL,
  `node` text NOT NULL,
  `parent` varchar(191) NOT NULL DEFAULT '',
  `plugin` text NOT NULL,
  `nodeid` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`nodeid`),
  UNIQUE KEY `i_pubsub_node_tuple` (`host`(71),`node`(120)),
  KEY `i_pubsub_node_parent` (`parent`(120))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pubsub_node`
--

LOCK TABLES `pubsub_node` WRITE;
/*!40000 ALTER TABLE `pubsub_node` DISABLE KEYS */;
/*!40000 ALTER TABLE `pubsub_node` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pubsub_node_option`
--

DROP TABLE IF EXISTS `pubsub_node_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pubsub_node_option` (
  `nodeid` bigint(20) DEFAULT NULL,
  `name` text NOT NULL,
  `val` text NOT NULL,
  KEY `i_pubsub_node_option_nodeid` (`nodeid`),
  CONSTRAINT `pubsub_node_option_ibfk_1` FOREIGN KEY (`nodeid`) REFERENCES `pubsub_node` (`nodeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pubsub_node_option`
--

LOCK TABLES `pubsub_node_option` WRITE;
/*!40000 ALTER TABLE `pubsub_node_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `pubsub_node_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pubsub_node_owner`
--

DROP TABLE IF EXISTS `pubsub_node_owner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pubsub_node_owner` (
  `nodeid` bigint(20) DEFAULT NULL,
  `owner` text NOT NULL,
  KEY `i_pubsub_node_owner_nodeid` (`nodeid`),
  CONSTRAINT `pubsub_node_owner_ibfk_1` FOREIGN KEY (`nodeid`) REFERENCES `pubsub_node` (`nodeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pubsub_node_owner`
--

LOCK TABLES `pubsub_node_owner` WRITE;
/*!40000 ALTER TABLE `pubsub_node_owner` DISABLE KEYS */;
/*!40000 ALTER TABLE `pubsub_node_owner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pubsub_state`
--

DROP TABLE IF EXISTS `pubsub_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pubsub_state` (
  `nodeid` bigint(20) DEFAULT NULL,
  `jid` text NOT NULL,
  `affiliation` char(1) DEFAULT NULL,
  `subscriptions` varchar(191) NOT NULL DEFAULT '',
  `stateid` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`stateid`),
  UNIQUE KEY `i_pubsub_state_tuple` (`nodeid`,`jid`(60)),
  KEY `i_pubsub_state_jid` (`jid`(60)),
  CONSTRAINT `pubsub_state_ibfk_1` FOREIGN KEY (`nodeid`) REFERENCES `pubsub_node` (`nodeid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pubsub_state`
--

LOCK TABLES `pubsub_state` WRITE;
/*!40000 ALTER TABLE `pubsub_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `pubsub_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pubsub_subscription_opt`
--

DROP TABLE IF EXISTS `pubsub_subscription_opt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pubsub_subscription_opt` (
  `subid` text NOT NULL,
  `opt_name` varchar(32) DEFAULT NULL,
  `opt_value` text NOT NULL,
  UNIQUE KEY `i_pubsub_subscription_opt` (`subid`(32),`opt_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pubsub_subscription_opt`
--

LOCK TABLES `pubsub_subscription_opt` WRITE;
/*!40000 ALTER TABLE `pubsub_subscription_opt` DISABLE KEYS */;
/*!40000 ALTER TABLE `pubsub_subscription_opt` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_session`
--

DROP TABLE IF EXISTS `push_session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_session` (
  `username` text NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `service` text NOT NULL,
  `node` text NOT NULL,
  `xml` text NOT NULL,
  UNIQUE KEY `i_push_usn` (`username`(191),`service`(191),`node`(191)),
  UNIQUE KEY `i_push_ut` (`username`(191),`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_session`
--

LOCK TABLES `push_session` WRITE;
/*!40000 ALTER TABLE `push_session` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_session` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roster_version`
--

DROP TABLE IF EXISTS `roster_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roster_version` (
  `username` varchar(191) NOT NULL,
  `version` text NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roster_version`
--

LOCK TABLES `roster_version` WRITE;
/*!40000 ALTER TABLE `roster_version` DISABLE KEYS */;
/*!40000 ALTER TABLE `roster_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rostergroups`
--

DROP TABLE IF EXISTS `rostergroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rostergroups` (
  `username` varchar(191) NOT NULL,
  `jid` varchar(191) NOT NULL,
  `grp` text NOT NULL,
  KEY `pk_rosterg_user_jid` (`username`(75),`jid`(75))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rostergroups`
--

LOCK TABLES `rostergroups` WRITE;
/*!40000 ALTER TABLE `rostergroups` DISABLE KEYS */;
/*!40000 ALTER TABLE `rostergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rosterusers`
--

DROP TABLE IF EXISTS `rosterusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `rosterusers` (
  `username` varchar(191) NOT NULL,
  `jid` varchar(191) NOT NULL,
  `nick` text NOT NULL,
  `subscription` char(1) NOT NULL,
  `ask` char(1) NOT NULL,
  `askmessage` text NOT NULL,
  `server` char(1) NOT NULL,
  `subscribe` text NOT NULL,
  `type` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_rosteru_user_jid` (`username`(75),`jid`(75)),
  KEY `i_rosteru_jid` (`jid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rosterusers`
--

LOCK TABLES `rosterusers` WRITE;
/*!40000 ALTER TABLE `rosterusers` DISABLE KEYS */;
/*!40000 ALTER TABLE `rosterusers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `route`
--

DROP TABLE IF EXISTS `route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `route` (
  `domain` text NOT NULL,
  `server_host` text NOT NULL,
  `node` text NOT NULL,
  `pid` text NOT NULL,
  `local_hint` text NOT NULL,
  UNIQUE KEY `i_route` (`domain`(75),`server_host`(75),`node`(75),`pid`(75))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `route`
--

LOCK TABLES `route` WRITE;
/*!40000 ALTER TABLE `route` DISABLE KEYS */;
/*!40000 ALTER TABLE `route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schema_version`
--

DROP TABLE IF EXISTS `schema_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `schema_version` (
  `module` text NOT NULL,
  `version` bigint(20) NOT NULL,
  UNIQUE KEY `i_schema_version_module` (`module`(191)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schema_version`
--

LOCK TABLES `schema_version` WRITE;
/*!40000 ALTER TABLE `schema_version` DISABLE KEYS */;
INSERT INTO `schema_version` VALUES
('mod_mam_sql',2);
/*!40000 ALTER TABLE `schema_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sm`
--

DROP TABLE IF EXISTS `sm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sm` (
  `usec` bigint(20) NOT NULL,
  `pid` text NOT NULL,
  `node` text NOT NULL,
  `username` varchar(191) NOT NULL,
  `resource` varchar(191) NOT NULL,
  `priority` text NOT NULL,
  `info` text NOT NULL,
  UNIQUE KEY `i_sid` (`usec`,`pid`(75)),
  KEY `i_node` (`node`(75)),
  KEY `i_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sm`
--

LOCK TABLES `sm` WRITE;
/*!40000 ALTER TABLE `sm` DISABLE KEYS */;
/*!40000 ALTER TABLE `sm` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `spool`
--

DROP TABLE IF EXISTS `spool`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `spool` (
  `username` varchar(191) NOT NULL,
  `xml` mediumtext NOT NULL,
  `seq` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `seq` (`seq`),
  KEY `i_despool` (`username`) USING BTREE,
  KEY `i_spool_created_at` (`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `spool`
--

LOCK TABLES `spool` WRITE;
/*!40000 ALTER TABLE `spool` DISABLE KEYS */;
/*!40000 ALTER TABLE `spool` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sr_group`
--

DROP TABLE IF EXISTS `sr_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sr_group` (
  `name` varchar(191) NOT NULL,
  `opts` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_sr_group_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_group`
--

LOCK TABLES `sr_group` WRITE;
/*!40000 ALTER TABLE `sr_group` DISABLE KEYS */;
/*!40000 ALTER TABLE `sr_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sr_user`
--

DROP TABLE IF EXISTS `sr_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sr_user` (
  `jid` varchar(191) NOT NULL,
  `grp` varchar(191) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  UNIQUE KEY `i_sr_user_jid_group` (`jid`(75),`grp`(75)),
  KEY `i_sr_user_grp` (`grp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sr_user`
--

LOCK TABLES `sr_user` WRITE;
/*!40000 ALTER TABLE `sr_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `sr_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `username` varchar(191) NOT NULL,
  `type` smallint(6) NOT NULL,
  `password` text NOT NULL,
  `serverkey` varchar(128) NOT NULL DEFAULT '',
  `salt` varchar(128) NOT NULL DEFAULT '',
  `iterationcount` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`username`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vcard`
--

DROP TABLE IF EXISTS `vcard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vcard` (
  `username` varchar(191) NOT NULL,
  `vcard` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vcard`
--

LOCK TABLES `vcard` WRITE;
/*!40000 ALTER TABLE `vcard` DISABLE KEYS */;
/*!40000 ALTER TABLE `vcard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vcard_search`
--

DROP TABLE IF EXISTS `vcard_search`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vcard_search` (
  `username` varchar(191) NOT NULL,
  `lusername` varchar(191) NOT NULL,
  `fn` text NOT NULL,
  `lfn` varchar(191) NOT NULL,
  `family` text NOT NULL,
  `lfamily` varchar(191) NOT NULL,
  `given` text NOT NULL,
  `lgiven` varchar(191) NOT NULL,
  `middle` text NOT NULL,
  `lmiddle` varchar(191) NOT NULL,
  `nickname` text NOT NULL,
  `lnickname` varchar(191) NOT NULL,
  `bday` text NOT NULL,
  `lbday` varchar(191) NOT NULL,
  `ctry` text NOT NULL,
  `lctry` varchar(191) NOT NULL,
  `locality` text NOT NULL,
  `llocality` varchar(191) NOT NULL,
  `email` text NOT NULL,
  `lemail` varchar(191) NOT NULL,
  `orgname` text NOT NULL,
  `lorgname` varchar(191) NOT NULL,
  `orgunit` text NOT NULL,
  `lorgunit` varchar(191) NOT NULL,
  PRIMARY KEY (`lusername`),
  KEY `i_vcard_search_lfn` (`lfn`),
  KEY `i_vcard_search_lfamily` (`lfamily`),
  KEY `i_vcard_search_lgiven` (`lgiven`),
  KEY `i_vcard_search_lmiddle` (`lmiddle`),
  KEY `i_vcard_search_lnickname` (`lnickname`),
  KEY `i_vcard_search_lbday` (`lbday`),
  KEY `i_vcard_search_lctry` (`lctry`),
  KEY `i_vcard_search_llocality` (`llocality`),
  KEY `i_vcard_search_lemail` (`lemail`),
  KEY `i_vcard_search_lorgname` (`lorgname`),
  KEY `i_vcard_search_lorgunit` (`lorgunit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vcard_search`
--

LOCK TABLES `vcard_search` WRITE;
/*!40000 ALTER TABLE `vcard_search` DISABLE KEYS */;
/*!40000 ALTER TABLE `vcard_search` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-06 12:37:51
