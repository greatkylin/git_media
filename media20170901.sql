/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : media

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-09-01 20:17:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for zy_media_app
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app`;
CREATE TABLE `zy_media_app` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `app_id` int(10) DEFAULT NULL COMMENT '应用id',
  `android_down_url` varchar(255) DEFAULT NULL COMMENT '安卓下载地址',
  `ios_down_url` varchar(255) DEFAULT NULL COMMENT '苹果下载地址',
  `play_info` text COMMENT '玩法介绍',
  `game_tag` varchar(255) DEFAULT NULL COMMENT '游戏标签',
  `cover_imgs_url` text COMMENT '封面图片地址',
  `fine_imgs_url` text COMMENT '精美图片地址',
  `fine_imgs_thumb_url` text COMMENT '精美图片缓存地址',
  `admin_id` int(10) DEFAULT '0' COMMENT '操作人',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否发布，0否，1是',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `special_title` varchar(255) DEFAULT NULL COMMENT '专辑标题',
  `special_info` varchar(255) DEFAULT NULL COMMENT '专辑文案',
  `video_id` int(10) DEFAULT NULL COMMENT '视频ID',
  `title` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zy_media_app
-- ----------------------------
INSERT INTO `zy_media_app` VALUES ('1', '32', '', '', '&lt;p&gt;《阴阳师》手游是网易游戏在2016年自主研发的一款3D唯美和风手游，以日本家喻户晓的阴阳师IP为背景，沿用经典人设，讲述了人鬼共生的平安时代，阴阳师安倍晴明探寻自身记忆的故《阴阳师》手游是网易游戏在2016年自主研发的一款3D唯美和风手游，以日本家喻户晓的阴阳师IP为背景，沿用经典人设，讲述了人鬼共生的平安时代，阴阳师安倍晴明探寻自身记忆的故事。剧情充实细腻而不乏创新点，画风唯美空灵极具和风意境，是网易2016年的战略产品。《阴阳师》在经典的半即时《阴阳师》手游是网易游戏在2016年自主研发的一款3D唯美和风手游，以日本家喻户晓的阴阳师IP为背景，沿用经典人设，讲述了人鬼共生的平安时代，阴阳师安倍晴明探寻自身记忆的故事。剧情充实细腻而不乏创新点，画风唯美空灵极具和风意境，是网易2016年的战略产品。《阴阳师》在经典的半即时《阴阳师》手游是网易游戏在2016年自主研发的一款3D唯美和风手游，以日本家喻户晓的阴阳师IP为背景，沿用经典人设，讲述了人鬼共生的平安时代，阴阳师安倍晴明探寻自身记忆的故事。剧情充实细腻而不乏创新点，画风唯美空灵极具和风意境，是网易2016年的战略产品。《阴阳师》在经典的半即时《阴阳师》手游是网易游戏在2016年自主研发的一款3D唯美和风手游，以日本家喻户晓的阴阳师IP为背景，沿用经典人设，讲述了人鬼共生的平安时代，阴阳师安倍晴明探寻自身记忆的故事。剧情充实细腻而不乏创新点，画风唯美空灵极具和风意境，是网易2016年的战略产品。《阴阳师》在经典的半即时《阴阳师》手游是网易游戏在2016年自主研发的一款3D唯美和风手游，以日本家喻户晓的阴阳师IP为背景，沿用经典人设，讲述了人鬼共生的平安时代，阴阳师安倍晴明探寻自身记忆的故事。剧情充实细腻而不乏创新点，画风唯美空灵极具和风意境，是网易2016年的战略产品。《阴阳师》在经典的半即时事。剧情充实细腻而不乏创新点，画风唯美空灵极具和风意境，是网易2016年的战略产品。《阴阳师》在经典的半即时&lt;/p&gt;', '和风;卡牌;重度;策略', 'Uploads/images/app/cover_pic/2017-06-08/5938ae23bd14f.png', 'Uploads/images/app/fine_pic/2017-06-08/5938b5647f11f.jpeg,Uploads/images/app/fine_pic/2017-06-08/5938b591c94a9.jpeg,Uploads/images/app/fine_pic/2017-06-08/5938b591eb0d6.jpeg,Uploads/images/app/fine_pic/2017-06-08/5938b591e85aa.jpeg,Uploads/images/app/fine_pic/2017-06-08/5938b59697ba9.jpeg', 'Uploads/images/app/fine_pic/2017-06-08/thumb/5938b5647f11f.jpeg,Uploads/images/app/fine_pic/2017-06-08/thumb/5938b591c94a9.jpeg,Uploads/images/app/fine_pic/2017-06-08/thumb/5938b591eb0d6.jpeg,Uploads/images/app/fine_pic/2017-06-08/thumb/5938b591e85aa.jpeg,Uploads/images/app/fine_pic/2017-06-08/thumb/5938b59697ba9.jpeg', '52', '1', '1495617978', '花式解谜，极度烧脑', '来试试你的智商上限吧！', '8', '阴阳师-指娱游戏，游戏如此精彩', '阴阳师;网易阴阳师;阴阳师下载;阴阳师专区', '阴阳师最全攻略尽在指娱游戏，阴阳师绝美壁纸全网收罗，要玩阴阳师就来指娱游戏！你没玩过的好游戏，都在指娱。指娱，游戏如此精彩！');
INSERT INTO `zy_media_app` VALUES ('2', '8', null, null, null, null, null, null, null, '37', '0', '1495681453', null, null, null, null, null, null);

-- ----------------------------
-- Table structure for zy_media_app_guide
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app_guide`;
CREATE TABLE `zy_media_app_guide` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '应用id',
  `guide_image` varchar(255) DEFAULT '' COMMENT '游戏详情页图文攻略图片',
  `guide_link` varchar(255) DEFAULT '' COMMENT '跳转链接',
  `create_time` int(11) DEFAULT '0' COMMENT '数据生成时间',
  `type` tinyint(1) DEFAULT '0' COMMENT '用来记录是视频还是url',
  `sort` tinyint(10) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `idx_app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COMMENT='游戏详情页图文攻略';

-- ----------------------------
-- Records of zy_media_app_guide
-- ----------------------------
INSERT INTO `zy_media_app_guide` VALUES ('19', '588', 'Uploads/Images/applib/topic2017-08-30/59a6763a245f1.png', 'www.baiu.com1', '1504083773', '0', '1');
INSERT INTO `zy_media_app_guide` VALUES ('20', '588', 'Uploads/Images/applib/topic2017-08-30/59a6763b39a07.png', 'www.baiu.com2', '1504083773', '0', '2');
INSERT INTO `zy_media_app_guide` VALUES ('21', '588', 'Uploads/Images/applib/topic2017-08-30/59a6763c3cd09.png', 'www.baiu.com3', '1504083773', '0', '3');
INSERT INTO `zy_media_app_guide` VALUES ('22', '588', 'Uploads/Images/applib/topic2017-08-30/59a6763e5bd9a.png', 'www.baiu.com4', '1504083773', '0', '4');
INSERT INTO `zy_media_app_guide` VALUES ('23', '588', 'Uploads/Images/applib/icon2017-08-30/59a6763d6b37d.png', 'www.baiu.com5', '1504083773', '0', '5');
INSERT INTO `zy_media_app_guide` VALUES ('24', '588', 'Uploads/Images/applib/topic2017-08-30/59a6763f642a5.png', 'www.baiu.com6', '1504083773', '0', '6');
INSERT INTO `zy_media_app_guide` VALUES ('25', '1517', '', '', '1504083820', '0', '1');
INSERT INTO `zy_media_app_guide` VALUES ('26', '1517', '', '', '1504083820', '0', '2');
INSERT INTO `zy_media_app_guide` VALUES ('27', '1517', '', '', '1504083820', '0', '3');
INSERT INTO `zy_media_app_guide` VALUES ('28', '1517', '', '', '1504083820', '0', '4');
INSERT INTO `zy_media_app_guide` VALUES ('29', '1517', '', '', '1504083820', '0', '5');
INSERT INTO `zy_media_app_guide` VALUES ('30', '1517', '', '', '1504083820', '0', '6');
INSERT INTO `zy_media_app_guide` VALUES ('31', '1093', '', '', '1504083934', '0', '1');
INSERT INTO `zy_media_app_guide` VALUES ('32', '1093', '', '', '1504083934', '0', '2');
INSERT INTO `zy_media_app_guide` VALUES ('33', '1093', '', '', '1504083934', '0', '3');
INSERT INTO `zy_media_app_guide` VALUES ('34', '1093', '', '', '1504083934', '0', '4');
INSERT INTO `zy_media_app_guide` VALUES ('35', '1093', '', '', '1504083934', '0', '5');
INSERT INTO `zy_media_app_guide` VALUES ('36', '1093', '', '', '1504083934', '0', '6');
INSERT INTO `zy_media_app_guide` VALUES ('37', '1165', '', '', '1504084010', '0', '1');
INSERT INTO `zy_media_app_guide` VALUES ('38', '1165', '', '', '1504084010', '0', '2');
INSERT INTO `zy_media_app_guide` VALUES ('39', '1165', '', '', '1504084010', '0', '3');
INSERT INTO `zy_media_app_guide` VALUES ('40', '1165', '', '', '1504084010', '0', '4');
INSERT INTO `zy_media_app_guide` VALUES ('41', '1165', '', '', '1504084010', '0', '5');
INSERT INTO `zy_media_app_guide` VALUES ('42', '1165', '', '', '1504084010', '0', '6');

-- ----------------------------
-- Table structure for zy_media_app_lib
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app_lib`;
CREATE TABLE `zy_media_app_lib` (
  `app_id` int(11) NOT NULL COMMENT '应用id,非自增',
  `app_name` varchar(150) NOT NULL COMMENT '应用名',
  `short_name` varchar(150) NOT NULL COMMENT '应用简称',
  `supplier_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏厂商id',
  `star_rank` int(2) DEFAULT NULL COMMENT '游戏星级',
  `start_score` float(3,1) DEFAULT '0.0' COMMENT '游戏评分',
  `platform` tinyint(2) DEFAULT '0' COMMENT '应用平台 1:android 2:ios 3:H5',
  `app_type` varchar(255) DEFAULT NULL,
  `app_type1` int(11) DEFAULT NULL COMMENT '主类型',
  `app_type2` varchar(255) DEFAULT NULL COMMENT '副类型',
  `app_size` bigint(18) DEFAULT '0' COMMENT '应用大小，字节为单位',
  `version_code` int(10) DEFAULT '0' COMMENT '应用版本号',
  `version_name` varchar(255) DEFAULT NULL COMMENT '应用版本名称',
  `about` text COMMENT '应用介绍',
  `introduct` varchar(255) DEFAULT NULL COMMENT '应用简介',
  `icon` varchar(255) DEFAULT NULL COMMENT '应用图标',
  `pic_url` mediumtext COMMENT '游戏详情图，多个逗号分割',
  `is_landscape` tinyint(1) NOT NULL DEFAULT '0' COMMENT '游戏截图类型，0表示横版，1表示竖版',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作人',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `game_quality` varchar(255) DEFAULT '游戏品质' COMMENT '游戏品质',
  `game_picture` varchar(255) DEFAULT '游戏画质' COMMENT '游戏画质',
  `game_gandu` varchar(255) DEFAULT '游戏肝度' COMMENT '游戏肝度',
  `game_diff` varchar(255) DEFAULT '游戏难度' COMMENT '游戏难度',
  `game_quality_value` int(11) DEFAULT '0' COMMENT '游戏品质',
  `game_picture_value` int(11) DEFAULT '0' COMMENT '游戏画质',
  `game_gandu_value` int(11) DEFAULT '0' COMMENT '游戏肝度',
  `game_diff_value` int(11) DEFAULT '0' COMMENT '游戏难度',
  `video_link` varchar(255) DEFAULT '' COMMENT '视频ID',
  `cover_img` varchar(255) DEFAULT NULL COMMENT '封面图片地址',
  PRIMARY KEY (`app_id`),
  KEY `idx_app_id_app_name` (`app_id`,`app_name`) USING BTREE COMMENT '基于app_id与app_name的索引'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏应用表';

-- ----------------------------
-- Records of zy_media_app_lib
-- ----------------------------
INSERT INTO `zy_media_app_lib` VALUES ('588', '乱斗三国', 'LDSG', '718', null, '4.1', '1', '', '16', '1,6,26', '255994822', '0', '1.3.3', '&lt;p&gt;《乱斗三国》是一款由台风互动信息技术有限公司开发、梦城互动科技有限公司制作的大型卡牌养成类手游。游戏采用游戏引擎unity3D开发，以三国历史文化为背景，新一代布阵划线式战斗，超强的策略性和即时性，让你掌握不一样的战场主动权。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;游戏以养成为主，收集为辅，提供众多养成方式供您选择，武将获取途径更多更容易。独特武将轮回变身，一骑当千英雄模式，策略布阵、即时战斗换位，清新Q版画风，真3D视角展示，带你畅游不一样的三国历史。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '三国背景的3D大型卡牌养成类手游。', 'Uploads/Images/applib/icon2017-08-30/59a675fd8cfe8.png', 'Uploads/Images/applib/pic2017-08-30/59a676128c8e1.png,Uploads/Images/applib/pic2017-08-30/59a676129eddd.png,Uploads/Images/applib/pic2017-08-30/59a67612b5d13.png,Uploads/Images/applib/pic2017-08-30/59a676138e85b.png,Uploads/Images/applib/pic2017-08-30/59a676147330d.png', '0', null, '1504081324', '1493794258', '游戏品质', '游戏品质', '游戏肝度', '游戏难度', '0', '0', '0', '0', 'www.baidu.com', 'Uploads/Images/applib/video2017-08-30/59a676076611b.png');
INSERT INTO `zy_media_app_lib` VALUES ('1093', '魂战三国', 'HZSG', '200', null, '3.9', '1', '20,23', '1', '5,26', '142450718', '2002000', '2.2.0', '&lt;p&gt;《魂战三国》是一款激情四射的三国题材的角色扮演即时战斗类型的手游，游戏以三国历史为背景，让你走进平行另类的三国世界，各种Q萌经典三国名将强势来袭，史诗级的战役完美再现，首次登录就送福利元宝，让你全民变大神，精彩内容尽在魂战三国，赶紧来一场生死相搏的较量吧。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;2.2更新内容&lt;/p&gt;&lt;p&gt;1.七日送大礼签到&lt;/p&gt;&lt;p&gt;2.每日签到(貂蝉送福利)&lt;/p&gt;&lt;p&gt;3.聊天秘籍&lt;/p&gt;&lt;p&gt;4.出征按钮等级13级开启&lt;/p&gt;&lt;p&gt;5.跑马灯广播&lt;/p&gt;&lt;p&gt;6.黑市礼包商店&lt;/p&gt;&lt;p&gt;7.一键挂机&lt;/p&gt;&lt;p&gt;8.合服比拼&lt;/p&gt;&lt;p&gt;9.战力可以超过42亿&lt;/p&gt;&lt;p&gt;10.新每日首充&lt;/p&gt;&lt;p&gt;11.新的首次充值&lt;/p&gt;&lt;p&gt;12.充值界面调整，显示送钻石和积分&lt;/p&gt;&lt;p&gt;13.70级开放化神&lt;/p&gt;', '佣兵天下终结混战三国。', null, null, '0', null, '1504083895', '1496823708', '游戏品质', '游戏品质', '游戏肝度', '游戏难度', '0', '0', '0', '0', '', null);
INSERT INTO `zy_media_app_lib` VALUES ('1165', '热血征途', 'RXZT', '767', null, '3.8', '1', '16,26', '1', '5,26', '244657305', '1003383', '1.3.383', '&lt;p&gt;热血征途是一款充满传奇战斗元素的手机网游，独特的游戏背景，各种另类的战&lt;/p&gt;&lt;p&gt;斗场景，延续经典传奇风格，传统MMORPG核心玩法，让你这场打怪过程更加精彩，&lt;/p&gt;&lt;p&gt;来进行一场酷炫战斗吧。打怪刷宝，这些都能帮你快速升级，在这场战斗之中，&lt;/p&gt;&lt;p&gt;快速pk，让你这场升级之路越战越勇，最独特战斗路线，打造属于你的怪兽升级&lt;/p&gt;&lt;p&gt;路线吧，在游戏之中斩妖除魔，独特战斗环节，各种神奇力量，帮助你进入一场&lt;/p&gt;&lt;p&gt;奇幻之中。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '另类战斗场景，延续经典传奇风格。', null, null, '0', null, '1504083978', '1498028494', '游戏品质', '游戏品质', '游戏肝度', '游戏难度', '0', '0', '0', '0', '', null);
INSERT INTO `zy_media_app_lib` VALUES ('1517', '战舞幻想曲极', 'ZWHXQJ', '1222', null, '4.5', '1', '17,56', '1', '5,22,26,55,94', '50060575', '3', '3.3.001', '&lt;p&gt;游戏采用4人上阵，切换式战斗的模式，在游戏中玩家需要通过切换4名角色解开各种机关谜题，打败关卡中的各色敌人完成通关，随着游戏历程的演进，玩家还会逐步接触到更多性格各异的人物。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;游戏融入了连段、硬直、击飞、奥义等动作游戏打击体系，让玩家体验酣畅淋漓的打击感。在流畅、爽快的战斗体验基础上，游戏还构建了丰富的关卡探索玩法和乐趣十足的机关解密要素。不同角色可以在关中开启不同的机关，每个关卡中的玩法也千差万别。肃穆庄严暗藏伤害机关的王国城堡；遍地黄沙令人步履维艰的谜之沙城；枝蔓横生需要谨慎攀爬的静谧森林；冰天雪地令行走滑行的冰雪遗迹，还有更多妙趣横生的冒险关卡都会在《战舞幻想曲》中逐步开放。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '首款3D萌娘探宝玩法手游。', null, null, '0', null, '1504083792', '1500517057', '游戏品质', '游戏画质', '游戏肝度', '游戏难度', '0', '0', '0', '0', '', null);

-- ----------------------------
-- Table structure for zy_media_app_list
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app_list`;
CREATE TABLE `zy_media_app_list` (
  `id` int(11) NOT NULL COMMENT '自增主键',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '应用id',
  `final_hot_sort` int(11) DEFAULT '0' COMMENT '生成列表后人气排行',
  `final_new_sort` int(11) DEFAULT '0' COMMENT '生成列表后近期更新',
  `pre_hot_sort` int(11) DEFAULT '0' COMMENT '人气排行',
  `pre_new_sort` int(11) DEFAULT '0' COMMENT '近期更新',
  `edit_time` int(11) DEFAULT '0' COMMENT '修改时间',
  `sync_time` int(11) DEFAULT '0' COMMENT '数据同步时间',
  PRIMARY KEY (`id`),
  KEY `idx_app_id` (`app_id`) USING BTREE COMMENT '基于app_id的索引'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏列表，与总库关联';

-- ----------------------------
-- Records of zy_media_app_list
-- ----------------------------
INSERT INTO `zy_media_app_list` VALUES ('14', '17', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('29', '32', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('45', '49', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('46', '50', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('47', '51', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('48', '52', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('49', '53', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('50', '54', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('51', '55', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('52', '56', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('53', '57', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('54', '58', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('55', '59', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('56', '60', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('57', '61', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('58', '62', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('59', '63', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('60', '64', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('61', '65', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('62', '66', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('63', '67', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('64', '68', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('65', '69', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('66', '70', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('67', '71', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('68', '72', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('69', '73', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('70', '74', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('71', '75', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('72', '76', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('74', '78', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('75', '79', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('76', '80', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('77', '81', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('78', '82', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('79', '83', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('80', '84', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('81', '85', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('82', '86', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('83', '87', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('84', '88', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('85', '89', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('86', '90', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('87', '91', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('88', '92', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('89', '93', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('90', '94', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('91', '95', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('92', '96', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('93', '97', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('94', '98', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('95', '99', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('96', '100', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('97', '101', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('99', '103', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('100', '104', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('101', '105', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('102', '106', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('103', '107', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('104', '108', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('105', '109', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('106', '110', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('109', '113', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('110', '114', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('113', '117', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('114', '118', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('115', '119', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('116', '120', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('117', '121', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('118', '122', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('119', '123', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('120', '124', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('122', '126', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('123', '127', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('124', '128', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('125', '129', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('126', '130', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('127', '131', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('128', '132', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('130', '134', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('142', '146', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('145', '149', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('146', '150', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('147', '151', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('148', '152', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('149', '153', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('150', '154', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('151', '155', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('152', '156', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('154', '158', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('155', '159', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('156', '160', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('157', '161', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('158', '162', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('159', '163', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('160', '164', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('161', '165', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('162', '166', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('163', '167', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('164', '168', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('165', '169', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('166', '170', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('167', '171', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('168', '172', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('169', '173', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('170', '174', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('171', '175', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('175', '179', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('176', '180', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('177', '181', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('178', '182', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('179', '183', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('180', '184', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('181', '185', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('182', '186', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('183', '187', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('184', '188', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('185', '189', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('186', '190', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('187', '191', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('188', '192', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('189', '193', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('190', '194', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('191', '195', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('192', '196', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('193', '197', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('196', '200', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('197', '201', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('198', '202', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('199', '203', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('200', '204', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('201', '205', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('202', '206', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('204', '207', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('208', '221', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('209', '222', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('210', '223', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('211', '224', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('212', '225', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('213', '226', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('214', '227', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('215', '228', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('216', '229', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('217', '208', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('218', '209', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('219', '210', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('220', '211', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('221', '212', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('222', '213', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('223', '214', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('228', '231', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('229', '232', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('230', '233', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('231', '234', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('232', '235', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('233', '236', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('234', '237', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('235', '238', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('236', '239', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('237', '240', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('238', '241', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('239', '242', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('240', '243', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('241', '244', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('242', '245', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('243', '246', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('244', '247', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('245', '248', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('246', '249', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('247', '250', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('248', '251', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('249', '252', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('250', '253', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('251', '254', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('252', '255', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('254', '257', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('256', '259', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('257', '260', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('259', '361', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('260', '362', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('261', '363', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('262', '364', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('263', '365', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('264', '366', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('265', '367', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('266', '368', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('267', '369', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('268', '370', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('269', '371', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('270', '372', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('271', '373', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('272', '374', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('273', '375', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('274', '376', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('275', '377', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('276', '378', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('277', '379', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('278', '380', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('279', '381', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('280', '382', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('281', '383', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('282', '384', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('283', '385', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('284', '386', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('285', '387', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('286', '388', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('287', '389', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('288', '390', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('289', '391', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('290', '392', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('291', '393', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('292', '394', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('293', '395', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('294', '396', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('295', '397', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('296', '398', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('297', '399', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('298', '400', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('299', '401', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('300', '402', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('301', '403', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('302', '404', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('303', '405', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('304', '406', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('305', '407', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('306', '408', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('307', '409', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('308', '410', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('309', '411', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('310', '412', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('311', '413', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('312', '414', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('313', '415', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('314', '416', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('315', '417', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('316', '418', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('317', '419', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('318', '420', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('319', '421', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('320', '422', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('321', '423', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('322', '424', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('323', '425', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('324', '426', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('325', '427', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('326', '428', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('327', '429', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('328', '430', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('329', '431', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('330', '432', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('331', '433', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('332', '434', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('333', '435', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('334', '436', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('335', '437', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('336', '438', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('337', '439', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('338', '440', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('339', '441', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('340', '442', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('341', '443', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('342', '444', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('343', '445', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('344', '446', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('345', '447', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('346', '448', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('347', '449', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('348', '450', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('349', '451', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('350', '452', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('351', '453', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('352', '454', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('353', '455', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('354', '456', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('355', '457', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('356', '458', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('357', '459', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('358', '460', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('359', '461', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('360', '462', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('361', '463', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('362', '464', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('363', '465', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('364', '466', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('365', '467', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('366', '468', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('367', '469', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('368', '470', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('369', '471', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('370', '472', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('371', '473', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('372', '474', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('373', '475', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('374', '476', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('375', '477', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('376', '478', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('377', '479', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('378', '480', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('379', '481', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('380', '482', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('381', '483', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('382', '484', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('383', '485', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('384', '486', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('385', '487', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('386', '488', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('387', '489', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('388', '490', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('389', '491', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('390', '492', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('391', '493', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('392', '494', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('393', '495', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('394', '496', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('395', '497', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('396', '498', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('397', '499', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('398', '500', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('399', '501', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('400', '502', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('401', '503', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('402', '504', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('403', '505', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('404', '506', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('405', '507', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('406', '508', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('407', '509', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('408', '510', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('409', '511', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('410', '512', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('411', '513', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('412', '514', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('414', '516', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('415', '517', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('416', '518', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('417', '519', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('418', '520', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('419', '521', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('420', '522', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('421', '523', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('422', '524', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('423', '525', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('424', '526', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('425', '527', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('426', '528', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('427', '529', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('428', '530', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('429', '531', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('430', '532', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('431', '533', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('432', '534', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('433', '535', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('434', '536', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('435', '537', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('436', '538', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('437', '539', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('438', '540', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('439', '541', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('440', '542', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('441', '543', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('442', '544', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('443', '545', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('444', '546', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('445', '547', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('446', '548', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('447', '549', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('448', '550', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('449', '551', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('450', '552', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('451', '553', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('452', '554', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('453', '555', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('454', '556', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('455', '557', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('456', '558', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('457', '559', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('458', '560', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('459', '561', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('460', '562', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('461', '563', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('462', '564', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('463', '565', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('464', '566', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('465', '567', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('466', '568', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('467', '569', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('468', '570', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('469', '571', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('470', '572', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('471', '573', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('472', '574', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('473', '575', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('474', '576', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('475', '577', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('476', '578', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('477', '579', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('478', '580', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('479', '581', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('480', '582', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('481', '583', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('482', '584', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('483', '585', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('484', '586', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('485', '587', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('486', '588', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('487', '589', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('488', '590', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('489', '591', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('490', '592', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('491', '593', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('492', '594', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('493', '595', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('494', '596', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('495', '597', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('496', '598', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('497', '599', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('498', '600', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('499', '601', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('500', '602', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('501', '603', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('502', '604', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('503', '605', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('504', '606', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('505', '607', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('506', '608', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('507', '609', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('508', '610', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('509', '611', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('510', '612', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('511', '613', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('512', '614', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('513', '615', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('514', '616', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('515', '617', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('516', '618', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('517', '619', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('518', '620', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('519', '621', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('520', '622', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('521', '623', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('522', '624', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('523', '625', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('524', '626', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('525', '627', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('526', '628', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('527', '629', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('528', '630', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('529', '631', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('530', '632', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('531', '633', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('532', '634', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('533', '635', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('534', '636', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('535', '637', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('536', '638', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('537', '639', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('538', '640', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('539', '641', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('540', '642', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('541', '643', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('542', '644', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('543', '645', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('544', '646', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('545', '647', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('546', '648', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('547', '649', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('548', '650', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('549', '651', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('551', '653', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('552', '654', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('553', '655', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('554', '656', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('555', '657', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('556', '658', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('557', '659', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('558', '660', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('559', '661', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('560', '662', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('561', '663', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('562', '664', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('563', '665', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('564', '666', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('565', '667', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('566', '668', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('567', '669', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('568', '670', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('569', '671', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('570', '672', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('571', '673', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('572', '674', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('573', '675', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('574', '676', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('575', '677', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('576', '679', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('577', '680', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('578', '681', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('579', '682', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('580', '683', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('581', '684', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('582', '685', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('583', '686', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('584', '687', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('585', '688', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('586', '689', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('587', '690', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('588', '691', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('589', '692', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('590', '693', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('591', '694', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('592', '695', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('593', '696', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('594', '697', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('595', '698', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('596', '699', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('597', '700', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('598', '701', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('599', '702', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('600', '703', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('601', '704', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('602', '705', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('603', '706', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('604', '707', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('605', '708', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('606', '709', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('607', '710', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('608', '711', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('609', '712', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('610', '713', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('611', '714', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('612', '715', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('613', '716', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('614', '717', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('615', '718', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('616', '719', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('617', '720', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('618', '721', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('619', '722', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('620', '723', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('621', '724', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('622', '725', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('623', '726', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('624', '727', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('625', '728', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('626', '729', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('627', '730', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('628', '731', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('629', '732', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('630', '733', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('631', '734', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('632', '735', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('633', '736', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('634', '737', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('635', '738', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('636', '739', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('637', '740', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('638', '741', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('639', '742', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('640', '743', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('641', '744', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('642', '745', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('643', '746', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('644', '747', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('645', '748', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('646', '749', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('647', '750', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('648', '751', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('649', '752', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('650', '753', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('651', '756', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('652', '758', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('653', '759', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('654', '760', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('655', '761', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('656', '762', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('657', '763', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('658', '261', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('659', '262', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('660', '263', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('661', '264', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('662', '265', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('663', '266', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('664', '267', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('665', '268', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('666', '269', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('668', '271', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('679', '765', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('680', '766', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('681', '767', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('682', '768', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('683', '769', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('684', '770', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('685', '771', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('686', '772', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('687', '773', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('688', '774', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('689', '775', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('690', '776', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('691', '777', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('692', '778', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('693', '779', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('694', '780', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('695', '781', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('696', '782', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('697', '783', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('698', '784', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('699', '785', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('700', '786', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('701', '787', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('702', '788', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('703', '789', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('704', '790', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('705', '791', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('706', '792', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('707', '793', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('708', '794', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('709', '795', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('710', '796', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('711', '797', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('712', '798', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('713', '799', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('714', '800', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('715', '801', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('716', '802', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('717', '803', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('718', '804', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('719', '805', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('720', '806', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('721', '807', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('722', '808', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('723', '809', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('724', '810', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('725', '811', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('726', '812', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('727', '813', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('728', '814', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('729', '815', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('730', '816', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('731', '817', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('732', '818', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('733', '819', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('734', '820', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('735', '821', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('736', '822', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('737', '823', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('738', '824', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('739', '825', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('740', '826', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('741', '827', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('742', '828', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('743', '829', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('744', '830', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('745', '831', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('746', '832', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('747', '833', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('748', '834', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('749', '835', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('750', '836', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('751', '837', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('752', '838', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('753', '839', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('754', '840', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('755', '841', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('756', '842', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('757', '843', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('758', '844', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('759', '845', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('760', '846', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('761', '847', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('762', '848', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('763', '849', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('764', '850', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('765', '851', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('766', '852', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('767', '853', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('768', '854', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('769', '855', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('770', '856', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('771', '857', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('772', '858', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('773', '859', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('774', '860', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('775', '861', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('776', '862', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('777', '863', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('778', '864', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('779', '865', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('780', '866', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('781', '867', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('782', '868', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('783', '869', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('784', '870', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('785', '871', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('786', '872', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('787', '873', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('788', '874', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('789', '875', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('790', '876', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('791', '877', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('792', '878', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('793', '879', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('794', '880', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('795', '881', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('796', '882', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('797', '883', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('798', '884', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('799', '885', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('800', '886', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('801', '887', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('802', '888', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('803', '889', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('804', '890', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('805', '891', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('806', '892', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('807', '893', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('808', '894', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('809', '895', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('810', '896', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('811', '897', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('812', '898', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('813', '899', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('814', '900', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('815', '901', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('817', '903', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('818', '904', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('819', '905', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('820', '906', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('821', '907', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('822', '908', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('823', '909', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('824', '910', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('825', '911', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('826', '912', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('827', '913', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('828', '914', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('829', '915', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('830', '916', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('831', '917', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('832', '918', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('833', '919', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('834', '920', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('835', '921', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('836', '922', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('837', '923', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('838', '924', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('839', '925', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('840', '926', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('841', '927', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('842', '928', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('843', '929', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('844', '930', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('845', '931', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('846', '932', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('847', '933', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('848', '934', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('849', '935', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('850', '936', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('851', '937', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('852', '938', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('853', '939', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('854', '940', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('855', '941', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('856', '942', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('857', '943', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('858', '944', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('859', '945', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('860', '946', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('861', '947', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('862', '948', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('863', '949', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('864', '950', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('865', '951', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('866', '952', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('867', '953', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('868', '954', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('869', '955', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('870', '956', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('872', '958', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('873', '959', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('874', '960', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('875', '961', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('876', '962', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('877', '963', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('878', '964', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('879', '965', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('880', '966', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('881', '967', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('882', '968', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('883', '969', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('884', '970', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('885', '971', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('886', '972', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('887', '973', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('888', '974', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('889', '975', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('890', '976', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('891', '977', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('892', '978', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('893', '979', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('894', '980', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('895', '981', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('896', '982', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('897', '983', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('898', '984', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('899', '985', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('900', '986', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('901', '987', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('903', '989', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('904', '990', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('905', '991', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('906', '992', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('907', '993', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('908', '994', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('909', '995', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('910', '996', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('911', '997', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('912', '998', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('913', '999', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('914', '1000', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('915', '1001', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('916', '1002', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('917', '1003', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('918', '1004', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('919', '1005', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('920', '1006', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('921', '764', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('922', '1007', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('923', '1008', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('924', '1009', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('925', '1010', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('926', '1011', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('927', '1012', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('928', '1013', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('929', '1014', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('930', '1015', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('931', '1016', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('932', '1017', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('933', '1018', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('934', '1019', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('935', '1020', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('936', '1021', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('937', '1022', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('938', '1023', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('939', '1024', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('940', '1025', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('941', '1026', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('942', '1027', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('943', '1028', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('944', '1029', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('945', '1030', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('946', '1031', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('947', '1032', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('948', '1033', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('949', '1034', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('950', '1035', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('951', '1036', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('952', '1037', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('953', '1038', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('954', '1039', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('955', '1040', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('956', '1041', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('957', '1042', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('958', '1043', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('959', '1044', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('960', '1045', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('961', '1046', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('962', '1047', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('963', '1048', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('964', '1049', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('965', '1050', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('966', '1051', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('968', '1053', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('969', '1054', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('970', '1055', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('971', '1056', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('972', '1057', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('973', '1058', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('974', '1059', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('975', '1060', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('976', '1061', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('977', '1062', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('978', '1063', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('979', '1064', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('980', '1065', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('981', '1066', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('982', '1067', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('983', '1068', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('984', '1069', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('985', '1070', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('986', '1071', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('987', '1072', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('989', '755', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('991', '1073', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('992', '1074', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('993', '1075', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('994', '1076', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('995', '1077', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('996', '1078', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('997', '1079', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('998', '1080', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('999', '1081', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1000', '1082', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1002', '1084', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1003', '1085', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1004', '1086', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1005', '1087', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1006', '1088', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1007', '1089', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1008', '1090', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1009', '1091', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1010', '1092', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1011', '1093', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1012', '1094', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1013', '1095', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1014', '1096', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1015', '1097', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1016', '1098', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1017', '1099', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1018', '1100', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1019', '1101', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1020', '1102', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1021', '1103', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1022', '1104', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1023', '1105', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1024', '1106', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1026', '1108', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1027', '1109', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1028', '1110', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1029', '1111', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1030', '1112', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1031', '1113', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1032', '1114', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1033', '1115', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1034', '1116', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1035', '1117', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1036', '1118', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1037', '1119', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1038', '1120', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1039', '1121', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1040', '1122', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1041', '1123', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1042', '1124', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1044', '1126', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1045', '1127', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1046', '1128', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1047', '1129', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1048', '1130', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1049', '1131', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1050', '1132', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1051', '1133', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1052', '1134', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1053', '1135', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1054', '1136', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1056', '1138', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1057', '1139', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1058', '1140', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1059', '1141', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1060', '1142', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1061', '1143', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1062', '1144', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1063', '1145', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1064', '1146', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1065', '1147', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1066', '1148', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1067', '1149', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1068', '1150', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1069', '1151', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1070', '1152', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1071', '1153', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1072', '1154', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1073', '1155', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1074', '1156', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1075', '1157', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1076', '1158', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1077', '1159', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1078', '1160', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1079', '1161', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1080', '1162', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1081', '1163', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1082', '1164', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1083', '1165', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1084', '1166', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1085', '1167', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1086', '1168', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1087', '1169', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1088', '1170', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1089', '1171', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1090', '1172', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1091', '1173', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1092', '1174', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1093', '1175', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1094', '1176', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1095', '1177', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1096', '1178', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1098', '1180', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1099', '1181', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1100', '1182', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1101', '1183', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1102', '1184', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1103', '1185', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1104', '1186', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1105', '1187', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1106', '1188', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1107', '1189', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1108', '1190', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1109', '1191', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1110', '1192', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1111', '1193', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1112', '1194', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1113', '1195', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1114', '1196', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1115', '1197', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1116', '1198', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1117', '1199', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1118', '1200', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1119', '1201', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1120', '1202', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1121', '1203', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1122', '1204', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1123', '1205', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1124', '1206', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1125', '1207', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1126', '1208', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1127', '1209', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1128', '1210', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1129', '1211', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1130', '1212', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1131', '1213', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1132', '1214', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1133', '1215', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1134', '1216', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1135', '1217', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1136', '1218', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1137', '1219', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1138', '1220', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1139', '1221', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1141', '1223', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1142', '1224', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1143', '1225', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1144', '1226', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1145', '1227', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1146', '1228', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1147', '1229', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1148', '1230', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1149', '1231', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1150', '1232', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1151', '1233', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1152', '1234', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1153', '1235', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1154', '1236', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1155', '1237', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1156', '1238', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1157', '1239', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1158', '1240', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1159', '1241', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1160', '1242', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1161', '1243', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1162', '1244', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1163', '1245', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1164', '1246', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1165', '1247', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1166', '1248', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1167', '1249', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1168', '1250', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1169', '1251', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1170', '1252', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1171', '1253', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1172', '1254', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1174', '1256', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1175', '1257', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1176', '1258', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1177', '1259', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1178', '1260', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1179', '1261', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1180', '1262', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1181', '1263', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1182', '1264', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1183', '1265', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1184', '1266', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1185', '1267', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1186', '1268', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1187', '1269', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1188', '1270', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1189', '1271', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1190', '1272', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1191', '1273', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1192', '1274', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1193', '1275', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1194', '1276', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1195', '1277', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1196', '1278', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1197', '1279', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1198', '1280', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1199', '1281', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1200', '1282', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1201', '1283', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1202', '1284', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1203', '1285', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1204', '1286', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1205', '1287', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1206', '1288', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1207', '1289', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1208', '1290', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1209', '1291', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1210', '1292', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1211', '1293', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1212', '1294', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1213', '1295', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1214', '1296', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1215', '1297', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1216', '1298', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1217', '1299', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1218', '1300', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1219', '1301', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1220', '1302', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1221', '1303', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1222', '1304', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1223', '1305', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1224', '1306', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1225', '1307', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1226', '1308', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1227', '1309', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1228', '1310', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1229', '1311', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1230', '1312', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1231', '1313', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1232', '1314', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1233', '1315', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1234', '1316', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1235', '1317', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1236', '1318', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1237', '1319', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1238', '1320', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1239', '1321', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1240', '1322', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1241', '1323', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1242', '1324', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1243', '1325', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1244', '1326', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1245', '1327', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1247', '1329', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1248', '1330', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1249', '1331', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1250', '1332', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1251', '1333', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1252', '1334', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1253', '1335', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1254', '1336', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1255', '1337', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1256', '1338', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1257', '1339', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1259', '1341', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1260', '1342', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1261', '1343', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1262', '1344', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1263', '1345', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1264', '1346', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1265', '1347', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1266', '1348', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1267', '1349', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1268', '1350', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1269', '1351', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1270', '1352', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1271', '1353', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1272', '1354', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1273', '1355', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1274', '1356', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1275', '1357', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1276', '1358', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1277', '1359', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1278', '1360', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1279', '1361', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1280', '1362', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1281', '1363', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1282', '1364', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1283', '1365', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1284', '1366', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1285', '1367', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1286', '1368', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1287', '1369', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1288', '1370', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1289', '1371', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1290', '1372', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1291', '1373', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1292', '1374', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1293', '1375', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1294', '1376', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1295', '1377', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1296', '1378', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1297', '1379', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1298', '1380', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1299', '1381', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1302', '1384', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1303', '1385', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1304', '1386', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1305', '1387', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1306', '1388', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1307', '1389', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1308', '1390', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1309', '1391', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1310', '1392', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1312', '1394', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1313', '1395', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1314', '1396', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1315', '1397', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1316', '1398', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1318', '1400', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1321', '1403', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1322', '1404', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1323', '1405', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1324', '1406', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1325', '1407', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1326', '1408', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1327', '1409', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1328', '1410', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1329', '1411', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1330', '1412', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1331', '1413', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1332', '1414', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1333', '1415', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1334', '1416', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1336', '1418', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1337', '1419', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1340', '1422', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1341', '1423', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1342', '1424', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1343', '1425', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1344', '1426', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1345', '1427', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1346', '1428', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1347', '1429', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1349', '1431', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1350', '1432', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1351', '1433', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1352', '1434', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1353', '1435', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1354', '1436', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1355', '1437', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1356', '1438', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1357', '1439', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1358', '1440', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1360', '1442', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1361', '1443', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1362', '1444', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1364', '1446', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1365', '1447', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1366', '1448', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1367', '1449', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1368', '1450', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1369', '1451', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1370', '1452', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1372', '1454', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1373', '1455', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1374', '1456', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1375', '1457', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1376', '1458', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1377', '1459', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1378', '1460', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1379', '1461', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1380', '1462', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1381', '1463', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1383', '1465', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1384', '1466', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1385', '1467', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1386', '1468', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1387', '1469', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1388', '1470', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1389', '1471', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1390', '1472', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1391', '1473', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1392', '1474', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1393', '1475', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1394', '1476', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1396', '1478', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1398', '1480', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1399', '1481', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1400', '1482', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1401', '1483', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1402', '1484', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1403', '1485', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1404', '1486', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1405', '1487', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1406', '1488', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1407', '1489', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1408', '1490', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1409', '1491', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1410', '1492', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1411', '1493', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1412', '1494', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1414', '1496', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1415', '1497', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1416', '1498', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1417', '1499', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1418', '1500', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1419', '1501', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1420', '1502', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1421', '1503', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1422', '1504', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1423', '1505', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1424', '1506', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1425', '1507', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1426', '1508', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1427', '1509', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1428', '1510', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1429', '1511', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1430', '1512', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1431', '1513', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1432', '1514', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1433', '1515', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1434', '1516', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1435', '1517', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1436', '1518', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1437', '1519', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1438', '1520', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1439', '1521', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1440', '1522', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1441', '1523', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1442', '1524', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1443', '1525', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1444', '1526', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1446', '1528', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1447', '1529', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1448', '1530', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1449', '1531', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1450', '1532', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1451', '1533', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1452', '1534', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1453', '1535', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1454', '1536', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1455', '1537', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1456', '1538', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1457', '1539', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1459', '1541', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1460', '1542', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1461', '1543', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1462', '1544', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1463', '1545', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1464', '1546', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1465', '1547', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1466', '1548', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1467', '1549', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1468', '1550', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1469', '1551', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1470', '1552', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1471', '1553', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1472', '1554', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1473', '1555', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1474', '1556', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1475', '1557', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1476', '1558', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1477', '1559', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1478', '1560', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1479', '1561', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1480', '1562', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1481', '1563', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1482', '1564', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1483', '1565', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1484', '1566', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1485', '1567', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1486', '1568', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1487', '1569', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1488', '1570', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1489', '1571', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1490', '1572', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1491', '1573', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1492', '1574', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1493', '1575', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1494', '1576', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1495', '1577', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1496', '1578', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1497', '1579', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1498', '1580', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1499', '1581', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1500', '1582', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1501', '1583', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1502', '1584', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1503', '1585', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1504', '1586', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1505', '1587', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1506', '1588', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1507', '1589', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1508', '1590', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1509', '1591', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1510', '1592', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1511', '1593', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1512', '1594', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1513', '1595', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1514', '1596', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1515', '1597', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1516', '1598', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1517', '1599', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1518', '1600', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1519', '1601', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1520', '1602', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1521', '1603', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1522', '1604', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1523', '1605', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1524', '1606', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1525', '1607', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1526', '1608', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1527', '1609', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1528', '1610', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1529', '1611', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1530', '1612', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1531', '1613', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1532', '1614', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1533', '1615', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1534', '1616', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1535', '1617', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1536', '1618', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1537', '1619', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1538', '1620', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1539', '1621', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1540', '1622', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1541', '1623', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1542', '1624', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1543', '1625', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1544', '1626', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1545', '1627', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1546', '1628', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1547', '1629', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1548', '1630', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1550', '1632', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1551', '1633', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1552', '1634', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1553', '1635', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1554', '1636', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1555', '1637', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1556', '1638', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1557', '1639', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1558', '1640', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1559', '1641', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1560', '1642', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1561', '1643', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1562', '1644', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1563', '1645', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1564', '1646', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1565', '1647', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1566', '1648', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1567', '1649', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1568', '1650', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1569', '1651', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1570', '1652', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1571', '1653', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1572', '1654', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1573', '1655', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1574', '1656', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1575', '1657', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1576', '1658', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1577', '1659', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1578', '1660', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1579', '1661', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1580', '1662', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1581', '1663', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1582', '1664', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1583', '1665', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1584', '1666', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1585', '1667', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1586', '1668', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1587', '1669', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1588', '1670', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1589', '1671', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1590', '1672', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1591', '1673', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1592', '1674', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1593', '1675', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1594', '1676', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1595', '1677', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1596', '1678', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1597', '1679', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1598', '1680', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1599', '1681', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1600', '1682', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1601', '1683', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1602', '1684', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1603', '1685', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1604', '1686', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1605', '1687', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1606', '1688', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1607', '1689', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1608', '1690', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1609', '1691', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1610', '1692', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1611', '1693', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1612', '1694', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1613', '1695', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1614', '1696', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1615', '1697', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1616', '1698', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1617', '1699', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1618', '1700', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1619', '1701', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1620', '1702', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1621', '1703', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1622', '1704', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1623', '1705', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1624', '1706', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1625', '1707', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1626', '1708', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1627', '1709', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1628', '1710', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1629', '1711', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1630', '1712', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1631', '1713', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1632', '1714', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1633', '1715', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1634', '1716', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1635', '1717', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1636', '1718', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1637', '1719', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1638', '1720', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1639', '1721', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1640', '1722', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1641', '1723', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1642', '1724', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1643', '1725', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1644', '1726', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1645', '1727', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1646', '1728', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1647', '1729', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1648', '1730', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1649', '1731', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1650', '1732', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1651', '1733', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1652', '1734', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1653', '1735', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1654', '1736', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1655', '1737', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1656', '1738', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1657', '1739', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1658', '1740', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1659', '1741', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1660', '1742', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1661', '1743', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1662', '1744', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1663', '1745', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1664', '1746', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1665', '1747', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1666', '1748', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1667', '1749', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1668', '1750', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1669', '1751', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1670', '1752', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1671', '1753', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1672', '1754', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1673', '1755', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1674', '1756', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1675', '1757', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1676', '1758', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1677', '1759', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1678', '1760', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1679', '1761', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1680', '1762', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1681', '1763', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1682', '1764', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1683', '1765', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1684', '1766', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1685', '1767', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1686', '1768', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1687', '1769', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1688', '1770', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1689', '1771', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1690', '1772', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1691', '1773', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1692', '1774', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1693', '1775', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1694', '1776', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1695', '1777', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1696', '1778', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1697', '1779', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1698', '1780', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1699', '1781', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1700', '1782', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1701', '1783', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1702', '1784', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1703', '1785', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1704', '1786', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1705', '1787', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1706', '1788', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1707', '1789', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1708', '1790', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1709', '1791', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1710', '1792', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1711', '1793', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1712', '1794', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1713', '1795', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1714', '1796', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1715', '1797', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1716', '1798', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1717', '1799', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1718', '1800', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1719', '1801', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1720', '1802', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1721', '1803', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1722', '1804', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1723', '1805', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1724', '1806', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1725', '1807', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1726', '1808', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1727', '1809', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1728', '1810', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1729', '1811', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1730', '1812', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1731', '1813', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1732', '1814', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1733', '1815', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1734', '1816', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1735', '1817', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1736', '1818', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1737', '1819', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1738', '1820', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1739', '1821', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1740', '1822', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1741', '1823', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1742', '1824', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1743', '1825', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1744', '1826', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1745', '1827', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1746', '1828', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1747', '1829', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1748', '1830', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1749', '1831', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1750', '1832', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1751', '1833', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1752', '1834', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1753', '1835', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1754', '1836', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1755', '1837', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1756', '1838', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1757', '1839', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1758', '1840', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1760', '1842', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1761', '1843', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1762', '1844', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1763', '1845', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1764', '1846', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1765', '1847', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1766', '1848', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1767', '1849', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1768', '1850', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1769', '1851', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1770', '1852', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1771', '1853', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1772', '1854', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1773', '1855', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1774', '1856', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1775', '1857', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1776', '1858', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1777', '1859', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1778', '1860', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1779', '1861', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1780', '1862', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1781', '1863', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1782', '1864', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1783', '1865', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1784', '1866', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1785', '1867', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1786', '1868', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1787', '1869', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1788', '1870', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1789', '1871', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1790', '1872', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1791', '1873', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1792', '1874', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1793', '1875', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1794', '1876', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1795', '1877', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1796', '1878', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1797', '1879', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1798', '1880', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1799', '1881', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1800', '1882', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1801', '1883', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1802', '1884', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1803', '1885', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1804', '1886', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1805', '1887', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1806', '1888', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1807', '1889', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1808', '1890', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1809', '1891', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1810', '1892', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1811', '1893', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1812', '1894', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1813', '1895', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1814', '1896', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1815', '1897', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1816', '1898', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1817', '1899', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1818', '1900', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1819', '1901', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1820', '1902', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1821', '1903', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1822', '1904', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1823', '1905', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1824', '1906', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1825', '1907', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1826', '1908', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1827', '1909', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1828', '1910', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1829', '1911', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1830', '1912', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1831', '1913', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1832', '1914', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1833', '1915', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1834', '1916', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1835', '1917', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1836', '1918', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1837', '1919', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1838', '1920', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1839', '1921', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1840', '1922', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1841', '1923', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1842', '1924', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1843', '1925', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1844', '1926', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1845', '1927', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1846', '1928', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1847', '1929', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1848', '1930', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1849', '1931', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1850', '1932', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1851', '1933', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1852', '1934', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1853', '1935', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1854', '1936', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1855', '1937', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1856', '1938', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1857', '1939', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1858', '1940', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1859', '1941', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1860', '1942', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1861', '1943', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1862', '1944', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1863', '1945', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1864', '1946', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1865', '1947', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1866', '1948', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1867', '1949', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1868', '1950', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1869', '1951', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1870', '1952', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1871', '1953', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1872', '1954', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1873', '1955', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1874', '1956', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1875', '1957', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1876', '1958', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1877', '1959', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1878', '1960', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1880', '1962', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1881', '1963', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1882', '1964', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1883', '1965', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1884', '1966', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1885', '1967', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1886', '1968', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1887', '1969', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1888', '1970', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1889', '1971', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1890', '1972', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1891', '1973', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1892', '1974', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1893', '1975', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1894', '1976', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1896', '1978', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1897', '1979', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1898', '1980', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1899', '1981', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1900', '1982', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1901', '1983', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1902', '1984', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1904', '1986', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1905', '1987', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1906', '1988', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1907', '1989', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1908', '1990', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1909', '1991', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1910', '1992', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1911', '1993', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1912', '1994', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1913', '1995', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1914', '1996', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1915', '1997', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1916', '1998', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1917', '1999', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1918', '2000', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1919', '2001', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1920', '2002', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1921', '2003', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1922', '2004', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1923', '2005', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1924', '2006', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1925', '2007', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1926', '2008', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1927', '2009', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1928', '2010', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1929', '2011', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1930', '2012', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1931', '2013', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1932', '2014', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1933', '2015', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1934', '2016', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1935', '2017', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1936', '2018', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1937', '2019', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1938', '2020', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1939', '2021', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1940', '2022', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1941', '2023', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1942', '2024', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1943', '2025', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1944', '2026', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1945', '2027', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1946', '2028', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1947', '2029', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1948', '2030', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1949', '2031', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1950', '2032', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1951', '2033', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1952', '2034', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1953', '2035', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1954', '2036', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1955', '2037', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1956', '2038', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1957', '2039', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1958', '2040', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1959', '2041', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1960', '2042', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1961', '2043', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1962', '2044', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1963', '2045', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1964', '2046', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1965', '2047', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1966', '2048', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1967', '2049', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1968', '2050', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1969', '2051', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1970', '2052', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1971', '2053', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1972', '2054', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1973', '2055', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1974', '2056', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1975', '2057', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1976', '2058', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1977', '2059', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1978', '2060', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1979', '2061', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1980', '2062', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1981', '2063', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1982', '2064', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1983', '2065', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1984', '2066', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1985', '2067', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1986', '2068', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1987', '2069', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1988', '2070', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1989', '2071', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1990', '2072', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1991', '2073', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1992', '2074', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1993', '2075', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1994', '2076', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1995', '2077', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1996', '2078', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1997', '2079', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1998', '2080', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('1999', '2081', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2000', '2082', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2001', '2083', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2002', '2084', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2003', '2085', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2004', '2086', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2005', '2087', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2006', '2088', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2007', '2089', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2008', '2090', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2009', '2091', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2010', '2092', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2011', '2093', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2012', '2094', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2013', '2095', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2014', '2096', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2015', '2097', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2016', '2098', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2017', '2099', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2018', '2100', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2019', '2101', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2020', '2102', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2021', '2103', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2022', '2104', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2023', '2105', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2024', '2106', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2025', '2107', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2026', '2108', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2027', '2109', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2028', '2110', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2029', '2111', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2030', '2112', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2031', '2113', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2032', '2114', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2033', '2115', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2034', '2116', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2035', '2117', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2036', '2118', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2037', '2119', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2038', '2120', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2039', '2121', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2040', '2122', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2041', '2123', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2042', '2124', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2043', '2125', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2044', '2126', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2045', '2127', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2046', '2128', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2047', '2129', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2048', '2130', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2049', '2131', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2050', '2132', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2051', '2133', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2052', '2134', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2053', '2135', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2054', '2136', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2055', '2137', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2056', '2138', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2057', '2139', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2058', '2140', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2059', '2141', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2060', '2142', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2061', '2143', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2062', '2144', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2063', '2145', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2064', '2146', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2065', '2147', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2066', '2148', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2067', '2149', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2068', '2150', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2069', '2151', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2070', '2152', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2071', '2153', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2072', '2154', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2073', '2155', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2074', '2156', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2075', '2157', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2076', '2158', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2077', '2159', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2078', '2160', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2079', '2161', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2080', '2162', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2081', '2163', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2082', '2164', '0', '0', '0', '0', '0', '1503891747');
INSERT INTO `zy_media_app_list` VALUES ('2083', '2165', '0', '0', '0', '0', '0', '1503891747');

-- ----------------------------
-- Table structure for zy_media_app_ranking
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app_ranking`;
CREATE TABLE `zy_media_app_ranking` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `app_id` int(11) DEFAULT NULL COMMENT '应用id',
  `ranking_type` tinyint(1) DEFAULT NULL COMMENT '榜单类型,0表示下载榜单，1表示畅销榜，2表示新游榜，3期待榜',
  `final_sort` int(11) DEFAULT '0' COMMENT '最终排序',
  `pre_sort` int(11) DEFAULT '0' COMMENT '预排序',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作人',
  `extend` varchar(100) DEFAULT NULL COMMENT '扩展',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `data_source` tinyint(1) DEFAULT NULL COMMENT '榜单数据来源，1周榜，2月榜，3总榜',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zy_media_app_ranking
-- ----------------------------

-- ----------------------------
-- Table structure for zy_media_app_topic
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app_topic`;
CREATE TABLE `zy_media_app_topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '专题id',
  `topic_name` varchar(150) NOT NULL COMMENT '专题名称',
  `time_range_end` int(11) NOT NULL COMMENT '专题时间段：结束',
  `time_range_start` int(11) NOT NULL COMMENT '专题时间段开始',
  `topic_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '专题展示方式，1模板，2编辑器，3 H5',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作人',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `publish_time` int(11) DEFAULT '0' COMMENT '专题发布时间',
  `is_publish` tinyint(1) DEFAULT '0' COMMENT '是否发布专题 1是，0否',
  `is_delete` tinyint(1) DEFAULT '0' COMMENT '是否删除 1是，0否',
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题名称表';

-- ----------------------------
-- Records of zy_media_app_topic
-- ----------------------------

-- ----------------------------
-- Table structure for zy_media_app_topic_content
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_app_topic_content`;
CREATE TABLE `zy_media_app_topic_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '专题内容表id',
  `topic_id` int(11) NOT NULL COMMENT '专题id',
  `topic_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '专题类型1模板，2编辑器，3H5',
  `cover_image_path` varchar(255) DEFAULT NULL COMMENT '封面图片路径',
  `background_image_path` text COMMENT '抬头图，切成多张或一张，保存路径',
  `introduce` varchar(255) DEFAULT NULL COMMENT '简介',
  `content` text COMMENT '专题具体内容，模板：序列话后的数据，编辑器，3 H5链接',
  `admin_id` int(11) DEFAULT NULL COMMENT '操作人',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题内容表';

-- ----------------------------
-- Records of zy_media_app_topic_content
-- ----------------------------

-- ----------------------------
-- Table structure for zy_media_archives
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_archives`;
CREATE TABLE `zy_media_archives` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章自增id',
  `article_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章id,管理文章库',
  `pre_sort_rank` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章预排序',
  `final_sort_rank` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章最终排序',
  `page_view` bigint(15) unsigned DEFAULT '0' COMMENT '阅读量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯中心文章表';

-- ----------------------------
-- Records of zy_media_archives
-- ----------------------------

-- ----------------------------
-- Table structure for zy_media_arctype
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_arctype`;
CREATE TABLE `zy_media_arctype` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '表自增id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '游戏id 游戏专题所属游戏，新闻中心默认为0',
  `cat_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id，0表示针对游戏专题做seo',
  `sort_rank` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序id,越小越靠前',
  `column_name` varchar(30) DEFAULT '' COMMENT '新栏目名称',
  `seo_description` varchar(150) DEFAULT '' COMMENT '高级设置里设置seo描述',
  `seo_keywords` varchar(80) DEFAULT '' COMMENT 'seo关键字',
  `seo_title` varchar(120) DEFAULT '' COMMENT 'seo标题',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `idx_catid_app_id` (`cat_id`,`app_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资讯栏目类型表';

-- ----------------------------
-- Records of zy_media_arctype
-- ----------------------------

-- ----------------------------
-- Table structure for zy_media_game_special
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_game_special`;
CREATE TABLE `zy_media_game_special` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `ma_id` int(10) DEFAULT '0' COMMENT '专辑ID',
  `app_id` int(10) DEFAULT NULL COMMENT '应用id',
  `game_info` varchar(255) DEFAULT NULL COMMENT '游戏文案',
  `admin_id` int(10) DEFAULT '0' COMMENT '操作人',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zy_media_game_special
-- ----------------------------
INSERT INTO `zy_media_game_special` VALUES ('1', '1', '4', '合金弹头OL》[1-2]  是一款2D横版射击闯关类手游。', '58', '1495714544');
INSERT INTO `zy_media_game_special` VALUES ('2', '1', '8', '《迷城物语》[1-2]  是2016最火爆的日式RPG社交手游，游戏由乐道互', '37', '1495791254');
INSERT INTO `zy_media_game_special` VALUES ('3', '1', '32', '这个游戏没什么，就是看谁RMB多！', '37', '1495791463');
INSERT INTO `zy_media_game_special` VALUES ('4', '1', '717', '没有一定的操作，这个游戏还是不要玩了。', '37', '1495791463');
INSERT INTO `zy_media_game_special` VALUES ('5', '1', '856', '唯美中国风的游戏，非常不错，值得一玩！', '37', '1495791463');
INSERT INTO `zy_media_game_special` VALUES ('6', '1', '859', '我最喜欢看到的，就是车掉到河里去！', '37', '1495791463');

-- ----------------------------
-- Table structure for zy_media_gift_banner
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_gift_banner`;
CREATE TABLE `zy_media_gift_banner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `app_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '游戏id',
  `banner_path` varchar(255) DEFAULT NULL COMMENT '礼包详情页图片banner',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '编辑时间',
  PRIMARY KEY (`id`),
  KEY `idx_app_id` (`app_id`) USING BTREE COMMENT '基于app_id字段的索引'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='游戏礼包详情页banner表';

-- ----------------------------
-- Records of zy_media_gift_banner
-- ----------------------------

-- ----------------------------
-- Table structure for zy_media_gift_opt_record
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_gift_opt_record`;
CREATE TABLE `zy_media_gift_opt_record` (
  `apply_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `gift_id` int(11) NOT NULL COMMENT '礼包库id',
  `opt_count` int(11) NOT NULL DEFAULT '0' COMMENT '操作改变的礼包码数量',
  `opt_type` int(11) NOT NULL DEFAULT '0' COMMENT '操作类型0，申请礼包，1删除礼包',
  `admin_id` smallint(6) DEFAULT '0' COMMENT '操作人',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `remark` varchar(255) DEFAULT NULL COMMENT '备注信息',
  PRIMARY KEY (`apply_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='媒体站申请礼包的记录';

-- ----------------------------
-- Records of zy_media_gift_opt_record
-- ----------------------------
INSERT INTO `zy_media_gift_opt_record` VALUES ('1', '116', '0', '0', '52', '1504081626', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('2', '116', '0', '0', '52', '1504082414', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('3', '116', '0', '0', '52', '1504082429', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('4', '116', '0', '0', '52', '1504083773', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('5', '133', '0', '0', '52', '1504083820', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('6', '132', '0', '0', '52', '1504083820', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('7', '134', '0', '0', '52', '1504083820', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('8', '127', '0', '0', '52', '1504083934', '设置礼包上限数量为150');
INSERT INTO `zy_media_gift_opt_record` VALUES ('9', '125', '0', '0', '52', '1504083934', '设置礼包上限数量为120');
INSERT INTO `zy_media_gift_opt_record` VALUES ('10', '128', '0', '0', '52', '1504083934', '设置礼包上限数量为160');
INSERT INTO `zy_media_gift_opt_record` VALUES ('11', '129', '0', '0', '52', '1504083934', '设置礼包上限数量为110');
INSERT INTO `zy_media_gift_opt_record` VALUES ('12', '126', '0', '0', '52', '1504083934', '设置礼包上限数量为500');
INSERT INTO `zy_media_gift_opt_record` VALUES ('13', '123', '0', '0', '52', '1504084010', '设置礼包上限数量为60');
INSERT INTO `zy_media_gift_opt_record` VALUES ('14', '124', '0', '0', '52', '1504084010', '设置礼包上限数量为50');
INSERT INTO `zy_media_gift_opt_record` VALUES ('15', '121', '0', '0', '52', '1504084010', '设置礼包上限数量为30');
INSERT INTO `zy_media_gift_opt_record` VALUES ('16', '122', '0', '0', '52', '1504084010', '设置礼包上限数量为20');
INSERT INTO `zy_media_gift_opt_record` VALUES ('17', '116', '1', '0', '52', '1504177519', '申请礼包码');
INSERT INTO `zy_media_gift_opt_record` VALUES ('18', '123', '1', '0', '52', '1504177764', '申请礼包码');
INSERT INTO `zy_media_gift_opt_record` VALUES ('19', '124', '1', '0', '52', '1504177768', '申请礼包码');
INSERT INTO `zy_media_gift_opt_record` VALUES ('20', '121', '1', '0', '52', '1504177773', '申请礼包码');
INSERT INTO `zy_media_gift_opt_record` VALUES ('21', '122', '1', '0', '52', '1504177779', '申请礼包码');

-- ----------------------------
-- Table structure for zy_media_gonglue
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_gonglue`;
CREATE TABLE `zy_media_gonglue` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `app_id` int(10) DEFAULT NULL COMMENT '应用id',
  `gl_title` varchar(255) DEFAULT NULL COMMENT '攻略标题',
  `gl_url` varchar(255) DEFAULT NULL COMMENT '攻略地址',
  `admin_id` int(10) DEFAULT '0' COMMENT '操作人',
  `create_time` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zy_media_gonglue
-- ----------------------------
INSERT INTO `zy_media_gonglue` VALUES ('2', '32', '【网易阴阳师】四月骗子曝光公告', 'https://tieba.baidu.com/p/5103191100', '37', '1495680700');
INSERT INTO `zy_media_gonglue` VALUES ('3', '32', '【网易阴阳师】四月骗子曝光公告', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('4', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('5', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('6', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('7', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('8', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('9', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('10', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');
INSERT INTO `zy_media_gonglue` VALUES ('11', '32', '游戏品质', 'https://tieba.baidu.com/p/5103191100', '37', '1495682707');

-- ----------------------------
-- Table structure for zy_media_independent_content
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_independent_content`;
CREATE TABLE `zy_media_independent_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `keyword` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字，唯一',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text COMMENT '内容',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `is_publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否发布 1发布2未发布，',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '编辑时间',
  `is_delete` tinyint(1) DEFAULT '1' COMMENT '是否删除，1正常2删除',
  `seo_keyword` varchar(80) DEFAULT NULL COMMENT 'seo关键字',
  `seo_description` varchar(150) DEFAULT NULL COMMENT 'seo描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='独立显示内容表，关于我们，联系我们等内容';

-- ----------------------------
-- Records of zy_media_independent_content
-- ----------------------------
INSERT INTO `zy_media_independent_content` VALUES ('1', 'ABOUT_US', '关于我们', '&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:48px;line-height:15px&quot;&gt;&lt;img src=&quot;/Upload/Image/ueditor/20170821/1503297991286548.png&quot; title=&quot;1503297991286548.png&quot; alt=&quot;tuiguang.png&quot;/&gt;一、&lt;span style=&quot;font-variant-numeric: normal;font-stretch: normal;font-size: 9px;line-height: normal;font-family: &amp;#39;Times New Roman&amp;#39;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;评论管理模块&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;line-height:15px&quot;&gt;1.&lt;span style=&quot;font-variant-numeric: normal;font-stretch: normal;font-size: 9px;line-height: normal;font-family: &amp;#39;Times New Roman&amp;#39;&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;评论列表（现版本是关于游戏的评论，后期会有文章的评论）&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;1.1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;评论未按游戏分类&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）评论可以根据游戏名称，昵称，发布时间，星级进行搜索，以及通过标签切换&lt;/span&gt;sp_type &lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;为&lt;/span&gt; 0,1,2&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;分别查询全部评论，被投诉评论与已删除评论&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;2&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）评论列表排序默认按置顶评论降序，置顶时间降序，以及评论时间升序排序。被投诉的评论按评论状态升序以及评论时间降序排序&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;3&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）根据条件获取评论列表（关联用户表，游戏库表）&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;获取用户的头像，昵称，用户&lt;/span&gt;id&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;，评论&lt;/span&gt;id&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;，评论内容，点赞数，评论时间，评论星级，评论状态（&lt;/span&gt;status&amp;lt;&amp;gt;1,&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;未删除的评论）&lt;/span&gt;,&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;游戏名称，游戏&lt;/span&gt;id&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;，总评论数，再获取该游戏已置顶的评论竖，待处理的评论投诉数量（投诉表&lt;/span&gt;status=0&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;4&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）点击列表的投诉红圈可以展示该评论的所有投诉&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;5&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）如果评论有投诉则显示列表的删除评论按钮&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;6&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）如果待处理的投诉则显示忽略投诉按钮&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;7&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）已置顶的投诉显示已置顶标识，显示置顶或取消置顶操作&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;1.2&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;评论按游戏分类&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）计算游戏未删除的评论的总数（&lt;/span&gt;status&amp;lt;&amp;gt;1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;2&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）计算游戏未删除的置顶的评论总数（&lt;/span&gt;status&amp;lt;&amp;gt;1 and is_top = 1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;3&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）计算游戏被投诉的评论（评论的投诉大于&lt;/span&gt;0&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）的总数&lt;/span&gt;&lt;/p&gt;&lt;p class=&quot;MsoListParagraph&quot; style=&quot;margin-left:72px;text-indent:0;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;4&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）如果有评论，点击评论的数量跳转到游戏具体的评论列表&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;line-height:15px&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; 2. &lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;游戏评论星级列表&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;line-height:15px&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; 2.1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;展示所有游戏的星级，即游戏评论各个星级评论的数量&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;line-height:15px&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp; &lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;1&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）去重查询评论表中未删除评论的评论人数&lt;/span&gt;count(DISTINCT uid)&lt;/p&gt;&lt;p style=&quot;margin-left:48px;text-indent:48px;line-height: 15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;这个计算的人数应该会小于或等于各个星级评论人数的总和&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent:81px;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;2&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）去重计算各个星级为删除评论的人数评论&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent:81px;line-height:15px&quot;&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;（&lt;/span&gt;3&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;）根据公式&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;5&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;星&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;*&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;百分比&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;+4&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;星&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;*&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;百分比&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;+3&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;星&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;*&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;百分比&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;+2&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;星&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;*&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;百分比&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;+1&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;星&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;*&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;百分比（百分比&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;=&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;人数&lt;/span&gt;&lt;span style=&quot;color:red&quot;&gt;/&lt;/span&gt;&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;;color:red&quot;&gt;总评论人数）&lt;/span&gt; &lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;计算综合评分&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent:48px;line-height:15px&quot;&gt;&amp;nbsp; &amp;nbsp; 3.&lt;span style=&quot;font-family:&amp;#39;微软雅黑&amp;#39;,&amp;#39;sans-serif&amp;#39;&quot;&gt;游戏评分管理页&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 48px; line-height: 15px;&quot;&gt;&lt;span style=&quot;text-indent: 81px;&quot;&gt;&lt;span style=&quot;font-family:微软雅黑, sans-serif&quot;&gt;&amp;nbsp; &amp;nbsp;&amp;nbsp;&lt;/span&gt;3.1 &lt;/span&gt;&lt;span style=&quot;text-indent: 81px; font-family: 微软雅黑, sans-serif;&quot;&gt;计算游戏的真实的综合评分与虚拟的综合评分&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 48px; line-height: 15px;&quot;&gt;&lt;span style=&quot;text-indent: 81px; font-family: 微软雅黑, sans-serif;&quot;&gt;&amp;nbsp; &amp;nbsp; （&lt;/span&gt;&lt;span style=&quot;text-indent: 81px;&quot;&gt;1&lt;/span&gt;&lt;span style=&quot;text-indent: 81px; font-family: 微软雅黑, sans-serif;&quot;&gt;）通过游戏&lt;/span&gt;&lt;span style=&quot;text-indent: 81px;&quot;&gt;id&lt;/span&gt;&lt;span style=&quot;text-indent: 81px; font-family: 微软雅黑, sans-serif;&quot;&gt;获取该游戏各个星级的未删除的评论的去重的人数，以及总的评论人数，根据公式计算真实的综合评分&amp;nbsp;&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 48px; line-height: 15px;&quot;&gt;&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;&amp;nbsp; &amp;nbsp; （&lt;/span&gt;2&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;）从&lt;/span&gt;app_lib&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;表获取各个星级的设置的虚拟评论人数，根据公式计算虚拟的综合评分&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 48px; line-height: 15px;&quot;&gt;&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;&amp;nbsp; &amp;nbsp; （&lt;/span&gt;3&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;）如果存在真实综合评分不存在虚拟评论人数则把真实综合评分保存到&lt;/span&gt;app_lib&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;的&lt;/span&gt;colligate_score&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;字&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent: 48px; line-height: 15px;&quot;&gt;&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;如果存在真实评分且存在虚拟评论人数则把虚拟综合评分保存到&lt;/span&gt;app_lib&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;的&lt;/span&gt;colligate_score&lt;span style=&quot;font-family: 微软雅黑, sans-serif;&quot;&gt;字段&lt;/span&gt;&lt;/p&gt;&lt;p style=&quot;text-indent:81px;line-height:15px&quot;&gt;&amp;nbsp;&lt;/p&gt;&lt;p style=&quot;text-indent:81px;line-height:15px&quot;&gt;&amp;nbsp;&lt;img src=&quot;/Upload/Image/ueditor/20170821/1503297999681193.png&quot; title=&quot;1503297999681193.png&quot; alt=&quot;logo.png&quot;/&gt;&lt;/p&gt;&lt;p style=&quot;margin-left:48px;text-indent:48px;line-height: 15px&quot;&gt;&amp;nbsp;&lt;/p&gt;&lt;p style=&quot;line-height:15px&quot;&gt;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&amp;nbsp;&lt;/p&gt;&lt;p style=&quot;line-height:15px&quot;&gt;&amp;nbsp;&lt;/p&gt;&lt;p style=&quot;line-height:15px&quot;&gt;&amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp; &amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '1', '2', '1500952020', '1503298132', '1', '', 'dgsdg');
INSERT INTO `zy_media_independent_content` VALUES ('2', 'CONTACT_US', '联系我们', null, '2', '2', '1501497481', '1501497481', '1', null, null);
INSERT INTO `zy_media_independent_content` VALUES ('3', 'NEW_JOBS', '招贤纳士', null, '0', '2', '1503889925', '1503889925', '1', null, null);
INSERT INTO `zy_media_independent_content` VALUES ('4', 'SITE_MAP', '网站地图', null, '0', '2', '1503890040', '1503890040', '1', null, null);

-- ----------------------------
-- Table structure for zy_media_index_app_test_open
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_index_app_test_open`;
CREATE TABLE `zy_media_index_app_test_open` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏id',
  `app_name` varchar(255) NOT NULL DEFAULT '' COMMENT '游戏名称',
  `open_test_time` int(11) DEFAULT '0' COMMENT '开服开测时间',
  `app_platform` tinyint(1) DEFAULT '1' COMMENT '有戏平台',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型，1开测，2开服',
  `index_content_id` int(11) NOT NULL DEFAULT '0' COMMENT '首页内容id',
  PRIMARY KEY (`id`),
  KEY `idx_app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='首页开服开测游戏';

-- ----------------------------
-- Records of zy_media_index_app_test_open
-- ----------------------------
INSERT INTO `zy_media_index_app_test_open` VALUES ('1', '17', '口袋妖怪重制', '1504177894', '1', '1', '38');
INSERT INTO `zy_media_index_app_test_open` VALUES ('2', '32', '阴阳师', '0', '0', '1', '39');

-- ----------------------------
-- Table structure for zy_media_index_column_category
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_index_column_category`;
CREATE TABLE `zy_media_index_column_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '栏目关键词，唯一',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '专栏名称',
  `is_delete` tinyint(1) DEFAULT '1' COMMENT '专栏状态，1正常，2删除',
  `image_size` varchar(255) DEFAULT '' COMMENT '广告图片大小说明',
  `num_limit` int(11) DEFAULT '0' COMMENT '该分类允许添加内容数量，0默认不限制',
  `type` tinyint(2) DEFAULT '0' COMMENT '首页内容类型，0 默认标题图片链接，1标题链接，3特殊链接',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='首页栏目分类表';

-- ----------------------------
-- Records of zy_media_index_column_category
-- ----------------------------
INSERT INTO `zy_media_index_column_category` VALUES ('1', 'TOP_BANNER', '顶部大图', '1', '图片大小1170*300', '3', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('2', 'LEFT_BANNER', '左侧轮播', '1', '图片大小1170*300', '5', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('3', 'TOP_ARTICLE', '头条文章', '1', '图片大小1170*300', '2', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('4', 'RECOMMEND_LIST', '列表推荐', '1', '图片大小1170*300', '9', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('5', 'HOT_GUIDE', '热门攻略', '1', '图片大小1170*300', '4', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('6', 'NEW_GAME_TEST', '新游测评', '1', '图片大小1170*300', '3', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('7', 'EVERYDAT_QUESTION', '每日一题', '1', '图片大小1170*300', '5', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('8', 'GREAT_TOPIC', '精彩专题', '1', '图片大小1170*300', '3', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('9', 'HOT_ACTIVITY', '热门活动', '1', '图片大小1170*300', '5', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('10', 'GUIDE_STATION', '攻略驿站', '1', '图片大小1170*300', '3', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('11', 'WEEK_RECOMMAND', '本周推荐', '1', '图片大小1170*300', '1', '0');
INSERT INTO `zy_media_index_column_category` VALUES ('12', 'NEW_APP_NOTICE', '新游预告', '1', '无', '12', '0');

-- ----------------------------
-- Table structure for zy_media_index_column_content
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_index_column_content`;
CREATE TABLE `zy_media_index_column_content` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属的分类',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `image_path` varchar(255) DEFAULT '' COMMENT '广告图片',
  `href_link` varchar(255) DEFAULT '' COMMENT '跳转链接',
  `description` text COMMENT '描述',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `is_publish` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否发布 1发布,2未发布',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '编辑时间',
  `is_delete` tinyint(1) DEFAULT '1' COMMENT '是否删除，1正常，2删除',
  `extend` varchar(500) DEFAULT NULL COMMENT '扩展字段的参数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='首页内容表';

-- ----------------------------
-- Records of zy_media_index_column_content
-- ----------------------------
INSERT INTO `zy_media_index_column_content` VALUES ('1', '1', '口袋妖怪重制', 'Uploads/Images/ad/2017-08-29/59a5429950b4d.png', 'http://www.baidu.com', null, '1', '1', '1504002720', '1504267206', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('2', '1', '顶部大图2', 'Uploads/Images/ad/2017-08-29/59a542e3805f9.png', 'http://www.baidu.com', null, '2', '1', '1504002791', '0', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('3', '1', '顶部大图3', 'Uploads/Images/ad/2017-08-29/59a5431ab3ecf.png', 'http://www.baidu.com', null, '3', '1', '1504002846', '0', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('4', '12', '阴阳师', '', '', null, '1', '1', '1504161745', '1504162944', '2', 'a:4:{s:16:\"app_publish_time\";i:1504161720;s:6:\"app_id\";i:32;s:8:\"app_name\";s:9:\"阴阳师\";s:12:\"app_platform\";i:1;}');
INSERT INTO `zy_media_index_column_content` VALUES ('5', '2', '左侧轮播1', 'Uploads/Images/ad/2017-08-31/59a7eec55f545.png', 'http://www.baidu.com', null, '1', '1', '1504177866', '1504177866', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('6', '2', '左侧轮播2', 'Uploads/Images/ad/2017-08-31/59a7eee2b5ebc.png', 'http://www.baidu.com', null, '2', '1', '1504177894', '1504177894', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('7', '2', '左侧大图3', 'Uploads/Images/ad/2017-08-31/59a7eef4da8d2.png', 'http://www.baidu.com', null, '0', '1', '1504177911', '1504177911', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('8', '6', '新游测评', 'Uploads/Images/ad/2017-08-31/59a7f2bb8cfc5.png', 'http://www.baidu.com', null, '1', '1', '1504178881', '1504178881', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('9', '6', '新游测评2', 'Uploads/Images/ad/2017-08-31/59a7f2d43f349.png', 'http://www.baidu.com', null, '0', '1', '1504178903', '1504178903', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('10', '6', '心有测评', 'Uploads/Images/ad/2017-08-31/59a7f2e9d180c.png', 'http://www.baidu.com', null, '3', '1', '1504178924', '1504178924', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('11', '7', '大图', 'Uploads/Images/ad/2017-08-31/59a7f71291838.png', 'http://www.baidu.com', null, '1', '1', '1504179990', '1504179990', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('12', '7', '1', 'Uploads/Images/ad/2017-08-31/59a7f71f7d2fb.png', 'http://www.baidu.com', null, '2', '1', '1504180002', '1504180002', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('13', '7', '2', 'Uploads/Images/ad/2017-08-31/59a7f72b0058c.png', 'http://www.baidu.com', null, '3', '1', '1504180019', '1504180019', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('14', '7', '4', 'Uploads/Images/ad/2017-08-31/59a7f73e6f36c.png', 'http://www.baidu.com', null, '4', '1', '1504180035', '1504180035', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('15', '7', '5', 'Uploads/Images/ad/2017-08-31/59a7f74ba7cb9.png', 'http://www.baidu.com', null, '5', '1', '1504180047', '1504180047', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('16', '12', '口袋妖怪重制', '', '', null, '2', '1', '1504181015', '1504181015', '2', 'a:4:{s:16:\"app_publish_time\";i:1504267405;s:6:\"app_id\";i:17;s:8:\"app_name\";s:18:\"口袋妖怪重制\";s:12:\"app_platform\";i:0;}');
INSERT INTO `zy_media_index_column_content` VALUES ('17', '8', '1', 'Uploads/Images/ad/2017-08-31/59a7fe88d766e.png', 'http://www.baidu.com', null, '1', '1', '1504181901', '1504181901', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('18', '8', '2', 'Uploads/Images/ad/2017-08-31/59a7fea66ba91.png', 'http://www.baidu.com', null, '2', '1', '1504181930', '1504181930', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('19', '8', '3', 'Uploads/Images/ad/2017-08-31/59a7feb1e1fc1.png', 'http://www.baidu.com', null, '3', '1', '1504181946', '1504181946', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('20', '9', '1', 'Uploads/Images/ad/2017-08-31/59a7fedd2c75c.png', 'http://www.baidu.com', null, '1', '1', '1504181985', '1504181985', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('21', '9', '2', 'Uploads/Images/ad/2017-08-31/59a7feee44616.png', 'http://www.baidu.com', null, '2', '1', '1504181999', '1504181999', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('22', '9', '3', 'Uploads/Images/ad/2017-08-31/59a7ff00ae59c.png', 'http://www.baidu.com', null, '3', '1', '1504182017', '1504182017', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('23', '9', '4', 'Uploads/Images/ad/2017-08-31/59a7ff097c2e3.png', 'http://www.baidu.com', null, '4', '1', '1504182035', '1504182035', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('24', '9', '5', 'Uploads/Images/ad/2017-08-31/59a7ff24a70a5.png', 'http://www.baidu.com', null, '5', '1', '1504182057', '1504182057', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('25', '5', '1', 'Uploads/Images/ad/2017-08-31/59a806871ce34.png', 'http://www.baidu.com', null, '1', '1', '1504183948', '1504184219', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('26', '5', '2', 'Uploads/Images/ad/2017-08-31/59a8069a028a4.png', 'http://www.baidu.com', null, '2', '1', '1504183965', '1504184222', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('27', '3', '头条文章1', 'Uploads/Images/ad/2017-09-01/59a8c780c57eb.png', 'http://www.baidu.com', null, '1', '1', '1504233345', '1504233345', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('28', '3', '头条文章2', 'Uploads/Images/ad/2017-09-01/59a8c790b0f71.png', 'http://www.baidu.com', null, '2', '1', '1504233365', '1504233365', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('29', '4', '文章列表1', '', 'http://www.baidu.com', null, '1', '1', '1504233461', '1504233461', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('30', '4', '文章列表2', '', 'http://www.baidu.com', null, '2', '1', '1504233477', '1504233477', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('31', '4', '文章列表3', '', 'http://www.baidu.com', null, '1', '1', '1504233490', '1504233490', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('32', '4', '文章列表4', '', 'http://www.baidu.com', null, '1', '1', '1504233461', '1504233461', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('33', '4', '文章列表5', '', 'http://www.baidu.com', null, '2', '1', '1504233477', '1504233477', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('34', '4', '文章列表6', '', 'http://www.baidu.com', null, '1', '1', '1504233490', '1504233490', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('35', '4', '文章列表7', '', 'http://www.baidu.com', null, '1', '1', '1504233461', '1504233461', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('36', '4', '文章列表8', '', 'http://www.baidu.com', null, '2', '1', '1504233477', '1504233477', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('37', '4', '文章列表9', '', 'http://www.baidu.com', null, '1', '1', '1504233490', '1504233490', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('38', '12', '口袋妖怪重制', '', '', null, '1', '1', '1504260875', '1504260875', '1', null);
INSERT INTO `zy_media_index_column_content` VALUES ('39', '12', '阴阳师', '', '', null, '1', '1', '1504267440', '1504267440', '1', null);

-- ----------------------------
-- Table structure for zy_media_sync_gift_lib
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_sync_gift_lib`;
CREATE TABLE `zy_media_sync_gift_lib` (
  `gift_id` int(11) NOT NULL COMMENT '礼包库id',
  `limited_count` int(11) NOT NULL COMMENT '上限数量',
  `pre_hot_sort` int(11) DEFAULT '0' COMMENT '未生成列表时指定的热门列表排序',
  `final_hot_sort` int(11) DEFAULT '0' COMMENT '生成列表时指定的热门列表排序',
  `pre_new_sort` int(11) DEFAULT '0' COMMENT '未生成列表时指定的最新列表排序',
  `final_new_sort` int(11) DEFAULT '0' COMMENT '生成列表时指定的最新列表排序',
  `edit_time` int(11) DEFAULT '0' COMMENT '编辑时间',
  `gift_detail` varchar(255) DEFAULT NULL COMMENT '礼包简介',
  PRIMARY KEY (`gift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='媒体站礼包库表';

-- ----------------------------
-- Records of zy_media_sync_gift_lib
-- ----------------------------
INSERT INTO `zy_media_sync_gift_lib` VALUES ('116', '50', '0', '0', '0', '0', '1504083773', '体力x20, 火之卷x10, 扫荡卷x20');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('121', '30', '0', '0', '0', '0', '1504084010', '初级技能书礼盒*1，魔精石（大）*5，血精石（大）*5，消罪令*3');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('122', '20', '0', '0', '0', '0', '1504084010', '神魔令*5，洗炼石*10，洗炼锁*10，三级宝石礼盒*3');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('123', '60', '0', '0', '0', '0', '1504084010', '复活令*5，消罪令*10，四级黑铁石*10，魔精石*10');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('124', '50', '0', '0', '0', '0', '1504084010', '血精石（大）*5，魔精石（大）*5，神魔令*5，三级黑铁石*5，复活令*1');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('125', '120', '0', '0', '0', '0', '1504083934', '包子*3   精力囊*5   钻石*280');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('126', '500', '0', '0', '0', '0', '1504083934', '包子*5   烤乳猪*5   小经验丹*20   钻石*480');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('127', '150', '0', '0', '0', '0', '1504083934', '烤乳猪*10   钻石*780   水晶*500   金币*666666');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('128', '160', '0', '0', '0', '0', '1504083934', '满汉全席*10   水晶*1000   神转丹*200   神装魔晶*30');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('129', '110', '0', '0', '0', '0', '1504083934', '水晶*2000   神转丹*500   神装魔晶*50   红将碎盒·C*20');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('132', '50', '0', '0', '0', '0', '1504083820', '炽耀石*10、洛可魔果Lv2*1');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('133', '50', '0', '0', '0', '0', '1504083820', '炽耀石*30、洛可魔果Lv3*1、战姬之心Lv3');
INSERT INTO `zy_media_sync_gift_lib` VALUES ('134', '50', '0', '0', '0', '0', '1504083820', '炽耀石*30、5心礼物*1');

-- ----------------------------
-- Table structure for zy_media_sys_config
-- ----------------------------
DROP TABLE IF EXISTS `zy_media_sys_config`;
CREATE TABLE `zy_media_sys_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增主键',
  `keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键词，唯一',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '配置名称',
  `config_value` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `is_delete` tinyint(1) DEFAULT '1' COMMENT '岗位分类状态，1正常,2删除',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='配置表';

-- ----------------------------
-- Records of zy_media_sys_config
-- ----------------------------
INSERT INTO `zy_media_sys_config` VALUES ('1', 'SITE_NAME', '网站名称', '嘉玩互动-媒体站1ssdsdsd', '1', '0');
INSERT INTO `zy_media_sys_config` VALUES ('2', 'IPC_INFO', 'IPC备案信息', '待确认2', '1', '0');
INSERT INTO `zy_media_sys_config` VALUES ('3', 'SITE_SEO_TITLE', '网站SEO标题', '嘉玩互动-媒体站', '1', '0');
INSERT INTO `zy_media_sys_config` VALUES ('4', 'SITE_SEO_KEYWORD', '网站SEO关键字', 'dfdf2', '1', '0');
INSERT INTO `zy_media_sys_config` VALUES ('5', 'SITE_SEO_DESCRIPTION', '网站SEO描述', 'sdfsdfsd3', '1', '0');
