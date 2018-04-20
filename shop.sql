/*
Navicat MySQL Data Transfer

Source Server         : Mysql
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : shop

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2018-04-19 22:19:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `attr`
-- ----------------------------
DROP TABLE IF EXISTS `attr`;
CREATE TABLE `attr` (
  `id` int(11) NOT NULL,
  `attr_name` varchar(255) NOT NULL,
  `type_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of attr
-- ----------------------------
INSERT INTO `attr` VALUES ('1', 'cpu', '1');
INSERT INTO `attr` VALUES ('2', 'memory', '1');
INSERT INTO `attr` VALUES ('3', 'size', '2');

-- ----------------------------
-- Table structure for `news`
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `body` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of news
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_admin`
-- ----------------------------
DROP TABLE IF EXISTS `shop_admin`;
CREATE TABLE `shop_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(255) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` char(255) NOT NULL DEFAULT '' COMMENT '盐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_admin
-- ----------------------------
INSERT INTO `shop_admin` VALUES ('1', 'admin', 'e56419914765c6d22dc5018114d73a28', '123456');
INSERT INTO `shop_admin` VALUES ('2', 'guest', 'ba5c7e61628ec4ef0e5176615166d239', '889779');
INSERT INTO `shop_admin` VALUES ('3', 'test', '1820d366c65ef7a4ce0171916f827c45', '604354');

-- ----------------------------
-- Table structure for `shop_admin_role`
-- ----------------------------
DROP TABLE IF EXISTS `shop_admin_role`;
CREATE TABLE `shop_admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0' COMMENT '后台用户id 关联admin表中的id字段',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID 关联角色表中的ID字段',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户角色中间表';

-- ----------------------------
-- Records of shop_admin_role
-- ----------------------------
INSERT INTO `shop_admin_role` VALUES ('1', '3', '1');
INSERT INTO `shop_admin_role` VALUES ('2', '4', '1');

-- ----------------------------
-- Table structure for `shop_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `shop_attribute`;
CREATE TABLE `shop_attribute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attr_name` varchar(255) NOT NULL DEFAULT '' COMMENT '属性名称',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性的所属类型 对应shop_type表中的id字段',
  `attr_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '属性本身的类型  1唯一属性 2、单选属性',
  `attr_input_type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '属性的录入方式 1手工输入  2列表选择',
  `attr_values` varchar(255) NOT NULL DEFAULT '' COMMENT '属性的默认值 多个值使用逗号隔开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_attribute
-- ----------------------------
INSERT INTO `shop_attribute` VALUES ('1', 'phone1', '0', '1', '1', '');
INSERT INTO `shop_attribute` VALUES ('2', 'phone2', '0', '1', '1', '');
INSERT INTO `shop_attribute` VALUES ('3', 'phone3', '0', '1', '1', '');
INSERT INTO `shop_attribute` VALUES ('4', 'mac1', '0', '1', '1', '');
INSERT INTO `shop_attribute` VALUES ('5', 'mac2', '0', '1', '1', '');
INSERT INTO `shop_attribute` VALUES ('6', 'mac3', '0', '1', '1', '');

-- ----------------------------
-- Table structure for `shop_cart`
-- ----------------------------
DROP TABLE IF EXISTS `shop_cart`;
CREATE TABLE `shop_cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '' COMMENT '商品属性值id组合 多个逗号隔开',
  `goods_count` smallint(6) NOT NULL DEFAULT '0' COMMENT '购买数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_category`
-- ----------------------------
DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE `shop_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `cname` varchar(255) NOT NULL,
  `parent_id` smallint(5) unsigned zerofill NOT NULL COMMENT '父分类id ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_category
-- ----------------------------
INSERT INTO `shop_category` VALUES ('1', '手机', '00000');
INSERT INTO `shop_category` VALUES ('2', '电脑', '00000');
INSERT INTO `shop_category` VALUES ('3', '华为手机', '00001');
INSERT INTO `shop_category` VALUES ('4', '苹果手机', '00001');
INSERT INTO `shop_category` VALUES ('5', '苹果电脑', '00002');
INSERT INTO `shop_category` VALUES ('6', '联想电脑', '00002');
INSERT INTO `shop_category` VALUES ('8', '喵嗷污', '00000');
INSERT INTO `shop_category` VALUES ('9', 'GoodsAddTest', '00000');

-- ----------------------------
-- Table structure for `shop_goods`
-- ----------------------------
DROP TABLE IF EXISTS `shop_goods`;
CREATE TABLE `shop_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '货号',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店售价  单位元',
  `goods_number` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `type_id` int(11) NOT NULL COMMENT '商品的类型ID 关联shop_type表中的id字段',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品的分类ID 关联category表中id字段',
  `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `goods_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `goods_body` text,
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否删除 1代表正常 0代表已经删除',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖 1是 0否',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否新品  1是0否',
  `is_rec` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否推荐  1是0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_goods
-- ----------------------------
INSERT INTO `shop_goods` VALUES ('1', '1204test', 'JX5a24f8eeb3714', '2222.00', '1111.00', '1', '0', '1', 'Uploads/2017-12-04/5a24f8d2c4b86.jpg', 'Uploads/2017-12-04/thumb_5a24f8d2c4b86.jpg', null, '1512372462', '1', '1', '1', '0');
INSERT INTO `shop_goods` VALUES ('2', 'test2', 'JX5a269b7545c3b', '2222.00', '1111.00', '1', '0', '1', 'Uploads/2017-12-05/5a269b70f263f.jpg', 'Uploads/2017-12-05/thumb_5a269b70f263f.jpg', null, '1512479605', '1', '0', '0', '0');
INSERT INTO `shop_goods` VALUES ('3', '喵嗷污', '1111', '1234.00', '1111.00', '1111', '0', '8', 'Uploads/2017-12-07/5a2917bd03fe9.jpg', 'Uploads/2017-12-07/thumb_5a2917bd03fe9.jpg', null, '1512642495', '1', '1', '1', '1');
INSERT INTO `shop_goods` VALUES ('4', '喵嗷污', '1112', '1123.00', '1122.00', '1234', '0', '8', 'Uploads/2017-12-07/5a2917f6286dc.jpg', 'Uploads/2017-12-07/thumb_5a2917f6286dc.jpg', null, '1512642564', '1', '1', '1', '1');
INSERT INTO `shop_goods` VALUES ('5', 'GoodsAddTest', 'JX5a2cc9b65e68e', '2450.00', '2000.00', '100', '0', '9', 'Uploads/2017-12-10/5a2cc9972d17d.jpg', 'Uploads/2017-12-10/thumb_5a2cc9972d17d.jpg', '&lt;p&gt;GoodsAddTest&lt;/p&gt;', '1512884662', '1', '1', '1', '1');
INSERT INTO `shop_goods` VALUES ('6', 'test3', 'JX5a2cd2d6b8285', '222.00', '123.00', '2', '0', '1', 'Uploads/2017-12-10/5a2cd2d02b3cf.jpg', 'Uploads/2017-12-10/thumb_5a2cd2d02b3cf.jpg', null, '1512886998', '1', '1', '1', '0');

-- ----------------------------
-- Table structure for `shop_goods_attr`
-- ----------------------------
DROP TABLE IF EXISTS `shop_goods_attr`;
CREATE TABLE `shop_goods_attr` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `attr_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性ID 对应attribute表中的id字段',
  `attr_value` varchar(255) NOT NULL DEFAULT '' COMMENT '属性值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_goods_attr
-- ----------------------------
INSERT INTO `shop_goods_attr` VALUES ('1', '10', '1', '2G');
INSERT INTO `shop_goods_attr` VALUES ('2', '10', '1', '4G');
INSERT INTO `shop_goods_attr` VALUES ('3', '10', '1', '8G');
INSERT INTO `shop_goods_attr` VALUES ('4', '10', '1', '32G');
INSERT INTO `shop_goods_attr` VALUES ('5', '10', '2', '24h');
INSERT INTO `shop_goods_attr` VALUES ('6', '10', '3', '800w');
INSERT INTO `shop_goods_attr` VALUES ('7', '10', '4', 'white');
INSERT INTO `shop_goods_attr` VALUES ('8', '10', '4', 'black');
INSERT INTO `shop_goods_attr` VALUES ('9', '10', '4', 'yellow');

-- ----------------------------
-- Table structure for `shop_goods_copy`
-- ----------------------------
DROP TABLE IF EXISTS `shop_goods_copy`;
CREATE TABLE `shop_goods_copy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `goods_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '货号',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `shop_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本店售价  单位元',
  `goods_number` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `cate_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品的分类ID 关联category表中id字段',
  `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片',
  `goods_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `goods_body` text,
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `is_del` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否删除 1代表正常 0代表已经删除',
  `is_hot` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否热卖 1是 0否',
  `is_new` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否新品  1是0否',
  `is_rec` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否推荐  1是0否',
  `cpu` varchar(255) NOT NULL DEFAULT '',
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_goods_copy
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_goods_img`
-- ----------------------------
DROP TABLE IF EXISTS `shop_goods_img`;
CREATE TABLE `shop_goods_img` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '相册原图',
  `goods_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '相册小图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_goods_img
-- ----------------------------
INSERT INTO `shop_goods_img` VALUES ('1', '2', 'Uploads/2017-12-05/5a269b754c56c.jpg', 'Uploads/2017-12-05/thumb_5a269b754c56c.jpg');
INSERT INTO `shop_goods_img` VALUES ('2', '2', 'Uploads/2017-12-05/5a269b754dff2.jpg', 'Uploads/2017-12-05/thumb_5a269b754dff2.jpg');
INSERT INTO `shop_goods_img` VALUES ('3', '2', 'Uploads/2017-12-05/5a269b754f35c.jpg', 'Uploads/2017-12-05/thumb_5a269b754f35c.jpg');
INSERT INTO `shop_goods_img` VALUES ('4', '4', 'Uploads/2017-12-07/5a291804c06ab.jpg', 'Uploads/2017-12-07/thumb_5a291804c06ab.jpg');
INSERT INTO `shop_goods_img` VALUES ('5', '4', 'Uploads/2017-12-07/5a291804c1bcf.jpg', 'Uploads/2017-12-07/thumb_5a291804c1bcf.jpg');
INSERT INTO `shop_goods_img` VALUES ('6', '4', 'Uploads/2017-12-07/5a291804c2ed1.jpg', 'Uploads/2017-12-07/thumb_5a291804c2ed1.jpg');
INSERT INTO `shop_goods_img` VALUES ('7', '4', 'Uploads/2017-12-07/5a291804c4117.jpg', 'Uploads/2017-12-07/thumb_5a291804c4117.jpg');
INSERT INTO `shop_goods_img` VALUES ('12', '5', 'Uploads/2017-12-10/5a2cc9b6654ce.jpg', 'Uploads/2017-12-10/thumb_5a2cc9b6654ce.jpg');
INSERT INTO `shop_goods_img` VALUES ('13', '5', 'Uploads/2017-12-10/5a2cc9b666f42.jpg', 'Uploads/2017-12-10/thumb_5a2cc9b666f42.jpg');
INSERT INTO `shop_goods_img` VALUES ('14', '5', 'Uploads/2017-12-10/5a2cc9b66807e.jpg', 'Uploads/2017-12-10/thumb_5a2cc9b66807e.jpg');
INSERT INTO `shop_goods_img` VALUES ('15', '5', 'Uploads/2017-12-10/5a2cc9b669d84.jpg', 'Uploads/2017-12-10/thumb_5a2cc9b669d84.jpg');
INSERT INTO `shop_goods_img` VALUES ('16', '0', 'Uploads/2017-12-10/5a2ccb2281272.jpg', 'Uploads/2017-12-10/thumb_5a2ccb2281272.jpg');
INSERT INTO `shop_goods_img` VALUES ('17', '0', 'Uploads/2017-12-10/5a2ccb2282c65.jpg', 'Uploads/2017-12-10/thumb_5a2ccb2282c65.jpg');
INSERT INTO `shop_goods_img` VALUES ('18', '0', 'Uploads/2017-12-10/5a2ccb2284f3f.jpg', 'Uploads/2017-12-10/thumb_5a2ccb2284f3f.jpg');
INSERT INTO `shop_goods_img` VALUES ('19', '0', 'Uploads/2017-12-10/5a2ccb22883b4.jpg', 'Uploads/2017-12-10/thumb_5a2ccb22883b4.jpg');
INSERT INTO `shop_goods_img` VALUES ('20', '6', 'Uploads/2017-12-10/5a2cd2d6bfa79.jpg', 'Uploads/2017-12-10/thumb_5a2cd2d6bfa79.jpg');

-- ----------------------------
-- Table structure for `shop_order`
-- ----------------------------
DROP TABLE IF EXISTS `shop_order`;
CREATE TABLE `shop_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `tel` char(11) NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `shr` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人',
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总价格',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `com` char(10) NOT NULL DEFAULT '' COMMENT '快递公司的编号',
  `no` varchar(255) NOT NULL DEFAULT '' COMMENT '快递的运单号',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单的状态 0表示下单  1已支付 2、已发货',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_order
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_order_detail`
-- ----------------------------
DROP TABLE IF EXISTS `shop_order_detail`;
CREATE TABLE `shop_order_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID对应order表中的ID字段',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_attr_id` varchar(255) NOT NULL DEFAULT '' COMMENT '商品属性值的ID组合',
  `goods_count` int(11) NOT NULL DEFAULT '0' COMMENT '购买的数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_order_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_role`
-- ----------------------------
DROP TABLE IF EXISTS `shop_role`;
CREATE TABLE `shop_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL DEFAULT '' COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_role
-- ----------------------------
INSERT INTO `shop_role` VALUES ('1', '超级管理员');
INSERT INTO `shop_role` VALUES ('2', '商品管理员');
INSERT INTO `shop_role` VALUES ('3', '订单管理员');

-- ----------------------------
-- Table structure for `shop_role_rule`
-- ----------------------------
DROP TABLE IF EXISTS `shop_role_rule`;
CREATE TABLE `shop_role_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `rule_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色权限中间表';

-- ----------------------------
-- Records of shop_role_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_rule`
-- ----------------------------
DROP TABLE IF EXISTS `shop_rule`;
CREATE TABLE `shop_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule_name` varchar(255) NOT NULL DEFAULT '' COMMENT '权限名称',
  `module_name` varchar(255) NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `controller_name` varchar(255) NOT NULL DEFAULT '' COMMENT '控制器名称',
  `action_name` varchar(255) NOT NULL DEFAULT '' COMMENT '操作名称',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '上级权限id',
  `is_show` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否导航菜单显示 1显示 0不显示',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of shop_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `shop_type`
-- ----------------------------
DROP TABLE IF EXISTS `shop_type`;
CREATE TABLE `shop_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) NOT NULL DEFAULT '' COMMENT '类型名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_type
-- ----------------------------
INSERT INTO `shop_type` VALUES ('3', 'php');

-- ----------------------------
-- Table structure for `shop_user`
-- ----------------------------
DROP TABLE IF EXISTS `shop_user`;
CREATE TABLE `shop_user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `tel` char(11) NOT NULL COMMENT '注册手机号',
  `email` varchar(255) NOT NULL DEFAULT '0' COMMENT '邮箱地址',
  `status` varchar(255) NOT NULL DEFAULT '0' COMMENT '是否激活 0未激活 1激活',
  `active_code` varchar(255) NOT NULL DEFAULT '0' COMMENT '激活码',
  `openid` varchar(255) NOT NULL COMMENT '用户登录QQ的唯一标志',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of shop_user
-- ----------------------------
INSERT INTO `shop_user` VALUES ('0', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '0', '0', '0', '');
