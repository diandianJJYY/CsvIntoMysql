/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : demo

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2016-07-21 08:43:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users_5
-- ----------------------------
DROP TABLE IF EXISTS `users_5`;
CREATE TABLE `users_5` (
  `user_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `sex` tinyint(1) unsigned DEFAULT '0',
  `last_login` int(11) unsigned DEFAULT '0',
  `last_ip` varchar(15) NOT NULL,
  `visit_count` smallint(5) unsigned DEFAULT '0',
  `msn` varchar(60) DEFAULT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `mobile_phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_name` (`user_name`,`password`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4832944 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
SET FOREIGN_KEY_CHECKS=1;
