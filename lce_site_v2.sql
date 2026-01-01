/*
 Navicat MySQL Data Transfer

 Source Server         : LCE - Hetzner - Prod
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42)
 Source Host           : localhost:3306
 Source Schema         : lce_site_v2

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42)
 File Encoding         : 65001

 Date: 31/12/2025 08:56:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for lce_communication_settings
-- ----------------------------
DROP TABLE IF EXISTS `lce_communication_settings`;
CREATE TABLE `lce_communication_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `pickup_confirm_email` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pickup_reminder_email` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `picked_up_email` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outfordelivery_email` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivered_email` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `outfordelivery_sms` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `picked_up_sms` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivered_sms` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pickup_confirm_sms` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8662 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_communication_settings
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_configurations
-- ----------------------------
DROP TABLE IF EXISTS `lce_configurations`;
CREATE TABLE `lce_configurations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `value` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_configurations
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_holidays_logs
-- ----------------------------
DROP TABLE IF EXISTS `lce_holidays_logs`;
CREATE TABLE `lce_holidays_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `holiday_date` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email_ids` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mail_sent` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `filter_date` date DEFAULT NULL,
  `email_count` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_holidays_logs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_nolaundry_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `lce_nolaundry_sms_log`;
CREATE TABLE `lce_nolaundry_sms_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `pickup_id` int NOT NULL,
  `nolaundry_date` datetime NOT NULL,
  `phone_number` varchar(32) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `msg_sent` tinyint(1) NOT NULL,
  `reply_text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `reply_date` datetime NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=918 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_nolaundry_sms_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_payment
-- ----------------------------
DROP TABLE IF EXISTS `lce_payment`;
CREATE TABLE `lce_payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `note` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `deleted` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cuser_id` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_payment
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_pickup_nonworking_days
-- ----------------------------
DROP TABLE IF EXISTS `lce_pickup_nonworking_days`;
CREATE TABLE `lce_pickup_nonworking_days` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `area` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_pickup_nonworking_days
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_pickup_zones
-- ----------------------------
DROP TABLE IF EXISTS `lce_pickup_zones`;
CREATE TABLE `lce_pickup_zones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `zip` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `city` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `state` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_monday` tinyint(1) DEFAULT NULL,
  `day_tuesday` tinyint(1) DEFAULT NULL,
  `day_wednesday` tinyint(1) DEFAULT NULL,
  `day_thursday` tinyint(1) DEFAULT NULL,
  `day_friday` tinyint(1) DEFAULT NULL,
  `area` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `drivers` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `order` smallint DEFAULT NULL,
  `geometry` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `geo_location` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=218 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_pickup_zones
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_prices
-- ----------------------------
DROP TABLE IF EXISTS `lce_prices`;
CREATE TABLE `lce_prices` (
  `id` double NOT NULL AUTO_INCREMENT,
  `sku` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `type` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `price_198` float DEFAULT '0',
  `price_197` float DEFAULT '0',
  `price_196` float DEFAULT '0',
  `price_195` float DEFAULT '0',
  `price_194` float DEFAULT '0',
  `price_193` float DEFAULT '0',
  `price_192` float DEFAULT '0',
  `price_191` float DEFAULT '0',
  `price_190` float DEFAULT '0',
  `price_189` float DEFAULT '0',
  `price_188` float DEFAULT '0',
  `price_187` float DEFAULT '0',
  `price_186` float DEFAULT '0',
  `price_185` float DEFAULT '0',
  `price_184` float DEFAULT '0',
  `price_183` float DEFAULT '0',
  `price_182` float DEFAULT '0',
  `price_181` float DEFAULT '0',
  `price_180` float DEFAULT '0',
  `price_179` float DEFAULT '0',
  `price_178` float DEFAULT '0',
  `price_177` float DEFAULT '0',
  `price_176` float DEFAULT '0',
  `price_175` float DEFAULT '0',
  `price_174` float DEFAULT '0',
  `price_173` float DEFAULT '0',
  `price_172` float DEFAULT '0',
  `price_171` float DEFAULT '0',
  `price_170` float DEFAULT '0',
  `price_169` float DEFAULT '0',
  `price_168` float DEFAULT '0',
  `price_167` float DEFAULT '0',
  `price_166` float DEFAULT '0',
  `price_165` float DEFAULT '0',
  `price_164` float DEFAULT '0',
  `price_163` float DEFAULT '0',
  `price_162` float DEFAULT '0',
  `price_161` float DEFAULT '0',
  `price_160` float DEFAULT '0',
  `price_159` float DEFAULT '0',
  `price_158` float DEFAULT '0',
  `price_157` float DEFAULT '0',
  `price_156` float DEFAULT '0',
  `price_155` float DEFAULT '0',
  `price_154` float DEFAULT '0',
  `price_153` float DEFAULT '0',
  `price_152` float DEFAULT '0',
  `price_151` float DEFAULT '0',
  `price_150` float DEFAULT '0',
  `price_149` float DEFAULT '0',
  `price_148` float DEFAULT '0',
  `price_147` float DEFAULT '0',
  `price_146` float DEFAULT '0',
  `price_145` float DEFAULT '0',
  `price_144` float DEFAULT '0',
  `price_143` float DEFAULT '0',
  `price_142` float DEFAULT '0',
  `price_141` float DEFAULT '0',
  `price_140` float DEFAULT '0',
  `price_139` float DEFAULT '0',
  `price_138` float DEFAULT '0',
  `price_137` float DEFAULT '0',
  `price_136` float DEFAULT '0',
  `price_135` float DEFAULT '0',
  `price_134` float DEFAULT '0',
  `price_133` float DEFAULT '0',
  `price_132` float DEFAULT '0',
  `price_131` float DEFAULT '0',
  `price_130` float DEFAULT '0',
  `price_129` float DEFAULT '0',
  `price_128` float DEFAULT '0',
  `price_127` float DEFAULT '0',
  `price_126` float DEFAULT '0',
  `price_125` float DEFAULT '0',
  `price_124` float DEFAULT '0',
  `price_123` float DEFAULT '0',
  `price_122` float DEFAULT '0',
  `price_121` float DEFAULT '0',
  `price_120` float DEFAULT '0',
  `price_119` float DEFAULT '0',
  `price_118` float DEFAULT '0',
  `price_117` float DEFAULT '0',
  `price_116` float DEFAULT '0',
  `price_115` float DEFAULT '0',
  `price_114` float DEFAULT '0',
  `price_113` float DEFAULT '0',
  `price_112` float DEFAULT '0',
  `price_111` float DEFAULT '0',
  `price_110` float DEFAULT '0',
  `price_109` float DEFAULT '0',
  `price_108` float DEFAULT '0',
  `price_107` float DEFAULT '0',
  `price_106` float DEFAULT '0',
  `price_105` float DEFAULT '0',
  `price_104` float DEFAULT '0',
  `price_103` float DEFAULT '0',
  `price_102` float DEFAULT '0',
  `price_101` float DEFAULT '0',
  `price_100` float DEFAULT '0',
  `price_99` float DEFAULT '0',
  `price_98` float DEFAULT '0',
  `price_97` float DEFAULT '0',
  `price_96` float DEFAULT '0',
  `price_95` float DEFAULT '0',
  `price_94` float DEFAULT '0',
  `price_93` float DEFAULT '0',
  `price_92` float DEFAULT '0',
  `price_91` float DEFAULT '0',
  `price_90` float DEFAULT '0',
  `price_89` float DEFAULT '0',
  `price_88` float DEFAULT '0',
  `price_87` float DEFAULT '0',
  `price_86` float DEFAULT '0',
  `price_85` float DEFAULT '0',
  `price_84` float DEFAULT '0',
  `price_83` float DEFAULT '0',
  `price_82` float DEFAULT '0',
  `price_81` float DEFAULT '0',
  `price_80` float DEFAULT '0',
  `price_79` float DEFAULT '0',
  `price_78` float DEFAULT '0',
  `price_77` float DEFAULT '0',
  `price_76` float DEFAULT '0',
  `price_75` float DEFAULT '0',
  `price_74` float DEFAULT '0',
  `price_73` float DEFAULT '0',
  `price_72` float DEFAULT '0',
  `price_71` float DEFAULT '0',
  `price_70` float DEFAULT '0',
  `price_69` float DEFAULT '0',
  `price_68` float DEFAULT '0',
  `price_67` float DEFAULT '0',
  `price_66` float DEFAULT '0',
  `price_65` float DEFAULT '0',
  `price_64` float DEFAULT '0',
  `price_63` float DEFAULT '0',
  `price_62` float DEFAULT '0',
  `price_61` float DEFAULT '0',
  `price_60` float DEFAULT '0',
  `price_59` float DEFAULT '0',
  `price_58` float DEFAULT '0',
  `price_57` float DEFAULT '0',
  `price_56` float DEFAULT '0',
  `price_55` float DEFAULT '0',
  `price_54` float DEFAULT '0',
  `price_53` float DEFAULT '0',
  `price_52` float DEFAULT '0',
  `price_51` float DEFAULT '0',
  `price_50` float DEFAULT '0',
  `price_49` float DEFAULT '0',
  `price_48` float DEFAULT '0',
  `price_47` float DEFAULT '0',
  `price_46` float DEFAULT '0',
  `price_45` float DEFAULT '0',
  `price_44` float DEFAULT '0',
  `price_43` float DEFAULT '0',
  `price_42` float DEFAULT '0',
  `price_41` float DEFAULT '0',
  `price_40` float DEFAULT '0',
  `price_39` float DEFAULT '0',
  `price_38` float DEFAULT '0',
  `price_37` float DEFAULT '0',
  `price_36` float DEFAULT '0',
  `price_35` float DEFAULT '0',
  `price_34` float DEFAULT '0',
  `price_33` float DEFAULT '0',
  `price_32` float DEFAULT '0',
  `price_31` float DEFAULT '0',
  `price_30` float DEFAULT '0',
  `price_29` float DEFAULT '0',
  `price_28` float DEFAULT '0',
  `price_27` float DEFAULT '0',
  `price_26` float DEFAULT '0',
  `price_25` float DEFAULT '0',
  `price_24` float DEFAULT '0',
  `price_23` float DEFAULT '0',
  `price_22` float DEFAULT '0',
  `price_21` float DEFAULT '0',
  `price_20` float DEFAULT '0',
  `price_19` float DEFAULT '0',
  `price_18` float DEFAULT '0',
  `price_17` float DEFAULT '0',
  `price_16` float DEFAULT '0',
  `price_15` float DEFAULT '0',
  `price_14` float DEFAULT '0',
  `price_13` float DEFAULT '0',
  `price_12` float DEFAULT '0',
  `price_11` float DEFAULT '0',
  `price_10` float DEFAULT '0',
  `price_9` float DEFAULT '0',
  `price_8` float DEFAULT '0',
  `price_7` float DEFAULT '0',
  `price_6` float DEFAULT '0',
  `price_1` float DEFAULT '0' COMMENT 'Default',
  `price_2` float DEFAULT '0',
  `price_3` float DEFAULT '0',
  `order` int DEFAULT NULL,
  `deleted` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_prices
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_prices_copy1
-- ----------------------------
DROP TABLE IF EXISTS `lce_prices_copy1`;
CREATE TABLE `lce_prices_copy1` (
  `id` double NOT NULL AUTO_INCREMENT,
  `sku` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `type` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `price_198` float DEFAULT '0',
  `price_197` float DEFAULT '0',
  `price_196` float DEFAULT '0',
  `price_195` float DEFAULT '0',
  `price_194` float DEFAULT '0',
  `price_193` float DEFAULT '0',
  `price_192` float DEFAULT '0',
  `price_191` float DEFAULT '0',
  `price_190` float DEFAULT '0',
  `price_189` float DEFAULT '0',
  `price_188` float DEFAULT '0',
  `price_187` float DEFAULT '0',
  `price_186` float DEFAULT '0',
  `price_185` float DEFAULT '0',
  `price_184` float DEFAULT '0',
  `price_183` float DEFAULT '0',
  `price_182` float DEFAULT '0',
  `price_181` float DEFAULT '0',
  `price_180` float DEFAULT '0',
  `price_179` float DEFAULT '0',
  `price_178` float DEFAULT '0',
  `price_177` float DEFAULT '0',
  `price_176` float DEFAULT '0',
  `price_175` float DEFAULT '0',
  `price_174` float DEFAULT '0',
  `price_173` float DEFAULT '0',
  `price_172` float DEFAULT '0',
  `price_171` float DEFAULT '0',
  `price_170` float DEFAULT '0',
  `price_169` float DEFAULT '0',
  `price_168` float DEFAULT '0',
  `price_167` float DEFAULT '0',
  `price_166` float DEFAULT '0',
  `price_165` float DEFAULT '0',
  `price_164` float DEFAULT '0',
  `price_163` float DEFAULT '0',
  `price_162` float DEFAULT '0',
  `price_161` float DEFAULT '0',
  `price_160` float DEFAULT '0',
  `price_159` float DEFAULT '0',
  `price_158` float DEFAULT '0',
  `price_157` float DEFAULT '0',
  `price_156` float DEFAULT '0',
  `price_155` float DEFAULT '0',
  `price_154` float DEFAULT '0',
  `price_153` float DEFAULT '0',
  `price_152` float DEFAULT '0',
  `price_151` float DEFAULT '0',
  `price_150` float DEFAULT '0',
  `price_149` float DEFAULT '0',
  `price_148` float DEFAULT '0',
  `price_147` float DEFAULT '0',
  `price_146` float DEFAULT '0',
  `price_145` float DEFAULT '0',
  `price_144` float DEFAULT '0',
  `price_143` float DEFAULT '0',
  `price_142` float DEFAULT '0',
  `price_141` float DEFAULT '0',
  `price_140` float DEFAULT '0',
  `price_139` float DEFAULT '0',
  `price_138` float DEFAULT '0',
  `price_137` float DEFAULT '0',
  `price_136` float DEFAULT '0',
  `price_135` float DEFAULT '0',
  `price_134` float DEFAULT '0',
  `price_133` float DEFAULT '0',
  `price_132` float DEFAULT '0',
  `price_131` float DEFAULT '0',
  `price_130` float DEFAULT '0',
  `price_129` float DEFAULT '0',
  `price_128` float DEFAULT '0',
  `price_127` float DEFAULT '0',
  `price_126` float DEFAULT '0',
  `price_125` float DEFAULT '0',
  `price_124` float DEFAULT '0',
  `price_123` float DEFAULT '0',
  `price_122` float DEFAULT '0',
  `price_121` float DEFAULT '0',
  `price_120` float DEFAULT '0',
  `price_119` float DEFAULT '0',
  `price_118` float DEFAULT '0',
  `price_117` float DEFAULT '0',
  `price_116` float DEFAULT '0',
  `price_115` float DEFAULT '0',
  `price_114` float DEFAULT '0',
  `price_113` float DEFAULT '0',
  `price_112` float DEFAULT '0',
  `price_111` float DEFAULT '0',
  `price_110` float DEFAULT '0',
  `price_109` float DEFAULT '0',
  `price_108` float DEFAULT '0',
  `price_107` float DEFAULT '0',
  `price_106` float DEFAULT '0',
  `price_105` float DEFAULT '0',
  `price_104` float DEFAULT '0',
  `price_103` float DEFAULT '0',
  `price_102` float DEFAULT '0',
  `price_101` float DEFAULT '0',
  `price_100` float DEFAULT '0',
  `price_99` float DEFAULT '0',
  `price_98` float DEFAULT '0',
  `price_97` float DEFAULT '0',
  `price_96` float DEFAULT '0',
  `price_95` float DEFAULT '0',
  `price_94` float DEFAULT '0',
  `price_93` float DEFAULT '0',
  `price_92` float DEFAULT '0',
  `price_91` float DEFAULT '0',
  `price_90` float DEFAULT '0',
  `price_89` float DEFAULT '0',
  `price_88` float DEFAULT '0',
  `price_87` float DEFAULT '0',
  `price_86` float DEFAULT '0',
  `price_85` float DEFAULT '0',
  `price_84` float DEFAULT '0',
  `price_83` float DEFAULT '0',
  `price_82` float DEFAULT '0',
  `price_81` float DEFAULT '0',
  `price_80` float DEFAULT '0',
  `price_79` float DEFAULT '0',
  `price_78` float DEFAULT '0',
  `price_77` float DEFAULT '0',
  `price_76` float DEFAULT '0',
  `price_75` float DEFAULT '0',
  `price_74` float DEFAULT '0',
  `price_73` float DEFAULT '0',
  `price_72` float DEFAULT '0',
  `price_71` float DEFAULT '0',
  `price_70` float DEFAULT '0',
  `price_69` float DEFAULT '0',
  `price_68` float DEFAULT '0',
  `price_67` float DEFAULT '0',
  `price_66` float DEFAULT '0',
  `price_65` float DEFAULT '0',
  `price_64` float DEFAULT '0',
  `price_63` float DEFAULT '0',
  `price_62` float DEFAULT '0',
  `price_61` float DEFAULT '0',
  `price_60` float DEFAULT '0',
  `price_59` float DEFAULT '0',
  `price_58` float DEFAULT '0',
  `price_57` float DEFAULT '0',
  `price_56` float DEFAULT '0',
  `price_55` float DEFAULT '0',
  `price_54` float DEFAULT '0',
  `price_53` float DEFAULT '0',
  `price_52` float DEFAULT '0',
  `price_51` float DEFAULT '0',
  `price_50` float DEFAULT '0',
  `price_49` float DEFAULT '0',
  `price_48` float DEFAULT '0',
  `price_47` float DEFAULT '0',
  `price_46` float DEFAULT '0',
  `price_45` float DEFAULT '0',
  `price_44` float DEFAULT '0',
  `price_43` float DEFAULT '0',
  `price_42` float DEFAULT '0',
  `price_41` float DEFAULT '0',
  `price_40` float DEFAULT '0',
  `price_39` float DEFAULT '0',
  `price_38` float DEFAULT '0',
  `price_37` float DEFAULT '0',
  `price_36` float DEFAULT '0',
  `price_35` float DEFAULT '0',
  `price_34` float DEFAULT '0',
  `price_33` float DEFAULT '0',
  `price_32` float DEFAULT '0',
  `price_31` float DEFAULT '0',
  `price_30` float DEFAULT '0',
  `price_29` float DEFAULT '0',
  `price_28` float DEFAULT '0',
  `price_27` float DEFAULT '0',
  `price_26` float DEFAULT '0',
  `price_25` float DEFAULT '0',
  `price_24` float DEFAULT '0',
  `price_23` float DEFAULT '0',
  `price_22` float DEFAULT '0',
  `price_21` float DEFAULT '0',
  `price_20` float DEFAULT '0',
  `price_19` float DEFAULT '0',
  `price_18` float DEFAULT '0',
  `price_17` float DEFAULT '0',
  `price_16` float DEFAULT '0',
  `price_15` float DEFAULT '0',
  `price_14` float DEFAULT '0',
  `price_13` float DEFAULT '0',
  `price_12` float DEFAULT '0',
  `price_11` float DEFAULT '0',
  `price_10` float DEFAULT '0',
  `price_9` float DEFAULT '0',
  `price_8` float DEFAULT '0',
  `price_7` float DEFAULT '0',
  `price_6` float DEFAULT '0',
  `price_1` float DEFAULT '0' COMMENT 'Default',
  `price_2` float DEFAULT '0',
  `price_3` float DEFAULT '0',
  `order` int DEFAULT NULL,
  `deleted` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_prices_copy1
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_prices_lists
-- ----------------------------
DROP TABLE IF EXISTS `lce_prices_lists`;
CREATE TABLE `lce_prices_lists` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 're',
  `name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `zip` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `order` int DEFAULT NULL,
  `deleted` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=199 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_prices_lists
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_processing_sites
-- ----------------------------
DROP TABLE IF EXISTS `lce_processing_sites`;
CREATE TABLE `lce_processing_sites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_1` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_2` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_1` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_2` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `city` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `country` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'US',
  `zip` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `area` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `print_align` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `wf` tinyint(1) DEFAULT NULL,
  `dc` tinyint(1) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `prices_lists_id` int DEFAULT '1',
  `area_group_id` int DEFAULT NULL,
  `user_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_processing_sites
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_processing_sites_8aug
-- ----------------------------
DROP TABLE IF EXISTS `lce_processing_sites_8aug`;
CREATE TABLE `lce_processing_sites_8aug` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_1` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_2` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_1` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_2` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `city` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `country` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'US',
  `zip` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `area` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `print_align` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `wf` tinyint(1) DEFAULT NULL,
  `dc` tinyint(1) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `prices_lists_id` int DEFAULT '1',
  `area_group_id` int DEFAULT NULL,
  `user_group_id` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_processing_sites_8aug
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_promo_codes
-- ----------------------------
DROP TABLE IF EXISTS `lce_promo_codes`;
CREATE TABLE `lce_promo_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `promocode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `promocode_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `promocode_value` float NOT NULL,
  `publish` tinyint(1) NOT NULL,
  `promocode_time_period` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `time_period_value` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `promo_expiry_date` date NOT NULL,
  `promocode_for` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `promocode_description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_date` date DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_promo_codes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_subscription_plans
-- ----------------------------
DROP TABLE IF EXISTS `lce_subscription_plans`;
CREATE TABLE `lce_subscription_plans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bags_per_month` int NOT NULL,
  `price_per_bag` decimal(10,2) NOT NULL,
  `billing_cycle` enum('monthly','annual') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `annual_discount` decimal(5,2) DEFAULT '15.00',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of lce_subscription_plans
-- ----------------------------
BEGIN;
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (1, 'SUB_M_1BAG', 'Subscribe & Save Monthly - 1 Bag', 1, 70.00, 'monthly', 0.00, 1, '2025-12-12 16:04:38', '2025-12-12 16:04:41');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (2, 'SUB_M_2BAG', 'Subscribe & Save Monthly - 2 Bags', 2, 67.00, 'monthly', 0.00, 1, '2025-12-12 16:06:06', '2025-12-12 16:06:06');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (3, 'SUB_M_4BAG', 'Subscribe & Save Monthly - 4 Bags', 4, 65.00, 'monthly', 0.00, 1, '2025-12-12 16:06:34', '2025-12-12 16:06:34');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (4, 'SUB_M_8BAG', 'Subscribe & Save Monthly - 8 Bags', 8, 64.00, 'monthly', 0.00, 1, '2025-12-12 16:06:55', '2025-12-12 16:06:55');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (5, 'SUB_A_1BAG', 'Subscribe & Save Annual - 1 Bag', 1, 70.00, 'annual', 15.00, 1, '2025-12-12 16:08:03', '2025-12-12 16:08:03');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (6, 'SUB_A_2BAG', 'Subscribe & Save Annual - 2 Bags', 2, 67.00, 'annual', 15.00, 1, '2025-12-12 16:08:23', '2025-12-12 16:08:23');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (7, 'SUB_A_4BAG', 'Subscribe & Save Annual - 4 Bags', 4, 65.00, 'annual', 15.00, 1, '2025-12-12 16:08:47', '2025-12-12 16:08:47');
INSERT INTO `lce_subscription_plans` (`id`, `code`, `name`, `bags_per_month`, `price_per_bag`, `billing_cycle`, `annual_discount`, `active`, `cdate`, `mdate`) VALUES (8, 'SUB_A_8BAG', 'Subscribe & Save Annual - 8 Bags', 8, 64.00, 'annual', 15.00, 1, '2025-12-12 16:09:06', '2025-12-12 16:09:06');
COMMIT;

-- ----------------------------
-- Table structure for lce_tmp_group_members
-- ----------------------------
DROP TABLE IF EXISTS `lce_tmp_group_members`;
CREATE TABLE `lce_tmp_group_members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_transaction_limit` decimal(10,2) NOT NULL,
  `monthly_wf_limit` tinyint(1) NOT NULL,
  `monthly_dc_limit` tinyint(1) NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cdate` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_tmp_group_members
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_tmp_invited_group_members_log
-- ----------------------------
DROP TABLE IF EXISTS `lce_tmp_invited_group_members_log`;
CREATE TABLE `lce_tmp_invited_group_members_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invited_emails` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `restricted_emails` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_tmp_invited_group_members_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_tmp_nolaundry_transactions
-- ----------------------------
DROP TABLE IF EXISTS `lce_tmp_nolaundry_transactions`;
CREATE TABLE `lce_tmp_nolaundry_transactions` (
  `intid` int NOT NULL AUTO_INCREMENT,
  `pickup_id` int NOT NULL,
  `user_id` int NOT NULL,
  `transaction_id` bigint NOT NULL,
  `date_added` datetime NOT NULL,
  `status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`intid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1862 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_tmp_nolaundry_transactions
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_tmp_register_emails
-- ----------------------------
DROP TABLE IF EXISTS `lce_tmp_register_emails`;
CREATE TABLE `lce_tmp_register_emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `zip` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cdate` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9669 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_tmp_register_emails
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_credits
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_credits`;
CREATE TABLE `lce_user_credits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` enum('welcome','promo','manual','refund') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of lce_user_credits
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_cs
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_cs`;
CREATE TABLE `lce_user_cs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'open',
  `invoice_number` int DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `action` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `hear_about` varchar(64) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `owner_id` int DEFAULT NULL,
  `cuser_id` int DEFAULT NULL,
  `muser_id` int DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5725 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_cs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_cs_log
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_cs_log`;
CREATE TABLE `lce_user_cs_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cs_id` int DEFAULT NULL,
  `note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `action` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cuser_id` int DEFAULT NULL,
  `muser_id` int DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1608 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_cs_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_group_admin
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_group_admin`;
CREATE TABLE `lce_user_group_admin` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_type` enum('Independent','InHouse','') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint NOT NULL,
  `date_added` date NOT NULL,
  `published` int NOT NULL,
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_group_admin
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_group_log
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_group_log`;
CREATE TABLE `lce_user_group_log` (
  `intid` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `group_admin_id` int NOT NULL,
  `pickup_id` int NOT NULL,
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_added` date NOT NULL,
  PRIMARY KEY (`intid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_group_log
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_group_members
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_group_members`;
CREATE TABLE `lce_user_group_members` (
  `intid` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `admin_user_id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `monthly_transaction_limit` decimal(10,2) NOT NULL,
  `monthly_wf_limit` tinyint(1) NOT NULL,
  `monthly_dc_limit` tinyint(1) NOT NULL,
  `monthly_wf_dc_limit` int NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `approved` int NOT NULL,
  `date_added` date NOT NULL,
  `published` int NOT NULL,
  `current_status` int NOT NULL,
  PRIMARY KEY (`intid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_group_members
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_group_members_history
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_group_members_history`;
CREATE TABLE `lce_user_group_members_history` (
  `intid` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `group_admin_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_amount` float NOT NULL,
  `wf_orders` int NOT NULL,
  `dc_orders` int NOT NULL,
  `wf_dc_orders` int NOT NULL,
  PRIMARY KEY (`intid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_group_members_history
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_info
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_info`;
CREATE TABLE `lce_user_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `user_md` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `last_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `first_name` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_1` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_2` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `phone_3` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cell_phone_1` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `opt_in` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `opt_in_log` varchar(5000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_1` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `address_2` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `city` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `state` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `zip` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `country` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'US',
  `nearest_cross_street` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `geo_address` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `geo_lat` float(10,6) DEFAULT NULL,
  `geo_lng` float(10,6) DEFAULT NULL,
  `payment_type` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_cc_number` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_cc_edate_month` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_cc_edate_year` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `customerProfileId` int DEFAULT NULL,
  `customerPaymentProfileId` int DEFAULT NULL,
  `payment_phone` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_address_1` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_address_2` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_city` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_state` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_zip` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `payment_country` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'US',
  `driver_instructions` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `driver_instructions_mdate` datetime DEFAULT NULL,
  `driver_comments` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `driver_comments_mdate` datetime DEFAULT NULL,
  `laundry_instructions` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `laundry_instructions_mdate` datetime DEFAULT NULL,
  `attendant_comments` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `attendant_comments_mdate` datetime DEFAULT NULL,
  `laundry_pref_detergent` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `laundry_pref_softener` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `laundry_pref_bleach` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `laundry_pref_hanging` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `laundry_pref_starch` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `laundry_pref_shirts` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `price_list_id` int NOT NULL DEFAULT '1',
  `pd_upcharge` float DEFAULT NULL,
  `nolaundry_charge` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_monday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_tuesday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_wednesday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_thursday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_friday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_saturday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `asd_sunday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `hold_date` datetime DEFAULT NULL,
  `suspention_date` datetime DEFAULT NULL,
  `suspention_note` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `trust_level` int DEFAULT '0',
  `hear_about` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `email_invoice_on_delivery` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `wash_fold_instructions` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `customer_type` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `custom_minimum_charge` float NOT NULL,
  `mdate` datetime DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_user_info_user_id` (`user_id`) USING BTREE,
  FULLTEXT KEY `last_name` (`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7889 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='Customer Information, BT = BillTo and ST = ShipTo';

-- ----------------------------
-- Records of lce_user_info
-- ----------------------------
BEGIN;
INSERT INTO `lce_user_info` (`id`, `user_id`, `user_md`, `email`, `last_name`, `first_name`, `phone_1`, `phone_2`, `phone_3`, `cell_phone_1`, `opt_in`, `opt_in_log`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `nearest_cross_street`, `geo_address`, `geo_lat`, `geo_lng`, `payment_type`, `payment_cc_number`, `payment_cc_edate_month`, `payment_cc_edate_year`, `customerProfileId`, `customerPaymentProfileId`, `payment_phone`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_state`, `payment_zip`, `payment_country`, `driver_instructions`, `driver_instructions_mdate`, `driver_comments`, `driver_comments_mdate`, `laundry_instructions`, `laundry_instructions_mdate`, `attendant_comments`, `attendant_comments_mdate`, `laundry_pref_detergent`, `laundry_pref_softener`, `laundry_pref_bleach`, `laundry_pref_hanging`, `laundry_pref_starch`, `laundry_pref_shirts`, `price_list_id`, `pd_upcharge`, `nolaundry_charge`, `asd_monday`, `asd_tuesday`, `asd_wednesday`, `asd_thursday`, `asd_friday`, `asd_saturday`, `asd_sunday`, `hold_date`, `suspention_date`, `suspention_note`, `trust_level`, `hear_about`, `email_invoice_on_delivery`, `wash_fold_instructions`, `customer_type`, `custom_minimum_charge`, `mdate`, `cdate`) VALUES (7654, 117530, '70bbcdf6bf5929e28bdcc2e591970d81', 'jpmejia@msn.com', 'Mejia', 'Juan Pablo', '', NULL, NULL, '(646) 515-4068', 'Yes', 'Yes,2025-07-23 13:47:54;No,2025-10-09 14:12:32', '3185 La Mesa Dr.', '', 'San Carlos', 'CA', '94065', 'US', NULL, '3185 La Mesa Dr.', 37.480766, -122.278015, 'mc', '****1510', '12', '2030', 764258807, 1314458163, NULL, '3185 La Mesa Dr.', NULL, 'San Carlos', 'CA', '94070', 'US', '', '2025-07-23 13:47:54', NULL, NULL, '', '2025-07-23 13:47:54', '', NULL, 'Unscented', 'No', 'Yes', 'Hang_and_Fold', 'None', '', 1, 0, '', '', '', '', '', '', NULL, NULL, NULL, NULL, '', 0, NULL, 'No', '', 'commercial', 0, '2025-10-09 14:12:32', '2025-07-23 13:47:54');
COMMIT;

-- ----------------------------
-- Table structure for lce_user_invoice
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_invoice`;
CREATE TABLE `lce_user_invoice` (
  `id` int NOT NULL AUTO_INCREMENT,
  `number` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `status` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `wf_site_id` int DEFAULT NULL,
  `dc_site_id` int DEFAULT NULL,
  `wholesale_total_wf` float DEFAULT NULL,
  `wholesale_total_dc` float DEFAULT NULL,
  `wholesale_total` float DEFAULT NULL,
  `sub_total_wf` float DEFAULT NULL,
  `sub_total_dc` float DEFAULT NULL,
  `sub_total` float DEFAULT NULL,
  `pickup_charge` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `deleted` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `errors` tinyblob,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `promo_id` int NOT NULL,
  `promocode` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `promo_amount` decimal(10,2) NOT NULL,
  `group_admin_id` int NOT NULL,
  `group_admin_discount_amount` float NOT NULL,
  `partial_invoice` tinyint NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `idx_number` (`number`) USING BTREE,
  KEY `idx_user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=156934 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_invoice
-- ----------------------------
BEGIN;
INSERT INTO `lce_user_invoice` (`id`, `number`, `user_id`, `status`, `wf_site_id`, `dc_site_id`, `wholesale_total_wf`, `wholesale_total_dc`, `wholesale_total`, `sub_total_wf`, `sub_total_dc`, `sub_total`, `pickup_charge`, `total`, `deleted`, `errors`, `cdate`, `mdate`, `promo_id`, `promocode`, `promo_amount`, `group_admin_id`, `group_admin_discount_amount`, `partial_invoice`) VALUES (151334, 7632644, 117530, 'processed', 0, 0, 10.5, 0, 10.5, 44.99, 0, 44.99, 0, 44.99, 'No', NULL, '2025-07-24 11:54:10', '2025-07-24 21:35:13', 0, '', 0.00, 0, 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for lce_user_invoice_line
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_invoice_line`;
CREATE TABLE `lce_user_invoice_line` (
  `id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL COMMENT 'lce_prices',
  `site_id` int DEFAULT NULL,
  `sku` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `type` varchar(25) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `wholesale_price` float DEFAULT NULL,
  `wholesale_amount` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `note` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `deleted` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'No',
  `order` int DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_invoice_id` (`invoice_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=249795 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_invoice_line
-- ----------------------------
BEGIN;
INSERT INTO `lce_user_invoice_line` (`id`, `invoice_id`, `item_id`, `site_id`, `sku`, `type`, `name`, `quantity`, `wholesale_price`, `wholesale_amount`, `price`, `amount`, `note`, `deleted`, `order`, `cdate`, `mdate`) VALUES (241371, 151334, 1, 14, 'WF1_1+', 'WF', 'Wash &amp; Fold Laundry ($2.79/lb; first 19 lbs $3.09/lb)', 10, NULL, 10.5, NULL, 30.9, 'Washer Cost: $ <br>Dryer Cost: $ <br>Weight: 10 lbs<br>', 'No', 2, '2025-07-24 21:35:12', '2025-07-24 21:35:12');
COMMIT;

-- ----------------------------
-- Table structure for lce_user_pickup
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_pickup`;
CREATE TABLE `lce_user_pickup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `pickup_type` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `pickup_date` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'pickup',
  `pickup_time` datetime DEFAULT NULL,
  `no_laundry_time` datetime DEFAULT NULL,
  `unload_wf_time` datetime DEFAULT NULL,
  `unload_dc_time` datetime DEFAULT NULL,
  `start_wf_time` datetime DEFAULT NULL,
  `start_dc_time` datetime DEFAULT NULL,
  `processing_wf_time` datetime DEFAULT NULL,
  `processing_dc_time` datetime DEFAULT NULL,
  `invoice_time` datetime DEFAULT NULL,
  `hold_time` datetime DEFAULT NULL,
  `unhold_time` datetime DEFAULT NULL,
  `load_wf_time` datetime DEFAULT NULL,
  `load_dc_time` datetime DEFAULT NULL,
  `delivery_time` datetime DEFAULT NULL,
  `cancelled_time` datetime DEFAULT NULL,
  `hold_status` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `wf_items` int DEFAULT NULL,
  `wf_bags_items` int DEFAULT NULL,
  `wf_hanger_items` int DEFAULT NULL,
  `wf_site_id` int DEFAULT NULL,
  `wf_washer_cost` float DEFAULT NULL,
  `wf_dryer_cost` float DEFAULT NULL,
  `wf_weight` int DEFAULT NULL,
  `wf_slip_number` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dc_items` int DEFAULT NULL,
  `dc_bags_items` int DEFAULT NULL,
  `dc_hanger_items` int DEFAULT NULL,
  `dc_site_id` int DEFAULT NULL,
  `dc_slip_number` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `invoice_id` int DEFAULT NULL,
  `customerPaymentTransId` bigint DEFAULT NULL,
  `customerPaymentTransAmount` float DEFAULT NULL,
  `plist_printed` tinyint(1) DEFAULT NULL,
  `keep_record` int NOT NULL,
  `geo_location` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `pickup_driver_id` int NOT NULL,
  `skipped_pickup` int NOT NULL DEFAULT '0',
  `deliver_driver_id` int NOT NULL,
  `on_vacation` tinyint NOT NULL DEFAULT '0',
  `group_admin_id` int NOT NULL,
  `group_code` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `partial_invoice` int NOT NULL,
  `group_invoice_id` int NOT NULL,
  `log` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `cuser_id` int DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `idx_user_id` (`user_id`) USING BTREE,
  KEY `idx_invoice_id` (`invoice_id`) USING BTREE,
  KEY `group_invoice_id` (`group_invoice_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=213921 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_pickup
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_promocode
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_promocode`;
CREATE TABLE `lce_user_promocode` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `promocode_id` int NOT NULL,
  `promocode` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `expiry_date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=653 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_promocode
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_promocodes
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_promocodes`;
CREATE TABLE `lce_user_promocodes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `promocode_id` int DEFAULT NULL,
  `promocode_name` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `used_date` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cdate` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_promocodes
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_rs
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_rs`;
CREATE TABLE `lce_user_rs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `day_monday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_tuesday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_wednesday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_thursday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_friday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_saturday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `day_sunday` varchar(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `delivey_type` varchar(16) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `comments` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `start_date` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7956 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_rs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_subscription_usage
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_subscription_usage`;
CREATE TABLE `lce_user_subscription_usage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_subscription_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `pickup_id` int NOT NULL,
  `bags_used` int NOT NULL DEFAULT '1',
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of lce_user_subscription_usage
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_subscriptions
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_subscriptions`;
CREATE TABLE `lce_user_subscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `status` enum('pending','active','paused','cancelled','upgraded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `billing_cycle` enum('monthly','annual') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `next_renewal_date` date NOT NULL,
  `bags_plan_period` int NOT NULL,
  `bags_plan_total` int NOT NULL,
  `bags_plan_balance` int NOT NULL DEFAULT '0',
  `bags_plan_used` int NOT NULL DEFAULT '0',
  `bags_available` int NOT NULL DEFAULT '1',
  `created_via` enum('web','intra','other') COLLATE utf8mb4_unicode_ci DEFAULT 'web',
  `payment_last` decimal(10,2) DEFAULT '0.00',
  `payment_discount` decimal(10,2) DEFAULT '0.00',
  `payment_balance` decimal(10,2) DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of lce_user_subscriptions
-- ----------------------------
BEGIN;
INSERT INTO `lce_user_subscriptions` (`id`, `user_id`, `plan_id`, `status`, `billing_cycle`, `start_date`, `end_date`, `next_renewal_date`, `bags_plan_period`, `bags_plan_total`, `bags_plan_balance`, `bags_plan_used`, `bags_available`, `created_via`, `payment_last`, `payment_discount`, `payment_balance`, `notes`, `cdate`, `mdate`) VALUES (1, 117530, 1, 'active', 'monthly', '2025-12-12', '2026-01-12', '2026-01-12', 1, 1, 1, 0, 1, 'web', 65.00, 0.00, 65.00, NULL, '2025-12-12 16:18:46', '2025-12-12 16:18:48');
COMMIT;

-- ----------------------------
-- Table structure for lce_user_transactions
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_transactions`;
CREATE TABLE `lce_user_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `invoice_id` int DEFAULT NULL,
  `transactionId` bigint DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `note` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cuserid` int DEFAULT NULL,
  `muserid` int DEFAULT NULL,
  `group_admin_id` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `USERID` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=318384 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_transactions
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_user_transactions_bk
-- ----------------------------
DROP TABLE IF EXISTS `lce_user_transactions_bk`;
CREATE TABLE `lce_user_transactions_bk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `invoice_id` int DEFAULT NULL,
  `transactionId` bigint DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `description` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `note` varchar(1000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `cdate` datetime DEFAULT NULL,
  `mdate` datetime DEFAULT NULL,
  `cuserid` int DEFAULT NULL,
  `muserid` int DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `USERID` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_user_transactions_bk
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_users_vacation_logs
-- ----------------------------
DROP TABLE IF EXISTS `lce_users_vacation_logs`;
CREATE TABLE `lce_users_vacation_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `start_date` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `end_date` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=668 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_users_vacation_logs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for lce_waiting_list
-- ----------------------------
DROP TABLE IF EXISTS `lce_waiting_list`;
CREATE TABLE `lce_waiting_list` (
  `id` int NOT NULL AUTO_INCREMENT,
  `zip` char(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '0',
  `notify_email` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `notified` enum('0','1') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT '0',
  `notify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `notify_email` (`notify_email`) USING BTREE,
  KEY `zip` (`zip`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10097 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of lce_waiting_list
-- ----------------------------
BEGIN;
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
