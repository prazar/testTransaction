/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : bil

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-08-03 23:39:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tb_users
-- ----------------------------
DROP TABLE IF EXISTS `tb_users`;
CREATE TABLE `tb_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(10,4) DEFAULT '0.0000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of tb_users
-- ----------------------------
INSERT INTO `tb_users` VALUES ('1', 'user1', 'd62935aee3f3cc4c511102a50a57c85e57e94646213cfbf5c492d8b1272986fb', 'Иванов Иван Иванович', '2000.0000');
INSERT INTO `tb_users` VALUES ('4', 'user2', 'd62935aee3f3cc4c511102a50a57c85e57e94646213cfbf5c492d8b1272986fb', 'Петров Алексей Григорьевич', '1500.0000');
INSERT INTO `tb_users` VALUES ('8', 'prime', 'd62935aee3f3cc4c511102a50a57c85e57e94646213cfbf5c492d8b1272986fb', 'Счет вывода средств', '0.0000');
