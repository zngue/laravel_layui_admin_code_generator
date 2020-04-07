
DROP TABLE IF EXISTS `code_generator`;
CREATE TABLE `code_generator` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `space_id` int(10) unsigned DEFAULT '0' COMMENT '命名空间 id',
  `mod_id` int(10) unsigned DEFAULT '0' COMMENT '模型id',
  `name` varchar(255) DEFAULT '' COMMENT '文件中文名称',
  `sort` mediumint(5) unsigned DEFAULT '0',
  `title` varchar(255) DEFAULT '' COMMENT '文件中文名字',
  `finish` tinyint(1) unsigned DEFAULT '0',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `route_status` tinyint(1) unsigned DEFAULT '0' COMMENT '路由生成',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of code_generator
-- ----------------------------
INSERT INTO `code_generator` VALUES ('9', '4', '0', 'ConfigList', '0', '配置列表', '1', '1', '2020-03-06 18:44:33', '2020-03-06 10:44:33', null);
INSERT INTO `code_generator` VALUES ('10', '4', '0', 'platform', '0', '平台管理', '1', '1', '2020-03-07 11:48:32', '2020-03-07 03:48:32', null);
INSERT INTO `code_generator` VALUES ('12', '6', '42', 'template', '0', '模板管理', '1', '1', '2020-03-10 09:22:57', '2020-03-10 01:22:57', '1');
INSERT INTO `code_generator` VALUES ('15', '6', '47', 'validator', '0', '表单后台验证规则', '1', '1', '2020-03-31 10:56:34', '2020-03-31 02:56:34', '1');
INSERT INTO `code_generator` VALUES ('17', '5', '45', 'cate', '0', '分类管理', '1', '1', '2020-04-03 13:28:45', '2020-04-03 13:28:45', '1');
INSERT INTO `code_generator` VALUES ('18', '7', '49', 'test', '0', 'Test', '1', '1', '2020-04-07 10:54:28', '2020-04-07 10:54:28', '1');

DROP TABLE IF EXISTS `namespace`;
CREATE TABLE `namespace` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT '' COMMENT '命名空间名称',
  `address` varchar(255) DEFAULT '' COMMENT '命名空间地址',
  `sort` mediumint(5) unsigned DEFAULT '0',
  `space` varchar(255) DEFAULT '' COMMENT '命名空间名称',
  `status` tinyint(1) unsigned DEFAULT '1' COMMENT '状态',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of namespace
-- ----------------------------
INSERT INTO `namespace` VALUES ('4', '配置中心服务端', 'addons\\config_ server\\', '0', 'Zngue\\ConfigServer\\', '1', '2020-03-06 07:39:01', '2020-03-06 07:39:01');
INSERT INTO `namespace` VALUES ('5', '分类管理', 'addons\\category\\', '0', 'Zngue\\Category\\', '1', '2020-03-09 02:36:56', '2020-03-09 02:36:56');
INSERT INTO `namespace` VALUES ('6', '模型管理', 'addons\\module\\', '0', 'Zngue\\Module\\', '1', '2020-03-09 08:31:47', '2020-03-09 08:31:47');
INSERT INTO `namespace` VALUES ('7', 'test', 'addons\\test\\', '0', 'Zngue\\Test\\', '1', '2020-04-03 19:09:17', '2020-04-03 19:09:17');

CREATE TABLE  IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` int(10) unsigned DEFAULT '0',
  `uri` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '权限跳转地址',
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '权限名称',
  `cate_pid_arr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '父级权限ids',
  `is_menu` tinyint(1) unsigned DEFAULT '0' COMMENT '是否菜单栏显示',
  `status` tinyint(1) DEFAULT '1' COMMENT '0 禁用 1 正常',
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图标',
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '权限描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO `permissions` VALUES (42, '代码管理', 0, 'code.index', 'code.index|code.ajaxList|code.completeCode', NULL, 1, 1, NULL, 'web', '2020-3-8 03:16:30', '2020-3-8 03:16:30', NULL);
INSERT INTO `permissions` VALUES (43, '代码列表', 42, 'code.index', 'code.index', '42', 1, 1, NULL, 'web', '2020-3-8 03:19:00', '2020-3-8 03:19:00', NULL);
INSERT INTO `permissions` VALUES (44, '添加代码', 43, 'code.add', 'code.add|code.doAdd', '42,43', 0, 1, NULL, 'web', '2020-3-8 03:22:28', '2020-3-8 03:22:28', NULL);
INSERT INTO `permissions` VALUES (45, '修改代码', 43, 'code.save', 'code.save|code.doSave|code.status|code.completeCode', '42,43,', 0, 1, NULL, 'web', '2020-3-8 03:23:40', '2020-3-8 03:24:41', NULL);
INSERT INTO `permissions` VALUES (46, '命名空间列表', 42, 'namespace.index', 'namespace.index', '42,', 1, 1, NULL, 'web', '2020-3-8 03:24:31', '2020-3-8 03:24:31', NULL);
INSERT INTO `permissions` VALUES (47, '添加命名空间', 46, 'namespace.add', 'namespace.add|namespace.doAdd', '42,46', 0, 1, NULL, 'web', '2020-3-8 03:25:24', '2020-3-8 03:25:24', NULL);
INSERT INTO `permissions` VALUES (48, '修改-命名空间', 46, 'namespace.save', 'namespace.save|namespace.doSave|namespace.status', '42,46,', 0, 1, NULL, 'web', '2020-3-8 03:26:24', '2020-3-8 03:26:24', NULL);
INSERT INTO `permissions` VALUES (49, '删除代码', 43, 'code.del', 'code.del', '42,43,', 0, 1, NULL, 'web', '2020-3-8 03:27:14', '2020-3-8 03:27:14', NULL);
INSERT INTO `permissions` VALUES (50, '删除命名空间', 46, 'namespace.del', 'namespace.del', '42,46,', 0, 1, NULL, 'web', '2020-3-8 03:28:09', '2020-3-8 03:28:09', NULL);
