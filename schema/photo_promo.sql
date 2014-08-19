/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50534
Source Host           : localhost:3306
Source Database       : foodmp

Target Server Type    : MYSQL
Target Server Version : 50534
File Encoding         : 65001

Date: 2014-08-08 08:49:14
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `photo_promo`
-- ----------------------------
DROP TABLE IF EXISTS `photo_promo`;
CREATE TABLE `photo_promo` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `retailer_location` text NOT NULL,
  `consumer_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `thumb` varchar(100) NOT NULL,
  `description` varchar(250) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `approved` int(11) NOT NULL,
  `grand_final` int(1) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `retailer_name` text,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of photo_promo
-- ----------------------------
INSERT INTO photo_promo VALUES ('2', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 1', '2014-07-16 12:18:32', '1', '1', null, null, null);
INSERT INTO photo_promo VALUES ('3', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 2', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('4', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 3', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('5', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 4', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('6', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 5', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('7', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 6', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('9', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 8', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('10', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 9', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('11', '857271', '0', '889894', 'uploads/53b9ac4c156a1.jpg', 'uploads/thumb_53b9ac4c156a1.jpg', 'Test 10', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('12', '857271', '0', '857271', 'uploads/53c3fddead457.jpg', 'uploads/thumb_53c3fddead457.jpg', 'Testing the writeup on a food retailer.', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('13', '889432', '0', '889925', 'uploads/53bb81b0327c2.jpg', 'uploads/thumb_53bb81b0327c2.jpg', 'Dinosaurs like fruit, right?', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('14', '889244', '0', '889925', 'uploads/53c8be3986f96.jpg', 'uploads/thumb_53c8be3986f96.jpg', 'testing123', '2014-07-18 13:27:05', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('15', '857271', '0', '889894', 'uploads/53bcdbc3571ed.jpg', 'uploads/thumb_53bcdbc3571ed.jpg', 'Testing 12345', '2014-07-16 12:18:32', '1', '0', null, null, null);
INSERT INTO photo_promo VALUES ('16', '0', '0', '890381', 'uploads/53c63c552e4c5.jpg', 'uploads/thumb_53c63c552e4c5.jpg', 'A great PUB in Double BAY.', '2014-07-16 15:48:23', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('17', '857271', '0', '889925', 'uploads/53c85299705d1.jpg', 'uploads/thumb_53c85299705d1.jpg', 'tEsting the use of the upload with the OAK HOTEL photo?', '2014-07-18 05:47:55', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('18', '0', '0', '889923', 'uploads/53ca0ea5e0dbb.jpg', 'uploads/thumb_53ca0ea5e0dbb.jpg', 'A great place for lunch of a day.', '2014-07-19 13:22:32', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('19', '0', '0', '889923', 'uploads/53ca10536b272.jpg', 'uploads/thumb_53ca10536b272.jpg', 'A fantastic pub with a great bistro!', '2014-07-19 13:29:41', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('20', '0', '0', '889925', 'uploads/53cc3dccbc276.jpg', 'uploads/thumb_53cc3dccbc276.jpg', 'A fantastic place to grab lunch.', '2014-07-21 05:08:14', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('21', '0', '0', '889967', 'uploads/53cc40275cd8c.jpg', 'uploads/thumb_53cc40275cd8c.jpg', 'A great place for breakfast, lunch or dinner.', '2014-07-21 05:18:17', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('22', '857271', '0', '890770', 'uploads/53cefc6708a48.jpg', 'uploads/thumb_53cefc6708a48.jpg', '4', '2014-07-23 07:05:59', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('23', '0', '885168', '889967', 'uploads/53d5f113393dc.jpg', 'uploads/thumb_53d5f113393dc.jpg', 'Just testing.', '2014-07-28 13:43:31', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('24', '857271', '0', '2330686', 'uploads/53d9fa45a9803.jpg', 'uploads/thumb_53d9fa45a9803.jpg', 'abc', '2014-07-31 15:16:21', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('25', '0', '0', '890835', 'uploads/53da08980eb36.jpg', 'uploads/thumb_53da08980eb36.jpg', 'This is for siro3112', '2014-07-31 16:12:56', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('26', '890835', '857821', '890835', 'uploads/53da0c1492a19.jpg', 'uploads/thumb_53da0c1492a19.jpg', 'test', '2014-07-31 16:28:44', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('27', '890836', '0', '890836', 'uploads/53da1363122b4.jpg', 'uploads/thumb_53da1363122b4.jpg', 'teea', '2014-07-31 17:01:34', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('28', '857271', '857271', '890836', 'uploads/53da19217cfc5.jpg', 'uploads/thumb_53da19217cfc5.jpg', 'tesst', '2014-07-31 17:23:29', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('29', '857271', '857271', '890837', 'uploads/53df518fa0614.jpg', 'uploads/thumb_53df518fa0614.jpg', 'Harris Farm', '2014-08-05 14:39:53', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('30', '0', '0', '890837', 'uploads/53df540d97d35.jpg', 'uploads/thumb_53df540d97d35.jpg', 'Description', '2014-08-04 16:36:13', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('31', '0', '0', '890837', 'uploads/53e1c49555b22.jpg', 'uploads/thumb_53e1c49555b22.jpg', 'test', '2014-08-06 13:00:53', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('32', '0', '0', '890837', 'uploads/53e1c5814d71b.jpg', 'uploads/thumb_53e1c5814d71b.jpg', 'abc', '2014-08-06 13:04:49', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('33', '0', '0', '890837', 'uploads/53e1c65ddc55d.jpg', 'uploads/thumb_53e1c65ddc55d.jpg', '', '2014-08-06 13:08:30', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('34', '0', '0', '890837', 'uploads/53e1c8335969b.jpg', 'uploads/thumb_53e1c8335969b.jpg', '', '2014-08-06 13:16:19', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('35', '0', '0', '890837', 'uploads/53e1c88e18978.jpg', 'uploads/thumb_53e1c88e18978.jpg', '', '2014-08-06 13:17:50', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('36', '0', '0', '890837', 'uploads/53e1ca1c6a6c6.jpg', 'uploads/thumb_53e1ca1c6a6c6.jpg', '', '2014-08-06 13:24:28', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('37', '0', '0', '890837', 'uploads/53e1ca8779192.jpg', 'uploads/thumb_53e1ca8779192.jpg', '', '2014-08-06 13:26:15', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('38', '0', '0', '890837', 'uploads/53e1caebdebe1.jpg', 'uploads/thumb_53e1caebdebe1.jpg', '', '2014-08-06 13:27:56', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('39', '0', '0', '890837', 'uploads/53e1d39213b8b.jpg', 'uploads/thumb_53e1d39213b8b.jpg', '', '2014-08-06 14:04:50', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('40', '0', '0', '890837', 'uploads/53e1d3cd2eb1f.jpg', 'uploads/thumb_53e1d3cd2eb1f.jpg', '', '2014-08-06 14:05:49', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('41', '0', '0', '890837', 'uploads/53e1d46eaa4c9.jpg', 'uploads/thumb_53e1d46eaa4c9.jpg', '', '2014-08-06 14:08:30', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('42', '0', '0', '890837', 'uploads/53e1dae9b75d2.jpg', 'uploads/thumb_53e1dae9b75d2.jpg', '', '2014-08-06 14:36:10', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('43', '0', '0', '890837', 'uploads/53e1dbb729e66.jpg', 'uploads/thumb_53e1dbb729e66.jpg', '', '2014-08-06 14:39:35', '0', '0', null, null, null);
INSERT INTO photo_promo VALUES ('44', '857271', '', '890837', 'uploads/53e1f8f9442a0.jpg', 'uploads/thumb_53e1f8f9442a0.jpg', 'description! abdwabcww', '2014-08-07 13:21:31', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('45', '857271', '', '890837', 'uploads/53e1fc7d36404.jpg', 'uploads/thumb_53e1fc7d36404.jpg', '', '2014-08-06 16:59:25', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('46', '0', '', '890837', 'uploads/53e1fcffeb5b3.jpg', 'uploads/thumb_53e1fcffeb5b3.jpg', 'ádfsfsfsdfsdf', '2014-08-07 13:23:13', '0', '0', '4', '3', 'Harris W S Pty Ltd');
INSERT INTO photo_promo VALUES ('47', '0', '', '890837', 'uploads/53e1fffa83c6e.jpg', 'uploads/thumb_53e1fffa83c6e.jpg', '', '2014-08-06 17:14:18', '0', '0', '5', '7', 'Harrigan\'s Irish Pub &amp; Accommodation');
INSERT INTO photo_promo VALUES ('48', '857271', '', '890837', 'uploads/53e339c67f7a9.jpg', 'uploads/thumb_53e339c67f7a9.jpg', 'abcdw', '2014-08-07 15:34:49', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('49', '857271', '', '890837', 'uploads/53e34299980f2.jpg', 'uploads/thumb_53e34299980f2.jpg', 'aaasasdf', '2014-08-07 16:10:49', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('50', '857271', '', '890837', 'uploads/53e342dee63ed.jpg', 'uploads/thumb_53e342dee63ed.jpg', 'abaa', '2014-08-07 16:11:59', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('51', '857271', '', '890837', 'uploads/53e3469790736.jpg', 'uploads/thumb_53e3469790736.jpg', 'abaaa', '2014-08-07 16:27:51', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('52', '857271', '', '890837', 'uploads/53e346f1b4cb4.jpg', 'uploads/thumb_53e346f1b4cb4.jpg', 'abac', '2014-08-07 16:29:21', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('53', '857271', '', '890837', 'uploads/53e34746ce192.jpg', 'uploads/thumb_53e34746ce192.jpg', 'aba', '2014-08-07 16:30:46', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('54', '857271', '', '890837', 'uploads/53e347a434f0a.jpg', 'uploads/thumb_53e347a434f0a.jpg', 'aba', '2014-08-07 16:32:20', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('55', '857271', '', '890837', 'uploads/53e348fa375c1.jpg', 'uploads/thumb_53e348fa375c1.jpg', 'aba', '2014-08-07 16:38:02', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('56', '857271', '', '890837', 'uploads/53e34b9467c3a.jpg', 'uploads/thumb_53e34b9467c3a.jpg', 'bacs', '2014-08-07 16:49:08', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('57', '857271', '', '890837', 'uploads/53e34ee4168be.jpg', 'uploads/thumb_53e34ee4168be.jpg', 'aba', '2014-08-07 17:03:16', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('58', '857271', '', '890837', 'uploads/53e34f9162282.jpg', 'uploads/thumb_53e34f9162282.jpg', 'aba', '2014-08-07 17:06:09', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('59', '857271', '', '890837', 'uploads/53e34fc538e60.jpg', 'uploads/thumb_53e34fc538e60.jpg', 'aaa', '2014-08-07 17:07:01', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('60', '857271', '', '890837', 'uploads/53e351469b81f.jpg', 'uploads/thumb_53e351469b81f.jpg', 'aaa', '2014-08-07 17:13:26', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('61', '857271', '', '890837', 'uploads/53e3516eae3c2.jpg', 'uploads/thumb_53e3516eae3c2.jpg', 'aaa', '2014-08-07 17:14:06', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
INSERT INTO photo_promo VALUES ('62', '0', '', '890837', 'uploads/53e351a03ac1a.jpg', 'uploads/thumb_53e351a03ac1a.jpg', 'aaaa', '2014-08-07 17:14:56', '0', '0', '2', '1', 'aaaaa');
INSERT INTO photo_promo VALUES ('63', '857271', '', '890837', 'uploads/53e351de91424.jpg', 'uploads/thumb_53e351de91424.jpg', 'aaaaaa', '2014-08-07 17:16:51', '0', '0', '6', '6', 'aaaaa');
INSERT INTO photo_promo VALUES ('64', '857271', '', '890837', 'uploads/53e35266a7615.jpg', 'uploads/thumb_53e35266a7615.jpg', 'aaaa', '2014-08-07 17:18:14', '0', '0', '5', '6', 'Harris Farm Markets EDGECLIFF');
