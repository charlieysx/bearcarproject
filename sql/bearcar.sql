/*
 Navicat Premium Data Transfer

 Source Server         : 120.78.66.16
 Source Server Type    : MySQL
 Source Server Version : 50556
 Source Host           : 120.78.66.16:3306
 Source Schema         : bearcar

 Target Server Type    : MySQL
 Target Server Version : 50556
 File Encoding         : 65001

 Date: 11/03/2018 09:54:00
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` text NOT NULL COMMENT '用户id',
  `phone` text NOT NULL COMMENT '手机号',
  `password` text NOT NULL COMMENT '密码',
  `user_name` text NOT NULL COMMENT '用户名',
  `salt` text NOT NULL COMMENT '秘钥（加密密码）',
  `last_login_time` text NOT NULL COMMENT '最后一次登录的时间',
  `login_count` int(11) NOT NULL DEFAULT '1' COMMENT '登录次数',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '账号状态，1：账号正常，2：账号被删除',
  `access_token` text COMMENT 'token',
  `token_expiresIn` text COMMENT 'token有效期至',
  `type` int(11) NOT NULL DEFAULT '2' COMMENT '类型，1：超级管理员，有所有权限，2：普通管理员，默认2',
  `register_time` text NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`,`user_id`(10)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='后台管理员用户';

-- ----------------------------
-- Table structure for banner
-- ----------------------------
DROP TABLE IF EXISTS `banner`;
CREATE TABLE `banner` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `banner` text NOT NULL COMMENT 'banner url',
  `time` text NOT NULL COMMENT '发布banner的时间',
  `link` text COMMENT '点击跳转的链接',
  `position` int(11) NOT NULL DEFAULT '1' COMMENT '第几张banner',
  `status` int(11) NOT NULL COMMENT '0:上架中，1：下架',
  `title` text COMMENT 'banner标题',
  `under_time` text COMMENT '下架时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='banner';

-- ----------------------------
-- Table structure for car
-- ----------------------------
DROP TABLE IF EXISTS `car`;
CREATE TABLE `car` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `user_id` text NOT NULL COMMENT '所属的用户id',
  `brand_id` int(11) NOT NULL COMMENT '品牌id',
  `series_id` int(11) NOT NULL COMMENT '车系id',
  `model_id` int(11) NOT NULL DEFAULT '-1' COMMENT '车型id',
  `local_license` int(11) DEFAULT '1' COMMENT '是否本地车牌，1：是，0：不是，默认1',
  `licensed_city` text COMMENT '上牌所在地，local_license为空时才有这个',
  `licensed_city_id` int(11) NOT NULL COMMENT '上牌所在地id，local_license为空时才有这个',
  `licensed_year` text NOT NULL COMMENT '初次上牌年份',
  `car_condition_id` int(11) NOT NULL COMMENT '车况id',
  `expire_date_id` int(11) NOT NULL COMMENT '预期售出时间id',
  `mileage` double NOT NULL COMMENT '当前里程，单位 万公里',
  `transfer_time` int(11) NOT NULL DEFAULT '-1' COMMENT '过户次数，0， 1， 2， 3，4， 5：4次以上，-1：不清楚',
  `last_transfer_time` int(11) DEFAULT '1' COMMENT '0表示两个月内，1表示超过两个月，默认1，过户次数不为0或-1时才有这个',
  `inspect_address` text NOT NULL COMMENT '预约地点',
  `inspect_lng` text NOT NULL COMMENT '经纬度',
  `inspect_lat` text NOT NULL COMMENT '经纬度',
  `inspect_datetime` text NOT NULL COMMENT '预约时间id 0:上午9-12点，1：下午12-18点，2：明天上午9-12点，3：明天下午12-18点，4：客服联系',
  `licensed_month` text NOT NULL COMMENT '初次上牌月份',
  `province_id` int(11) NOT NULL COMMENT '所在省份id',
  `city_id` int(11) NOT NULL COMMENT '所在城市id',
  `district_id` int(11) NOT NULL COMMENT '所在地区id',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '状态，0：待上架，1：售卖中，2：交易完成，4：手动下架，5：系统下架，6：检测中，默认0',
  `publish_time` text NOT NULL COMMENT '发布时间',
  `see_count` int(11) NOT NULL DEFAULT '0' COMMENT '查看人数，默认0',
  `under_reason` text COMMENT '系统下架理由，status=5时才有',
  `under_user_id` text COMMENT '下架者的id，statu=5时才有',
  `deal_user_id` text COMMENT '处理者的id，status != 0  && status != 4就有',
  `under_time` text COMMENT '下架时间',
  PRIMARY KEY (`id`,`car_id`(10)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COMMENT='用户卖车记录的车信息';

-- ----------------------------
-- Table structure for car_brand
-- ----------------------------
DROP TABLE IF EXISTS `car_brand`;
CREATE TABLE `car_brand` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL COMMENT '品牌id',
  `brand_name` text NOT NULL COMMENT '品牌名称',
  `first_char` char(1) NOT NULL COMMENT '拼音首字母',
  `search_count` int(11) NOT NULL DEFAULT '1' COMMENT '该品牌被搜索次数',
  PRIMARY KEY (`id`,`brand_id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8 COMMENT='车品牌';

-- ----------------------------
-- Table structure for car_condition
-- ----------------------------
DROP TABLE IF EXISTS `car_condition`;
CREATE TABLE `car_condition` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `condition_id` int(11) NOT NULL COMMENT '车况id',
  `condition_name` text NOT NULL COMMENT '车况名称',
  PRIMARY KEY (`id`,`condition_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='车况';

-- ----------------------------
-- Table structure for car_image
-- ----------------------------
DROP TABLE IF EXISTS `car_image`;
CREATE TABLE `car_image` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `img_out` text COMMENT '车辆外观（json保存）',
  `img_in` text COMMENT '中控内饰（json保存）',
  `img_engine_chassis` text COMMENT '发动机、底盘（json保存）',
  `img` text COMMENT '封面',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-图片';

-- ----------------------------
-- Table structure for car_model
-- ----------------------------
DROP TABLE IF EXISTS `car_model`;
CREATE TABLE `car_model` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL COMMENT '车型id',
  `model_name` text NOT NULL COMMENT '车型名称',
  `series_id` int(11) NOT NULL COMMENT '所属的车系id',
  PRIMARY KEY (`id`,`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25009 DEFAULT CHARSET=utf8 COMMENT='车型';

-- ----------------------------
-- Table structure for car_series
-- ----------------------------
DROP TABLE IF EXISTS `car_series`;
CREATE TABLE `car_series` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `series_id` int(11) NOT NULL COMMENT '车系id',
  `series_name` text NOT NULL COMMENT '车系名称',
  `brand_id` int(11) NOT NULL COMMENT '所属的车品牌id',
  `search_count` int(11) NOT NULL DEFAULT '1' COMMENT '该车系被搜索次数',
  PRIMARY KEY (`id`,`series_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1284 DEFAULT CHARSET=utf8 COMMENT='车系';

-- ----------------------------
-- Table structure for check_accident
-- ----------------------------
DROP TABLE IF EXISTS `check_accident`;
CREATE TABLE `check_accident` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-重大事故检测';

-- ----------------------------
-- Table structure for check_appearance
-- ----------------------------
DROP TABLE IF EXISTS `check_appearance`;
CREATE TABLE `check_appearance` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-外观内饰检测';

-- ----------------------------
-- Table structure for check_breakable_part
-- ----------------------------
DROP TABLE IF EXISTS `check_breakable_part`;
CREATE TABLE `check_breakable_part` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-易损耗部件检测';

-- ----------------------------
-- Table structure for check_crash
-- ----------------------------
DROP TABLE IF EXISTS `check_crash`;
CREATE TABLE `check_crash` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-轻微碰撞检测';

-- ----------------------------
-- Table structure for check_engine_status
-- ----------------------------
DROP TABLE IF EXISTS `check_engine_status`;
CREATE TABLE `check_engine_status` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-发动机状态检测';

-- ----------------------------
-- Table structure for check_high_tech
-- ----------------------------
DROP TABLE IF EXISTS `check_high_tech`;
CREATE TABLE `check_high_tech` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-高科技配置检测';

-- ----------------------------
-- Table structure for check_in_config
-- ----------------------------
DROP TABLE IF EXISTS `check_in_config`;
CREATE TABLE `check_in_config` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-内部配置检测';

-- ----------------------------
-- Table structure for check_instrument_desk
-- ----------------------------
DROP TABLE IF EXISTS `check_instrument_desk`;
CREATE TABLE `check_instrument_desk` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-仪表台指示灯检测';

-- ----------------------------
-- Table structure for check_light_system
-- ----------------------------
DROP TABLE IF EXISTS `check_light_system`;
CREATE TABLE `check_light_system` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-灯光系统检测';

-- ----------------------------
-- Table structure for check_out_config
-- ----------------------------
DROP TABLE IF EXISTS `check_out_config`;
CREATE TABLE `check_out_config` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-外部配置检测';

-- ----------------------------
-- Table structure for check_safety_system
-- ----------------------------
DROP TABLE IF EXISTS `check_safety_system`;
CREATE TABLE `check_safety_system` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-安全系统检测';

-- ----------------------------
-- Table structure for check_speed
-- ----------------------------
DROP TABLE IF EXISTS `check_speed`;
CREATE TABLE `check_speed` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-变速箱及转向检测';

-- ----------------------------
-- Table structure for check_time
-- ----------------------------
DROP TABLE IF EXISTS `check_time`;
CREATE TABLE `check_time` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `check_time_id` int(11) NOT NULL COMMENT '验车时间id',
  `value` text NOT NULL COMMENT '验车时间名称',
  PRIMARY KEY (`id`,`check_time_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='预期售出时间';

-- ----------------------------
-- Table structure for check_tool
-- ----------------------------
DROP TABLE IF EXISTS `check_tool`;
CREATE TABLE `check_tool` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-随车工具检测';

-- ----------------------------
-- Table structure for check_water_fire
-- ----------------------------
DROP TABLE IF EXISTS `check_water_fire`;
CREATE TABLE `check_water_fire` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `value` text COMMENT 'json数据保存',
  `abnormal` int(11) DEFAULT '0' COMMENT '异常数量',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COMMENT='二手车-泡水火烧检测';

-- ----------------------------
-- Table structure for city
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(11) NOT NULL COMMENT '城市id',
  `province_id` int(11) NOT NULL COMMENT '省份id',
  `province_name` varchar(10) NOT NULL COMMENT '省份名称',
  `name` varchar(16) NOT NULL COMMENT '城市名称',
  `zip` varchar(6) NOT NULL COMMENT 'zip',
  `first_char` varchar(1) NOT NULL COMMENT '拼音首字母',
  `search_count` int(11) NOT NULL DEFAULT '1' COMMENT '该城市被搜索次数',
  `lng` text NOT NULL COMMENT '经纬度',
  `lat` text NOT NULL COMMENT '经纬度',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='城市';

-- ----------------------------
-- Table structure for config_base
-- ----------------------------
DROP TABLE IF EXISTS `config_base`;
CREATE TABLE `config_base` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `credentials` text NOT NULL COMMENT '证件品牌型号',
  `vendor` text NOT NULL COMMENT '厂商',
  `level` text COMMENT '级别',
  `engine` text COMMENT '发动机',
  `speed` text COMMENT '变速箱',
  `structure` text COMMENT '车身结构',
  `length` text COMMENT '长度，单位：mm',
  `width` text COMMENT '宽度，单位：mm',
  `height` text COMMENT '高度，单位：mm',
  `wheelbase` text COMMENT '轴距，单位：mm',
  `trunk` text COMMENT '行李箱容积，单位：L',
  `quality` text COMMENT '整备质量，单位：kg',
  PRIMARY KEY (`id`,`car_id`(11))
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-基本参数';

-- ----------------------------
-- Table structure for config_chassis_brake
-- ----------------------------
DROP TABLE IF EXISTS `config_chassis_brake`;
CREATE TABLE `config_chassis_brake` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `drive_mode` text COMMENT '驱动方式',
  `power_type` text COMMENT '助力类型',
  `suspension_front` text COMMENT '前悬挂类型',
  `suspension_behind` text COMMENT '后悬挂类型',
  `brake_front` text COMMENT '前制动类型',
  `brake_behind` text COMMENT '后制动类型',
  `parking_brake` text COMMENT '驻车制动类型',
  `tire_size_front` text COMMENT '前轮胎规格',
  `tire_size_behind` text COMMENT '后轮胎规格',
  PRIMARY KEY (`id`,`car_id`(11))
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-底盘与制动';

-- ----------------------------
-- Table structure for config_engine
-- ----------------------------
DROP TABLE IF EXISTS `config_engine`;
CREATE TABLE `config_engine` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `displacement` text COMMENT '排量，单位：L',
  `air_intake` text COMMENT '进气形式',
  `cylinder` text COMMENT '气缸',
  `max_horsepower` text COMMENT '最大马力。单位：Ps',
  `max_torque` text COMMENT '最大扭矩，单位：N*m',
  `fuel_type` text COMMENT '燃料类型',
  `fuel_label` text COMMENT '燃油标号',
  `oil_supply_way` text COMMENT '供油方式',
  `emission_standards` text COMMENT '排放标准',
  PRIMARY KEY (`id`,`car_id`(11))
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-发动机参数';

-- ----------------------------
-- Table structure for config_in
-- ----------------------------
DROP TABLE IF EXISTS `config_in`;
CREATE TABLE `config_in` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `multi_steering_wheel` text COMMENT '多功能方向盘',
  `cruise_control` text COMMENT '定速巡航',
  `air_conditioner` text COMMENT '空调',
  `air_conditioner_auto` text COMMENT '自动空调',
  `gps` text COMMENT 'GPS导航',
  `reversing_radar` text COMMENT '倒车雷达',
  `reversing_image_system` text COMMENT '倒车影像系统',
  `leather_seat` text COMMENT '真皮座椅',
  `seat_hot_front` text COMMENT '前排座椅加热',
  `seat_hot_behind` text COMMENT '后排座椅加热',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-内部配置';

-- ----------------------------
-- Table structure for config_out
-- ----------------------------
DROP TABLE IF EXISTS `config_out`;
CREATE TABLE `config_out` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `skylight_electric` text COMMENT '电动天窗',
  `skylight_full_view` text COMMENT '全景天窗',
  `electric_suction_door` text COMMENT '电动吸合门',
  `induction_trunk` text COMMENT '感应后备箱',
  `windshield_wiper_sensing` text COMMENT '感应雨刷',
  `windshield_wiper_behind` text COMMENT '后雨刷',
  `electric_window_front` text COMMENT '前电动车窗',
  `electric_window_behind` text COMMENT '后电动车窗',
  `rear_view_mirror_electric` text COMMENT '后视镜电动调节',
  `rear_view_mirror_hot` text COMMENT '后视镜加热',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-外部配置';

-- ----------------------------
-- Table structure for config_safety
-- ----------------------------
DROP TABLE IF EXISTS `config_safety`;
CREATE TABLE `config_safety` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL COMMENT '二手车id',
  `safety_airbag_main` text COMMENT '主驾驶安全气囊',
  `safety_airbag_vice` text COMMENT '副驾驶安全气囊',
  `side_airbag_front` text COMMENT '前排侧气囊',
  `side_airbag_behind` text COMMENT '后排侧气囊',
  `head_airbag_front` text COMMENT '前排头部气囊',
  `head_airbag_behind` text COMMENT '后排头部气囊',
  `tire_pressure_monitoring` text COMMENT '胎压监测',
  `in_control_lock` text COMMENT '车内中控锁',
  `child_seat_interface` text COMMENT '儿童座椅接口',
  `keyless_start` text COMMENT '无钥匙启动',
  `abs` text COMMENT '防抱死系统(ABS)',
  `esp` text COMMENT '车身稳定控制(ESP)',
  PRIMARY KEY (`id`,`car_id`(11)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-安全配置';

-- ----------------------------
-- Table structure for district
-- ----------------------------
DROP TABLE IF EXISTS `district`;
CREATE TABLE `district` (
  `id` int(11) NOT NULL COMMENT '地区id',
  `province_id` int(11) NOT NULL COMMENT '省份id',
  `city_id` int(11) NOT NULL COMMENT '城市id',
  `province_name` varchar(10) NOT NULL COMMENT '省份名称',
  `city_name` varchar(16) NOT NULL COMMENT '城市名称',
  `name` varchar(16) NOT NULL COMMENT '地区名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='地区名';

-- ----------------------------
-- Table structure for expire_date
-- ----------------------------
DROP TABLE IF EXISTS `expire_date`;
CREATE TABLE `expire_date` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `expire_date_id` int(11) NOT NULL COMMENT '预期售出时间id',
  `expire_date_name` text NOT NULL COMMENT '预期售出时间名称',
  PRIMARY KEY (`id`,`expire_date_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='预期售出时间';

-- ----------------------------
-- Table structure for info_base
-- ----------------------------
DROP TABLE IF EXISTS `info_base`;
CREATE TABLE `info_base` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `car_id` text NOT NULL,
  `price` double NOT NULL COMMENT '车主报价，单位：万',
  `new_car_price` double NOT NULL COMMENT '新车指导价，单位：万',
  `tax` text NOT NULL COMMENT '购置税，单位：万',
  `licensed_year` text NOT NULL COMMENT '上牌年份',
  `licensed_month` text NOT NULL COMMENT '上牌月份',
  `mileage` double NOT NULL COMMENT '表显里程',
  `licensed_city_id` int(11) NOT NULL COMMENT '上牌地id',
  `user_name` text NOT NULL COMMENT '车主称呼',
  `look_district_id` int(11) NOT NULL COMMENT '看车地址id',
  `year_check` text NOT NULL COMMENT '年检到期时间',
  `strong_risk` text NOT NULL COMMENT '交强险时间',
  `business_risk` text NOT NULL COMMENT '商业险到期时间',
  `car_options` text NOT NULL COMMENT '车源号',
  `transfer_time` int(11) NOT NULL DEFAULT '0' COMMENT '过户次数，0， 1， 2， 3，4， 5：4次以上，-1：不清楚',
  PRIMARY KEY (`id`,`car_id`(11))
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='二手车-基本信息';

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `news_id` text NOT NULL COMMENT '资讯id',
  `news_title` text NOT NULL COMMENT '资讯标题',
  `news_time` text NOT NULL COMMENT '发布时间',
  `news_content` text NOT NULL COMMENT '资讯内容',
  `news_img` text NOT NULL COMMENT '封面图片',
  `news_info` text NOT NULL COMMENT '简介',
  `see_count` int(11) NOT NULL DEFAULT '0' COMMENT '查看的人数',
  `user_id` text NOT NULL COMMENT '发布者id',
  `from` text NOT NULL COMMENT '来源',
  PRIMARY KEY (`id`,`news_id`(10)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='预期售出时间';

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `order_id` text NOT NULL COMMENT '订单id',
  `car_id` text NOT NULL COMMENT '二手车id',
  `appraiser_id` text NOT NULL COMMENT '评估师id',
  `user_id` text NOT NULL COMMENT '买家id',
  `start_time` text COMMENT '预约时间',
  `check_time` text COMMENT '检测时间',
  `finish_time` text COMMENT '订单结束时间',
  `price` double DEFAULT NULL COMMENT '交易价格',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '状态，1：检测中，2：销售中，3：交易完成，4：系统下架结束，默认1',
  `step` int(11) DEFAULT '1' COMMENT '检测的完成步骤，1：填写配置信息，2：填写检测信息，3：上传图片，4：检测完',
  `order_number` text NOT NULL COMMENT '订单编号',
  PRIMARY KEY (`id`,`order_id`(11),`order_number`(26)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='订单';

-- ----------------------------
-- Table structure for province
-- ----------------------------
DROP TABLE IF EXISTS `province`;
CREATE TABLE `province` (
  `id` int(11) NOT NULL COMMENT '省份id',
  `name` varchar(10) NOT NULL COMMENT '省份名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='省份';

-- ----------------------------
-- Table structure for search_record
-- ----------------------------
DROP TABLE IF EXISTS `search_record`;
CREATE TABLE `search_record` (
  `id` int(10) NOT NULL,
  `user_id` text NOT NULL COMMENT '用户',
  `brand_id` int(11) DEFAULT NULL COMMENT '品牌id',
  `series_id` int(11) DEFAULT NULL COMMENT '车系id',
  `model_id` int(11) DEFAULT NULL COMMENT '车型id',
  `car_id` text COMMENT '二手车的id',
  `price` text COMMENT '价格范围',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户搜索记录表';

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` text NOT NULL COMMENT '用户id',
  `phone` text NOT NULL COMMENT '手机号',
  `password` text NOT NULL COMMENT '密码',
  `salt` text NOT NULL COMMENT '秘钥（加密密码）',
  `last_login_time` text NOT NULL COMMENT '最后一次登录的时间',
  `login_count` int(11) NOT NULL DEFAULT '1' COMMENT '登陆次数',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '账号状态，1：账号正常，2：账号被删除',
  `access_token` text COMMENT 'token',
  `token_expiresIn` text COMMENT 'token有效期至',
  `register_time` text NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`,`user_id`(10)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8 COMMENT='用户';

-- ----------------------------
-- Table structure for user_order
-- ----------------------------
DROP TABLE IF EXISTS `user_order`;
CREATE TABLE `user_order` (
  `id` int(10) NOT NULL,
  `car_id` text NOT NULL COMMENT '二手车id',
  `user_id` text NOT NULL COMMENT '预约者id',
  `time` text NOT NULL COMMENT '预约时的时间',
  `user_name` text NOT NULL COMMENT '预约者称呼',
  PRIMARY KEY (`id`,`user_id`(11)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户预约车辆表';

SET FOREIGN_KEY_CHECKS = 1;
