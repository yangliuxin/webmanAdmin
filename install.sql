/*
 Navicat Premium Dump SQL

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80040 (8.0.40)
 Source Host           : localhost:3306
 Source Schema         : db_petstar

 Target Server Type    : MySQL
 Target Server Version : 80040 (8.0.40)
 File Encoding         : 65001

 Date: 01/12/2024 14:15:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_menus
-- ----------------------------
DROP TABLE IF EXISTS `admin_menus`;
CREATE TABLE `admin_menus`  (
                                `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                `type` int NOT NULL DEFAULT 0,
                                `parent_id` int NOT NULL DEFAULT 0,
                                `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `uri` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `sort` int NOT NULL DEFAULT 0,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_menus
-- ----------------------------
INSERT INTO `admin_menus` VALUES (1, 1, 0, '首页', 'home', 'fa-dashboard', '/', 1, '2024-04-17 12:23:01', '2024-11-30 19:26:47');
INSERT INTO `admin_menus` VALUES (2, 1, 0, '系统设置', 'system', 'fa-tasks', '', 3, '2023-05-21 16:35:31', '2023-05-21 16:35:31');
INSERT INTO `admin_menus` VALUES (3, 1, 2, '用户管理', 'user', 'fa-user', 'users', 4, '2024-04-16 20:02:22', '2024-04-16 20:02:22');
INSERT INTO `admin_menus` VALUES (4, 1, 2, '角色管理', 'role', 'fa-user-md', 'roles', 3, '2024-04-16 19:43:50', '2024-04-16 19:43:50');
INSERT INTO `admin_menus` VALUES (5, 1, 2, '菜单模块', 'menu', 'fa-bars', 'menu', 1, '2024-04-17 12:22:12', '2024-04-17 12:22:12');
INSERT INTO `admin_menus` VALUES (7, 1, 6, '脚手架', 'scaffold', 'fa-keyboard-o', 'scaffold', 1, '2024-04-27 10:39:40', '2024-04-27 10:39:40');
INSERT INTO `admin_menus` VALUES (14, 1, 0, '系统工具', 'project', 'fa-anchor', '', 100, '2024-06-11 18:45:10', '2024-11-30 21:31:05');
INSERT INTO `admin_menus` VALUES (15, 1, 14, '脚手架', 'scaffold', 'fa-keyboard-o', 'scaffold', 1, '2024-06-11 18:45:37', '2024-11-30 21:33:51');

-- ----------------------------
-- Table structure for admin_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_permissions`;
CREATE TABLE `admin_role_permissions`  (
                                           `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                           `role_id` int NOT NULL DEFAULT 0,
                                           `permission_id` int NOT NULL DEFAULT 0,
                                           `created_at` timestamp NULL DEFAULT NULL,
                                           `updated_at` timestamp NULL DEFAULT NULL,
                                           PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_role_permissions
-- ----------------------------
INSERT INTO `admin_role_permissions` VALUES (7, 1, 1, '2024-12-01 13:47:46', '2024-12-01 13:47:46');
INSERT INTO `admin_role_permissions` VALUES (8, 1, 2, '2024-12-01 13:47:46', '2024-12-01 13:47:46');
INSERT INTO `admin_role_permissions` VALUES (9, 1, 3, '2024-12-01 13:47:46', '2024-12-01 13:47:46');
INSERT INTO `admin_role_permissions` VALUES (10, 1, 4, '2024-12-01 13:47:46', '2024-12-01 13:47:46');
INSERT INTO `admin_role_permissions` VALUES (11, 1, 5, '2024-12-01 13:47:46', '2024-12-01 13:47:46');
INSERT INTO `admin_role_permissions` VALUES (12, 1, 14, '2024-12-01 13:47:46', '2024-12-01 13:47:46');
INSERT INTO `admin_role_permissions` VALUES (13, 1, 15, '2024-12-01 13:47:46', '2024-12-01 13:47:46');

-- ----------------------------
-- Table structure for admin_role_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_users`;
CREATE TABLE `admin_role_users`  (
                                     `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                     `role_id` int NOT NULL DEFAULT 0,
                                     `user_id` int NOT NULL DEFAULT 0,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL,
                                     PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_role_users
-- ----------------------------
INSERT INTO `admin_role_users` VALUES (6, 1, 1, '2024-12-01 13:47:57', '2024-12-01 13:47:57');

-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles`  (
                                `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES (1, '超级管理员', 'admin', '2024-04-19 13:24:43', '2024-12-01 13:47:46');

-- ----------------------------
-- Table structure for admin_stats
-- ----------------------------
DROP TABLE IF EXISTS `admin_stats`;
CREATE TABLE `admin_stats`  (
                                `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                `uri` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_stats
-- ----------------------------

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users`  (
                                `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
                                `username` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `password` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `salt` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `avatar` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES (1, 'admin', 'e7cebef0d9206b446823f7ce09d8cb02', 'lhI5TZdZ', '超级管理员', '/uploads/images/CmK4gs0njp.png', '2023-05-19 09:17:43', '2024-12-01 13:47:57');

SET FOREIGN_KEY_CHECKS = 1;
