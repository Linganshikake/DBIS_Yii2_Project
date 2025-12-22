/*
 Navicat Premium Data Transfer

 Source Server         : 0
 Source Server Type    : MySQL
 Source Server Version : 80019 (8.0.19)
 Source Host           : localhost:3306
 Source Schema         : yii2_team_project

 Target Server Type    : MySQL
 Target Server Version : 80019 (8.0.19)
 File Encoding         : 65001

 Date: 22/12/2025 18:33:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for comment_likes
-- ----------------------------
DROP TABLE IF EXISTS `comment_likes`;
CREATE TABLE `comment_likes`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `comment_id` int NOT NULL COMMENT '评论ID',
  `user_id` int NOT NULL COMMENT '点赞用户ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_comment_user`(`comment_id` ASC, `user_id` ASC) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `fk_like_comment` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_like_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '评论点赞记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of comment_likes
-- ----------------------------

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `user_id` int NOT NULL COMMENT '用户ID',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '评论内容',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `like_count` int NOT NULL DEFAULT 0 COMMENT '点赞数',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_created_at`(`created_at` DESC) USING BTREE,
  CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '评论广场表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of comments
-- ----------------------------

-- ----------------------------
-- Table structure for company
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '企业ID',
  `team_id` int NOT NULL COMMENT '关联队伍ID',
  `e_mail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '企业邮箱',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '队伍Logo图片',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  `web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '企业网站',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_team_id`(`team_id` ASC) USING BTREE,
  CONSTRAINT `fk_company_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '企业信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of company
-- ----------------------------
INSERT INTO `company` VALUES (1, 1, 'pr_beastx@japanet.co.jp', 'BEAST.png', 1, 'https://www.bs10.jp');
INSERT INTO `company` VALUES (2, 2, 'exfurinkazan@gmail.com', 'EX.png', 1, 'https://www.tv-asahi.co.jp');
INSERT INTO `company` VALUES (3, 3, ' https://wwws.kadokawa.co.jp/support/sakuraknights/', 'SAKURA.png', 1, 'https://www.kadokawa.co.jp');
INSERT INTO `company` VALUES (4, 4, 'konamimfcpress@konami.com', 'KONAMI.png', 1, 'https://www.konami.com/arcadegames/corporate/ja');
INSERT INTO `company` VALUES (5, 5, 'teamraiden@dentsu.co.jp', 'RAIDEN.png', 1, 'https://www.dentsu.co.jp');
INSERT INTO `company` VALUES (6, 6, ' https://www.unext-pirates.jp/contact', 'U-NEXT.png', 1, 'https://video.unext.jp');
INSERT INTO `company` VALUES (7, 7, 'https://www.segasammy.co.jp/ja/faq/（最下部「お問い合わせはこちら」）', 'PHOENIX.png', 1, 'https://www.segasammy.co.jp/ja');
INSERT INTO `company` VALUES (8, 9, ' Shibuya_Abemas@cyberagent.co.jp', 'ABEMAS.png', 1, 'https://www.cyberagent.co.jp');
INSERT INTO `company` VALUES (9, 10, ' drivens@hakuhodody-media.co.jp', 'DRIVENS.png', 1, 'https://www.hakuhodo.co.jp');
INSERT INTO `company` VALUES (10, 17, ' earthjets@earth.jp', 'JETS.png', 1, 'https://www.earth.jp');

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration`  (
  `version` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apply_time` int NULL DEFAULT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', 1766052054);
INSERT INTO `migration` VALUES ('m130524_201442_init', 1766052060);
INSERT INTO `migration` VALUES ('m190124_110200_add_verification_token_column_to_user_table', 1766052060);

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '新闻ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '新闻标题',
  `publish_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '发布时间',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '新闻正文',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '新闻封面图',
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '正文图片JSON数组，如[\"img1.jpg\",\"img2.jpg\"]',
  `view_count` int NOT NULL DEFAULT 0 COMMENT '浏览次数',
  `is_hot` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否热门 1:是 0:否',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  `created_at` int NULL DEFAULT NULL COMMENT '创建时间戳',
  `updated_at` int NULL DEFAULT NULL COMMENT '更新时间戳',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_publish_time`(`publish_time` DESC) USING BTREE,
  INDEX `idx_is_hot`(`is_hot` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '联赛新闻表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of news
-- ----------------------------
INSERT INTO `news` VALUES (1, 'M联赛2025-26 Premium Night 2026年2月6日开办决定', '2025-11-16 10:00:00', '一般社团法人M联赛机构（本社：东京都港区、代表理事：藤田晋）宣布，「M联赛2025-26 Premium Night」将于Kanadevia Hall（金辉的演厅）举办。这是M联赛历史上首次在大型音乐厅举办的特别活动，届时将有精彩的表演和比赛。', 'NEWS1_COVER.png', NULL, 1522, 1, 1, NULL, NULL);
INSERT INTO `news` VALUES (2, 'M联赛2025-26赛季「复制品球衣」发售开始', '2025-11-12 14:30:00', '2025年11月12日（水）起，「M联赛2025-26赛季」（以下简称M联赛2025-26）的复制品球衣正式发售。今赛季的球衣采用了全新设计，展现了各队伍的独特风格。', 'NEWS2_COVER.png', NULL, 982, 1, 1, NULL, NULL);
INSERT INTO `news` VALUES (3, 'M.LEAGUE OFFICIAL SHOP 2025 POP UP STORE 开办决定', '2025-10-01 09:00:00', 'M.LEAGUE OFFICIAL SHOP（M联赛官方商店）的POP UP STORE今赛季也将开办。第一波活动将于10月18日（土）至10月31日（金）在新潟的中央区「花园1-1 BRACE1 1层」举办。', 'NEWS3_COVER.jpg', NULL, 750, 1, 1, NULL, NULL);

-- ----------------------------
-- Table structure for organizations
-- ----------------------------
DROP TABLE IF EXISTS `organizations`;
CREATE TABLE `organizations`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '团体ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '团体名称',
  `top_title_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '该团体的最高头衔名称',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '职业麻将团体' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of organizations
-- ----------------------------
INSERT INTO `organizations` VALUES (1, 'RMU', '令昭位', 1);
INSERT INTO `organizations` VALUES (2, '日本职业麻将协会', '雀王位', 1);
INSERT INTO `organizations` VALUES (3, '日本职业麻将联盟', '凤凰位', 1);
INSERT INTO `organizations` VALUES (4, '最高位战日本职业麻将协会', '最高位', 1);
INSERT INTO `organizations` VALUES (5, '麻将联合μ', '将王位', 1);

-- ----------------------------
-- Table structure for player_season_stats
-- ----------------------------
DROP TABLE IF EXISTS `player_season_stats`;
CREATE TABLE `player_season_stats`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `player_id` int NOT NULL COMMENT '选手ID',
  `season_id` int NOT NULL COMMENT '赛季ID',
  `team_id` int NOT NULL COMMENT '该赛季所属队伍ID (考虑转会情况)',
  `games_count` int NULL DEFAULT 0 COMMENT '试合数',
  `total_score` decimal(10, 1) NULL DEFAULT 0.0 COMMENT '通算得点',
  `rank_1_count` int NULL DEFAULT 0 COMMENT '1位次数',
  `rank_2_count` int NULL DEFAULT 0 COMMENT '2位次数',
  `rank_3_count` int NULL DEFAULT 0 COMMENT '3位次数',
  `rank_4_count` int NULL DEFAULT 0 COMMENT '4位次数',
  `max_score` int NULL DEFAULT 0 COMMENT '单局最高打点',
  `avg_rank` decimal(4, 2) NULL DEFAULT 0.00 COMMENT '平均顺位',
  `top_rate` decimal(5, 2) NULL DEFAULT 0.00 COMMENT '一位率',
  `last_avoid_rate` decimal(5, 2) NULL DEFAULT 0.00 COMMENT '避四率',
  `display_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_player_season`(`player_id` ASC, `season_id` ASC) USING BTREE,
  INDEX `fk_stats_season`(`season_id` ASC) USING BTREE,
  INDEX `fk_stats_team`(`team_id` ASC) USING BTREE,
  CONSTRAINT `fk_stats_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_stats_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_stats_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 64 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '选手分赛季成绩统计' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of player_season_stats
-- ----------------------------
INSERT INTO `player_season_stats` VALUES (1, 1, 1, 9, 25, -427.2, 4, 2, 11, 8, 46400, 2.92, 0.16, 0.68, 1);
INSERT INTO `player_season_stats` VALUES (2, 2, 1, 2, 25, -325.9, 3, 7, 6, 9, 54800, 2.84, 0.12, 0.64, 1);
INSERT INTO `player_season_stats` VALUES (3, 6, 1, 6, 38, 297.7, 11, 11, 10, 6, 73000, 2.29, 0.29, 0.84, 1);
INSERT INTO `player_season_stats` VALUES (4, 7, 1, 3, 33, 72.5, 10, 5, 11, 7, 67400, 2.45, 0.30, 0.79, 1);
INSERT INTO `player_season_stats` VALUES (5, 8, 1, 9, 27, -562.3, 2, 8, 5, 12, 38300, 3.00, 0.07, 0.56, 1);
INSERT INTO `player_season_stats` VALUES (6, 9, 1, 7, 30, -271.7, 6, 7, 7, 10, 61600, 2.70, 0.20, 0.67, 1);
INSERT INTO `player_season_stats` VALUES (7, 10, 1, 3, 27, 76.7, 7, 8, 6, 6, 49100, 2.41, 0.26, 0.78, 1);
INSERT INTO `player_season_stats` VALUES (8, 11, 1, 1, 20, -575.4, 2, 2, 5, 11, 50100, 3.25, 0.10, 0.45, 1);
INSERT INTO `player_season_stats` VALUES (9, 12, 1, 2, 28, 37.9, 6, 8, 9, 5, 54800, 2.46, 0.21, 0.82, 1);
INSERT INTO `player_season_stats` VALUES (10, 13, 1, 2, 16, -221.2, 2, 4, 3, 7, 78500, 2.94, 0.13, 0.56, 1);
INSERT INTO `player_season_stats` VALUES (11, 14, 1, 4, 29, 61.2, 9, 7, 4, 9, 71900, 2.45, 0.31, 0.69, 1);
INSERT INTO `player_season_stats` VALUES (12, 15, 1, 4, 38, 437.8, 12, 11, 10, 5, 62600, 2.21, 0.32, 0.87, 1);
INSERT INTO `player_season_stats` VALUES (13, 16, 1, 3, 19, -56.6, 4, 4, 8, 3, 65200, 2.53, 0.21, 0.84, 1);
INSERT INTO `player_season_stats` VALUES (14, 17, 1, 3, 17, -506.5, 1, 3, 3, 10, 38400, 3.29, 0.06, 0.41, 1);
INSERT INTO `player_season_stats` VALUES (15, 18, 1, 5, 43, 276.2, 13, 9, 14, 7, 58100, 2.35, 0.30, 0.84, 1);
INSERT INTO `player_season_stats` VALUES (16, 19, 1, 4, 27, -178.9, 6, 5, 8, 8, 56100, 2.67, 0.22, 0.70, 1);
INSERT INTO `player_season_stats` VALUES (17, 20, 1, 5, 29, 129.9, 9, 6, 8, 6, 59900, 2.38, 0.31, 0.79, 1);
INSERT INTO `player_season_stats` VALUES (18, 21, 1, 1, 24, -402.2, 3, 4, 9, 8, 47500, 2.92, 0.13, 0.67, 1);
INSERT INTO `player_season_stats` VALUES (19, 22, 1, 9, 41, 368.8, 13, 9, 13, 6, 65900, 2.29, 0.32, 0.85, 1);
INSERT INTO `player_season_stats` VALUES (20, 23, 1, 2, 27, -242.1, 7, 4, 7, 9, 58600, 2.67, 0.26, 0.67, 1);
INSERT INTO `player_season_stats` VALUES (21, 24, 1, 1, 23, -196.0, 4, 6, 6, 7, 65200, 2.70, 0.17, 0.70, 1);
INSERT INTO `player_season_stats` VALUES (22, 25, 1, 1, 29, 50.0, 8, 6, 6, 9, 91300, 2.55, 0.28, 0.69, 1);
INSERT INTO `player_season_stats` VALUES (23, 26, 1, 4, 22, -300.6, 2, 7, 6, 7, 41300, 2.82, 0.09, 0.68, 1);
INSERT INTO `player_season_stats` VALUES (24, 27, 1, 5, 29, 130.7, 10, 5, 7, 7, 52400, 2.38, 0.34, 0.76, 1);
INSERT INTO `player_season_stats` VALUES (25, 28, 1, 10, 37, 359.0, 12, 10, 7, 8, 75200, 2.30, 0.32, 0.78, 1);
INSERT INTO `player_season_stats` VALUES (26, 29, 1, 9, 23, -77.8, 4, 6, 10, 3, 60300, 2.52, 0.17, 0.87, 1);
INSERT INTO `player_season_stats` VALUES (27, 30, 1, 10, 23, 331.0, 9, 6, 4, 4, 47100, 2.13, 0.39, 0.83, 1);
INSERT INTO `player_season_stats` VALUES (28, 31, 1, 10, 36, -10.5, 9, 11, 9, 7, 44400, 2.39, 0.25, 0.81, 1);
INSERT INTO `player_season_stats` VALUES (29, 32, 1, 6, 32, 89.7, 9, 9, 6, 8, 62400, 2.41, 0.28, 0.75, 1);
INSERT INTO `player_season_stats` VALUES (30, 33, 1, 7, 37, 349.6, 10, 12, 12, 3, 59400, 2.22, 0.27, 0.92, 1);
INSERT INTO `player_season_stats` VALUES (31, 34, 1, 7, 27, -108.8, 5, 8, 4, 10, 85700, 2.70, 0.19, 0.63, 1);
INSERT INTO `player_season_stats` VALUES (32, 35, 1, 7, 38, 811.6, 15, 13, 6, 4, 88800, 1.97, 0.39, 0.89, 1);
INSERT INTO `player_season_stats` VALUES (33, 36, 1, 6, 34, 603.6, 13, 8, 9, 4, 81900, 2.12, 0.38, 0.88, 1);
INSERT INTO `player_season_stats` VALUES (34, 37, 1, 10, 36, 528.5, 14, 9, 4, 9, 86300, 2.22, 0.39, 0.75, 1);
INSERT INTO `player_season_stats` VALUES (35, 38, 1, 6, 28, -128.5, 6, 9, 4, 9, 45400, 2.57, 0.21, 0.68, 1);
INSERT INTO `player_season_stats` VALUES (36, 39, 1, 5, 31, -360.2, 3, 12, 6, 10, 47900, 2.74, 0.10, 0.68, 1);

-- ----------------------------
-- Table structure for players
-- ----------------------------
DROP TABLE IF EXISTS `players`;
CREATE TABLE `players`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '选手ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '真实姓名',
  `register_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '注册名/比赛用名',
  `gender` enum('男','女') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '性别',
  `nickname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '绰号',
  `team_id` int NULL DEFAULT NULL COMMENT '当前所属队伍ID (外键)',
  `org_id` int NULL DEFAULT NULL COMMENT '所属团体ID (外键)',
  `org_rank` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '团体段位/等级',
  `join_date` date NULL DEFAULT NULL COMMENT '加入M联赛时间',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选手照片',
  `intro_video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选手介绍短视频',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_team_id`(`team_id` ASC) USING BTREE,
  INDEX `idx_org_id`(`org_id` ASC) USING BTREE,
  CONSTRAINT `fk_player_org` FOREIGN KEY (`org_id`) REFERENCES `organizations` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_player_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 70 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '选手基础信息表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of players
-- ----------------------------
INSERT INTO `players` VALUES (1, '多井隆晴', '多井', '男', '最速最强', 9, 1, 'A1', NULL, 1, 'player_1766070409_609.jpg', 'pv_1766151517_199.mp4', 'cover_1766151517_171.png');
INSERT INTO `players` VALUES (2, '松之濑隆弥', '松之濑', '男', '纤细的超巨炮', 2, 1, 'A1', NULL, 1, 'player_1766070422_367.jpg', 'pv_1766151473_333.mp4', 'cover_1766151473_390.png');
INSERT INTO `players` VALUES (6, '仲林圭', '仲林', '男', '圭圭', 6, 2, 'A1', NULL, 1, 'player_1766070433_254.jpg', 'pv_1766153044_973.mp4', 'cover_1766153044_128.jpg');
INSERT INTO `players` VALUES (7, '堀慎吾', '堀', '男', '小天才', 3, 2, 'A1', NULL, 1, 'player_1766070448_628.jpg', 'Hori.mp4', 'cover_1766114313_568.jpg');
INSERT INTO `players` VALUES (8, '松本吉弘', '松本', '男', '少爷', 9, 2, 'A1', NULL, 1, 'player_1766070491_921.jpg', 'pv_1766143005_141.mp4', 'cover_1766143005_547.jpg');
INSERT INTO `players` VALUES (9, '浅井堂岐', '浅井', '男', '黑皮内川', 7, 2, 'A1', NULL, 1, 'player_1766070531_965.jpg', 'pv_1766152132_700.mp4', 'cover_1766152132_333.png');
INSERT INTO `players` VALUES (10, '涩川难波', '涩川', '男', '魔神', 3, 2, 'A1', NULL, 1, 'player_1766070547_346.jpg', 'pv_1766152366_295.mp4', 'cover_1766152366_242.png');
INSERT INTO `players` VALUES (11, '中田花奈', '花奈', '女', '花奶', 1, 3, '', NULL, 1, 'player_1766070569_638.jpg', 'pv_1766151015_603.mp4', 'cover_1766151015_623.jpg');
INSERT INTO `players` VALUES (12, '二阶堂亚树', '亚树', '女', '桌上的舞姬', 2, 3, 'B1', NULL, 1, 'player_1766070580_391.jpg', 'pv_1766152034_933.mp4', 'cover_1766152034_311.png');
INSERT INTO `players` VALUES (13, '二阶堂瑠美', '瑠美', '女', '天衣无缝', 2, 3, 'B2', NULL, 1, 'player_1766070593_473.jpg', 'pv_1766163093_290.mp4', 'cover_1766163093_987.jpg');
INSERT INTO `players` VALUES (14, '伊达朱里莎', '伊达', '女', '赤红的女战士', 4, 3, 'C3', NULL, 1, 'player_1766070605_910.jpg', 'pv_1766151106_293.mp4', 'cover_1766151106_247.jpg');
INSERT INTO `players` VALUES (15, '佐佐木寿人', '佐佐木', '男', '魔王', 4, 3, 'A1', NULL, 1, 'player_1766070621_324.jpg', 'pv_1766150712_855.mp4', 'cover_1766150712_915.jpg');
INSERT INTO `players` VALUES (16, '内川幸太郎', '内川', '男', '手顺大师', 3, 3, 'A1', NULL, 1, 'player_1766070636_494.jpg', 'pv_1766151556_836.mp4', 'cover_1766151556_901.png');
INSERT INTO `players` VALUES (17, '冈田纱佳', '冈田', '女', '模特', 3, 3, 'C3', NULL, 1, 'player_1766070649_417.jpg', 'pv_1766150957_402.mp4', 'cover_1766150957_807.jpg');
INSERT INTO `players` VALUES (18, '本田朋广', '本田', '男', '北麓的役满王子', 5, 3, 'C1', NULL, 1, 'player_1766070660_323.jpg', 'pv_1766152275_308.mp4', 'cover_1766152275_727.png');
INSERT INTO `players` VALUES (19, '泷泽和典', '泷泽', '男', '龙哥', 4, 3, 'B1', NULL, 1, 'player_1766070686_870.jpg', 'pv_1766151601_478.mp4', 'cover_1766151601_314.jpg');
INSERT INTO `players` VALUES (20, '濑户熊直树', '濑户熊', '男', '桌上的暴君', 5, 3, 'A2', NULL, 1, 'player_1766070699_269.jpg', 'pv_1766151254_632.mp4', 'cover_1766151254_134.png');
INSERT INTO `players` VALUES (21, '猿川真寿', '猿川', '男', '应猿队长', 1, 3, 'B1', NULL, 1, 'player_1766070713_590.jpg', 'pv_1766152324_535.mp4', 'cover_1766152324_909.png');
INSERT INTO `players` VALUES (22, '白鸟翔', '白鸟', '男', '冥府的先导者', 9, 3, 'A1', NULL, 1, 'player_1766070724_654.jpg', 'pv_1766151213_535.mp4', 'cover_1766151213_208.jpg');
INSERT INTO `players` VALUES (23, '胜又健志', '胜又', '男', '麻将IQ220', 2, 3, 'A1', NULL, 1, 'player_1766070734_811.jpg', 'pv_1766151987_462.mp4', 'cover_1766151987_212.png');
INSERT INTO `players` VALUES (24, '菅原千瑛', '菅原', '女', '一姬', 1, 3, 'C3', NULL, 1, 'player_1766070770_201.jpg', 'pv_1766152240_446.mp4', 'cover_1766152240_609.png');
INSERT INTO `players` VALUES (25, '铃木大介', '大介', '男', '将棋的王者', 1, 3, 'B1', NULL, 1, 'player_1766070785_569.jpg', 'pv_1766151300_632.mp4', 'cover_1766151300_979.png');
INSERT INTO `players` VALUES (26, '高宫茉莉', '高宫', '女', '高老师', 4, 3, 'D1', NULL, 1, 'player_1766070796_319.jpg', 'pv_1766151824_635.mp4', 'cover_1766151824_944.jpg');
INSERT INTO `players` VALUES (27, '黑泽咲', '黑泽', '女', '战斗的维纳斯', 5, 3, 'A2', NULL, 1, 'player_1766070809_451.jpg', 'pv_1766150822_127.mp4', 'cover_1766150822_526.jpg');
INSERT INTO `players` VALUES (28, '园田贤', '园田', '男', '桌上的魔术师', 10, 4, 'A1', NULL, 1, 'player_1766070831_848.jpg', 'pv_1766150892_575.mp4', 'cover_1766150892_594.jpg');
INSERT INTO `players` VALUES (29, '日向蓝子', '蓝子', '女', '蓝子妈妈', 9, 4, 'B2', NULL, 1, 'player_1766070845_101.jpg', 'pv_1766151941_924.mp4', 'cover_1766151941_294.png');
INSERT INTO `players` VALUES (30, '浅见真纪', '浅见', '女', '妈咪', 10, 4, 'C3', NULL, 1, 'player_1766070866_761.jpg', 'pv_1766151664_560.mp4', 'cover_1766151664_115.jpg');
INSERT INTO `players` VALUES (31, '渡边太', '渡边', '男', '猪猪太', 10, 4, 'A2', NULL, 1, 'player_1766070891_936.jpg', 'pv_1766152176_162.mp4', 'cover_1766152176_610.jpg');
INSERT INTO `players` VALUES (32, '瑞原明奈', '瑞原', '女', '太太', 6, 4, '', NULL, 1, 'player_1766070907_634.jpg', 'pv_1766151055_582.mp4', 'cover_1766151055_104.jpg');
INSERT INTO `players` VALUES (33, '竹内元太', '元太', '男', '旺仔', 7, 4, 'A1', NULL, 1, 'player_1766070919_624.jpg', 'pv_1766151431_140.mp4', 'cover_1766151431_258.png');
INSERT INTO `players` VALUES (34, '茅森早香', '茅森', '女', '打点女王', 7, 4, '', NULL, 1, 'player_1766070959_839.jpg', 'pv_1766163137_951.mp4', 'cover_1766163137_833.jpg');
INSERT INTO `players` VALUES (35, '醍醐大', '醍醐', '男', '大老师', 7, 4, 'A2', NULL, 1, 'player_1766069378_401.jpg', 'pv_1766151738_468.mp4', 'cover_1766151738_585.png');
INSERT INTO `players` VALUES (36, '铃木优', '优', '男', '战斗民族', 6, 4, 'A1', NULL, 1, 'player_1766070976_371.jpg', 'pv_1766152086_134.mp4', 'cover_1766152086_138.png');
INSERT INTO `players` VALUES (37, '铃木太郎', '太郎', '男', '宙斯', 10, 4, 'A1', NULL, 1, 'player_1766071030_421.jpg', 'pv_1766151912_438.mp4', 'cover_1766151912_876.png');
INSERT INTO `players` VALUES (38, '小林刚', '小林', '男', '麻将机器人', 6, 5, 'μ2', NULL, 1, 'player_1766071012_118.jpg', 'pv_1766151785_152.mp4', 'cover_1766151785_903.png');
INSERT INTO `players` VALUES (39, '萩原圣人', '萩原', '男', '雪原的求道者', 5, NULL, '', NULL, 1, 'player_1766071000_345.jpg', 'pv_1766163168_893.mp4', 'cover_1766163168_799.png');
INSERT INTO `players` VALUES (64, '石井一马', '一马', '男', '小马哥', 17, 4, '最高位', '2025-09-01', 1, 'player_1766344518_341.jpg', 'pv_1766344751_424.mp4', 'cover_1766344751_499.jpg');
INSERT INTO `players` VALUES (65, 'HIRO柴田', '柴田', '男', '红杏', 17, 3, 'A1', '2025-09-01', 1, 'player_1766344530_646.jpg', 'pv_1766345252_955.mp4', 'cover_1766345252_895.jpg');
INSERT INTO `players` VALUES (67, '220', '220', '男', '', 17, 1, 'A2', NULL, 0, NULL, NULL, NULL);
INSERT INTO `players` VALUES (68, '三浦智博', '三浦', '男', '无冕之王', 17, 3, 'A2', NULL, 1, 'player_1766344506_799.jpg', 'pv_1766345226_252.mp4', 'cover_1766345226_799.jpg');
INSERT INTO `players` VALUES (69, '逢川惠梦', '逢川', '女', '爪姐', 17, 4, '雀王', NULL, 1, 'player_1766344475_313.jpg', 'pv_1766345174_673.mp4', 'cover_1766345174_271.jpg');

-- ----------------------------
-- Table structure for schedule
-- ----------------------------
DROP TABLE IF EXISTS `schedule`;
CREATE TABLE `schedule`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '日程ID',
  `match_date` date NOT NULL COMMENT '比赛日期',
  `day_of_week` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '星期几(中文)',
  `team_id1` int NOT NULL COMMENT '参赛队伍1',
  `team_id2` int NOT NULL COMMENT '参赛队伍2',
  `team_id3` int NOT NULL COMMENT '参赛队伍3',
  `team_id4` int NOT NULL COMMENT '参赛队伍4',
  `top_team_id` int NULL DEFAULT NULL COMMENT '首位队伍ID，NULL表示未开局',
  `season_id` int NOT NULL COMMENT '赛季ID',
  `match_status` tinyint NOT NULL DEFAULT 0 COMMENT '比赛状态 0:未开始 1:进行中 2:已结束',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_match_date`(`match_date` ASC) USING BTREE,
  INDEX `idx_season`(`season_id` ASC) USING BTREE,
  INDEX `fk_schedule_team1`(`team_id1` ASC) USING BTREE,
  INDEX `fk_schedule_team2`(`team_id2` ASC) USING BTREE,
  INDEX `fk_schedule_team3`(`team_id3` ASC) USING BTREE,
  INDEX `fk_schedule_team4`(`team_id4` ASC) USING BTREE,
  CONSTRAINT `fk_schedule_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_schedule_team1` FOREIGN KEY (`team_id1`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_schedule_team2` FOREIGN KEY (`team_id2`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_schedule_team3` FOREIGN KEY (`team_id3`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_schedule_team4` FOREIGN KEY (`team_id4`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 67 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '比赛日程表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of schedule
-- ----------------------------
INSERT INTO `schedule` VALUES (1, '2025-12-22', '星期一', 10, 3, 5, 6, NULL, 1, 1, 1);
INSERT INTO `schedule` VALUES (2, '2025-12-23', '星期二', 17, 3, 4, 1, NULL, 1, 0, 1);
INSERT INTO `schedule` VALUES (3, '2025-12-25', '星期四', 17, 2, 9, 6, NULL, 1, 0, 1);
INSERT INTO `schedule` VALUES (4, '2025-12-25', '星期四', 10, 4, 7, 5, NULL, 1, 0, 1);
INSERT INTO `schedule` VALUES (5, '2026-01-06', '星期一', 2, 3, 6, 9, NULL, 1, 0, 1);
INSERT INTO `schedule` VALUES (6, '2026-01-07', '星期二', 1, 4, 7, 10, NULL, 1, 0, 1);
INSERT INTO `schedule` VALUES (7, '2025-09-15', '星期一', 4, 7, 1, 6, 6, 1, 2, 1);
INSERT INTO `schedule` VALUES (8, '2025-09-16', '星期二', 2, 3, 4, 9, 3, 1, 2, 1);
INSERT INTO `schedule` VALUES (9, '2025-09-18', '星期四', 7, 5, 1, 6, 5, 1, 2, 1);
INSERT INTO `schedule` VALUES (10, '2025-09-19', '星期五', 4, 2, 9, 5, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (11, '2025-09-22', '星期一', 4, 9, 7, 6, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (12, '2025-09-23', '星期二', 7, 5, 1, 6, 5, 1, 2, 1);
INSERT INTO `schedule` VALUES (13, '2025-09-25', '星期四', 17, 7, 4, 5, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (14, '2025-09-26', '星期五', 9, 6, 7, 3, 6, 1, 2, 1);
INSERT INTO `schedule` VALUES (15, '2025-09-29', '星期一', 7, 5, 1, 6, 5, 1, 2, 1);
INSERT INTO `schedule` VALUES (16, '2025-09-30', '星期二', 2, 7, 10, 3, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (17, '2025-10-02', '星期四', 5, 9, 6, 4, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (18, '2025-10-03', '星期五', 4, 9, 7, 6, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (19, '2025-10-06', '星期一', 5, 7, 2, 3, 3, 1, 2, 1);
INSERT INTO `schedule` VALUES (20, '2025-10-07', '星期二', 4, 9, 1, 6, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (21, '2025-10-09', '星期四', 5, 2, 7, 6, 6, 1, 2, 1);
INSERT INTO `schedule` VALUES (22, '2025-10-10', '星期五', 7, 4, 5, 1, 4, 1, 2, 1);
INSERT INTO `schedule` VALUES (23, '2025-10-13', '星期一', 4, 9, 1, 6, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (24, '2025-10-14', '星期二', 17, 7, 3, 6, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (25, '2025-10-16', '星期四', 4, 9, 5, 1, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (26, '2025-10-17', '星期五', 7, 4, 9, 6, 4, 1, 2, 1);
INSERT INTO `schedule` VALUES (27, '2025-10-20', '星期一', 4, 7, 3, 2, 3, 1, 2, 1);
INSERT INTO `schedule` VALUES (28, '2025-10-21', '星期二', 2, 4, 5, 9, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (29, '2025-10-23', '星期四', 4, 7, 5, 6, 4, 1, 2, 1);
INSERT INTO `schedule` VALUES (30, '2025-10-24', '星期五', 7, 2, 1, 3, 1, 1, 2, 1);
INSERT INTO `schedule` VALUES (31, '2025-10-27', '星期一', 2, 4, 9, 5, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (32, '2025-10-28', '星期二', 17, 7, 1, 6, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (33, '2025-10-30', '星期四', 2, 3, 5, 1, 1, 1, 2, 1);
INSERT INTO `schedule` VALUES (34, '2025-10-31', '星期五', 2, 4, 5, 6, 6, 1, 2, 1);
INSERT INTO `schedule` VALUES (35, '2025-11-03', '星期一', 17, 7, 1, 6, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (36, '2025-11-04', '星期二', 2, 3, 7, 5, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (37, '2025-11-06', '星期四', 4, 3, 17, 6, 4, 1, 2, 1);
INSERT INTO `schedule` VALUES (38, '2025-11-07', '星期五', 4, 10, 9, 7, 10, 1, 2, 1);
INSERT INTO `schedule` VALUES (39, '2025-11-10', '星期一', 2, 3, 7, 5, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (40, '2025-11-11', '星期二', 4, 9, 1, 6, 9, 1, 2, 1);
INSERT INTO `schedule` VALUES (41, '2025-11-13', '星期四', 4, 7, 5, 17, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (42, '2025-11-14', '星期五', 17, 7, 2, 9, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (43, '2025-11-17', '星期一', 4, 9, 1, 6, 6, 1, 2, 1);
INSERT INTO `schedule` VALUES (44, '2025-11-18', '星期二', 17, 7, 2, 4, 4, 1, 2, 1);
INSERT INTO `schedule` VALUES (45, '2025-11-20', '星期四', 2, 3, 7, 1, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (46, '2025-11-21', '星期五', 3, 4, 9, 1, 1, 1, 2, 1);
INSERT INTO `schedule` VALUES (47, '2025-11-24', '星期一', 17, 7, 2, 4, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (48, '2025-11-25', '星期二', 3, 9, 7, 5, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (49, '2025-11-27', '星期四', 2, 3, 1, 6, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (50, '2025-11-28', '星期五', 9, 7, 1, 6, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (51, '2025-12-01', '星期一', 3, 9, 7, 5, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (52, '2025-12-02', '星期二', 17, 4, 1, 6, 17, 1, 2, 1);
INSERT INTO `schedule` VALUES (53, '2025-12-04', '星期四', 3, 2, 17, 5, 2, 1, 2, 1);
INSERT INTO `schedule` VALUES (54, '2025-12-05', '星期五', 2, 9, 5, 6, 5, 1, 2, 1);
INSERT INTO `schedule` VALUES (55, '2025-12-08', '星期一', 17, 4, 1, 6, 6, 1, 2, 1);
INSERT INTO `schedule` VALUES (56, '2025-12-09', '星期二', 10, 2, 9, 7, 10, 1, 2, 1);
INSERT INTO `schedule` VALUES (57, '2025-12-11', '星期四', 7, 4, 5, 1, 1, 1, 2, 1);
INSERT INTO `schedule` VALUES (58, '2025-12-12', '星期五', 4, 3, 17, 5, 4, 1, 2, 1);
INSERT INTO `schedule` VALUES (59, '2025-12-15', '星期一', 7, 2, 9, 17, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (60, '2025-12-16', '星期二', 7, 3, 5, 6, 3, 1, 2, 1);
INSERT INTO `schedule` VALUES (61, '2025-12-18', '星期四', 4, 3, 9, 7, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (62, '2025-12-19', '星期五', 7, 2, 17, 1, 7, 1, 2, 1);
INSERT INTO `schedule` VALUES (65, '2025-12-26', '星期五', 3, 9, 7, 6, NULL, 1, 0, 1);
INSERT INTO `schedule` VALUES (66, '2025-12-26', '星期五', 10, 2, 5, 1, NULL, 1, 0, 1);

-- ----------------------------
-- Table structure for schedule_score
-- ----------------------------
DROP TABLE IF EXISTS `schedule_score`;
CREATE TABLE `schedule_score`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '成绩ID',
  `schedule_id` int NOT NULL COMMENT '日程ID',
  `game_number` tinyint NOT NULL DEFAULT 0 COMMENT '场次 0:第一场 1:第二场',
  `team_id1` int NULL DEFAULT NULL COMMENT '队伍1ID',
  `team_id2` int NULL DEFAULT NULL COMMENT '队伍2ID',
  `team_id3` int NULL DEFAULT NULL COMMENT '队伍3ID',
  `team_id4` int NULL DEFAULT NULL COMMENT '队伍4ID',
  `team1_player_id` int NULL DEFAULT NULL COMMENT '队伍1出战选手ID',
  `team2_player_id` int NULL DEFAULT NULL COMMENT '队伍2出战选手ID',
  `team3_player_id` int NULL DEFAULT NULL COMMENT '队伍3出战选手ID',
  `team4_player_id` int NULL DEFAULT NULL COMMENT '队伍4出战选手ID',
  `team1_score` decimal(10, 1) NOT NULL DEFAULT 0.0 COMMENT '队伍1得分',
  `team2_score` decimal(10, 1) NOT NULL DEFAULT 0.0 COMMENT '队伍2得分',
  `team3_score` decimal(10, 1) NOT NULL DEFAULT 0.0 COMMENT '队伍3得分',
  `team4_score` decimal(10, 1) NOT NULL DEFAULT 0.0 COMMENT '队伍4得分',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_schedule_game`(`schedule_id` ASC, `game_number` ASC) USING BTREE,
  INDEX `fk_score_player1`(`team1_player_id` ASC) USING BTREE,
  INDEX `fk_score_player2`(`team2_player_id` ASC) USING BTREE,
  INDEX `fk_score_player3`(`team3_player_id` ASC) USING BTREE,
  INDEX `fk_score_player4`(`team4_player_id` ASC) USING BTREE,
  CONSTRAINT `fk_score_player1` FOREIGN KEY (`team1_player_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_score_player2` FOREIGN KEY (`team2_player_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_score_player3` FOREIGN KEY (`team3_player_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_score_player4` FOREIGN KEY (`team4_player_id`) REFERENCES `players` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_score_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `schedule` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_score_sum` CHECK ((((`team1_score` + `team2_score`) + `team3_score`) + `team4_score`) = 0)
) ENGINE = InnoDB AUTO_INCREMENT = 113 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '比赛成绩表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of schedule_score
-- ----------------------------
INSERT INTO `schedule_score` VALUES (1, 7, 0, 10, 6, 17, 1, 28, 36, 64, 21, 54.9, 9.8, -15.9, -48.8, 1);
INSERT INTO `schedule_score` VALUES (2, 7, 1, 6, 1, 10, 17, 6, 24, 37, 68, 66.4, 6.1, -17.5, -55.0, 1);
INSERT INTO `schedule_score` VALUES (3, 8, 0, 3, 4, 2, 9, 16, 15, 12, 8, 76.0, 6.2, -23.8, -58.4, 1);
INSERT INTO `schedule_score` VALUES (4, 8, 1, 9, 3, 4, 2, 22, 17, 14, 2, 55.9, 11.7, -17.1, -50.5, 1);
INSERT INTO `schedule_score` VALUES (5, 9, 0, 5, 6, 1, 7, 39, 32, 24, 35, 58.5, 5.8, -15.9, -48.4, 1);
INSERT INTO `schedule_score` VALUES (6, 9, 1, 1, 5, 6, 7, 25, 18, 38, 9, 72.7, -0.6, -25.5, -46.6, 1);
INSERT INTO `schedule_score` VALUES (7, 10, 0, 2, 17, 5, 9, 13, 69, 20, 1, 62.8, 3.7, -16.8, -49.7, 1);
INSERT INTO `schedule_score` VALUES (8, 10, 1, 2, 9, 5, 17, 2, 22, 27, 65, 64.2, 14.0, -10.8, -67.4, 1);
INSERT INTO `schedule_score` VALUES (9, 11, 0, 6, 4, 9, 7, 32, 15, 29, 34, 54.6, 14.1, -16.7, -52.0, 1);
INSERT INTO `schedule_score` VALUES (10, 11, 1, 9, 4, 6, 7, 22, 26, 36, 33, 56.5, 8.6, -21.3, -43.8, 1);
INSERT INTO `schedule_score` VALUES (11, 12, 0, 7, 6, 1, 5, 35, 38, 21, 39, 55.9, 10.6, -21.4, -45.1, 1);
INSERT INTO `schedule_score` VALUES (12, 12, 1, 5, 1, 6, 7, 20, 11, 6, 33, 63.5, 6.9, -17.7, -52.7, 1);
INSERT INTO `schedule_score` VALUES (13, 13, 0, 17, 5, 10, 4, 64, 27, 30, 19, 54.8, 2.6, -17.6, -39.8, 1);
INSERT INTO `schedule_score` VALUES (14, 13, 1, 5, 17, 4, 10, 18, 65, 14, 31, 56.1, 6.6, -19.6, -43.1, 1);
INSERT INTO `schedule_score` VALUES (15, 14, 0, 17, 2, 4, 7, 69, 13, 19, 9, 62.6, 12.7, -25.7, -49.6, 1);
INSERT INTO `schedule_score` VALUES (16, 14, 1, 2, 7, 4, 17, 2, 35, 26, 68, 70.1, 9.0, -11.2, -67.9, 1);
INSERT INTO `schedule_score` VALUES (17, 15, 0, 5, 6, 1, 7, 18, 32, 11, 34, 51.3, 9.7, -15.3, -45.7, 1);
INSERT INTO `schedule_score` VALUES (18, 15, 1, 7, 5, 6, 1, 33, 20, 36, 25, 78.2, 3.6, -27.7, -54.1, 1);
INSERT INTO `schedule_score` VALUES (19, 16, 0, 2, 3, 10, 17, 13, 10, 31, 65, 59.1, 12.0, -22.7, -48.4, 1);
INSERT INTO `schedule_score` VALUES (20, 16, 1, 2, 10, 17, 3, 2, 28, 64, 7, 59.6, 17.2, -24.9, -51.9, 1);
INSERT INTO `schedule_score` VALUES (21, 17, 0, 6, 17, 9, 5, 32, 68, 22, 20, 74.3, 23.5, -36.3, -61.5, 1);
INSERT INTO `schedule_score` VALUES (22, 17, 1, 9, 17, 5, 6, 1, 69, 27, 38, 78.4, 9.6, -21.3, -66.7, 1);
INSERT INTO `schedule_score` VALUES (23, 18, 0, 10, 5, 3, 1, 30, 18, 17, 25, 69.5, 6.1, -21.9, -53.7, 1);
INSERT INTO `schedule_score` VALUES (24, 18, 1, 1, 5, 10, 3, 24, 18, 28, 16, 63.4, 6.6, -19.5, -50.5, 1);
INSERT INTO `schedule_score` VALUES (25, 19, 0, 2, 3, 10, 17, 23, 17, 37, 65, 70.2, 12.8, -29.2, -53.8, 1);
INSERT INTO `schedule_score` VALUES (26, 19, 1, 3, 17, 10, 2, 10, 69, 31, 13, 76.1, 17.7, -15.9, -77.9, 1);
INSERT INTO `schedule_score` VALUES (27, 20, 0, 4, 9, 1, 6, 19, 8, 21, 32, 53.6, 13.3, -18.5, -48.4, 1);
INSERT INTO `schedule_score` VALUES (28, 20, 1, 4, 9, 6, 1, 26, 1, 38, 25, 73.8, 2.0, -19.7, -56.1, 1);
INSERT INTO `schedule_score` VALUES (29, 21, 0, 10, 9, 3, 5, 28, 29, 10, 27, 54.4, 4.3, -17.0, -41.7, 1);
INSERT INTO `schedule_score` VALUES (30, 21, 1, 5, 10, 3, 9, 39, 37, 7, 22, 52.1, 5.6, -15.9, -41.8, 1);
INSERT INTO `schedule_score` VALUES (31, 22, 0, 4, 7, 10, 5, 15, 35, 31, 18, 62.7, 3.1, -20.2, -45.6, 1);
INSERT INTO `schedule_score` VALUES (32, 22, 1, 5, 4, 10, 7, 20, 14, 28, 34, 77.4, 7.7, -14.5, -70.6, 1);
INSERT INTO `schedule_score` VALUES (33, 23, 0, 6, 4, 1, 9, 36, 15, 24, 22, 59.8, 9.4, -23.2, -46.0, 1);
INSERT INTO `schedule_score` VALUES (34, 23, 1, 9, 1, 6, 4, 8, 21, 32, 26, 60.0, 10.6, -15.9, -54.7, 1);
INSERT INTO `schedule_score` VALUES (35, 24, 0, 10, 7, 17, 3, 30, 33, 65, 16, 59.0, 12.0, -22.2, -48.8, 1);
INSERT INTO `schedule_score` VALUES (36, 24, 1, 17, 3, 7, 10, 64, 17, 34, 37, 57.2, 9.7, -20.4, -46.5, 1);
INSERT INTO `schedule_score` VALUES (37, 25, 0, 6, 2, 7, 17, 32, 2, 33, 64, 53.2, 10.1, -19.1, -44.2, 1);
INSERT INTO `schedule_score` VALUES (38, 25, 1, 6, 7, 2, 17, 38, 9, 2, 68, 59.8, 13.1, -12.8, -60.1, 1);
INSERT INTO `schedule_score` VALUES (39, 26, 0, 4, 6, 10, 9, 19, 6, 31, 29, 56.6, 16.4, -7.8, -65.2, 1);
INSERT INTO `schedule_score` VALUES (40, 26, 1, 9, 10, 6, 4, 1, 37, 36, 15, 60.7, 6.9, -20.0, -47.6, 1);
INSERT INTO `schedule_score` VALUES (41, 27, 0, 17, 10, 3, 7, 64, 28, 17, 33, 49.9, 5.9, -15.7, -40.1, 1);
INSERT INTO `schedule_score` VALUES (42, 27, 1, 3, 10, 7, 17, 10, 31, 9, 64, 72.5, 23.5, -33.1, -62.9, 1);
INSERT INTO `schedule_score` VALUES (43, 28, 0, 2, 9, 5, 4, 12, 22, 20, 14, 73.6, 25.4, -30.7, -68.3, 1);
INSERT INTO `schedule_score` VALUES (44, 28, 1, 4, 2, 5, 9, 19, 23, 39, 1, 54.3, 11.5, -21.6, -44.2, 1);
INSERT INTO `schedule_score` VALUES (45, 29, 0, 4, 6, 7, 17, 19, 38, 33, 69, 62.0, 18.2, -25.3, -54.9, 1);
INSERT INTO `schedule_score` VALUES (46, 29, 1, 4, 17, 6, 7, 14, 64, 36, 35, 75.0, -2.8, -24.8, -47.4, 1);
INSERT INTO `schedule_score` VALUES (47, 30, 0, 1, 10, 2, 7, 11, 30, 2, 9, 61.2, 4.6, -19.2, -46.6, 1);
INSERT INTO `schedule_score` VALUES (48, 30, 1, 7, 10, 2, 1, 35, 31, 23, 24, 49.7, 6.3, -17.9, -38.1, 1);
INSERT INTO `schedule_score` VALUES (49, 31, 0, 4, 2, 9, 5, 14, 13, 29, 18, 61.4, 17.7, -15.7, -63.4, 1);
INSERT INTO `schedule_score` VALUES (50, 31, 1, 9, 2, 5, 4, 1, 23, 39, 15, 59.1, 8.7, -23.1, -44.7, 1);
INSERT INTO `schedule_score` VALUES (51, 32, 0, 17, 1, 10, 6, 65, 25, 37, 6, 65.6, 6.1, -17.3, -54.4, 1);
INSERT INTO `schedule_score` VALUES (52, 32, 1, 10, 1, 17, 6, 31, 11, 65, 38, 56.7, 5.5, -15.3, -46.9, 1);
INSERT INTO `schedule_score` VALUES (53, 33, 0, 9, 4, 10, 7, 8, 14, 30, 34, 56.0, 12.5, -16.8, -51.7, 1);
INSERT INTO `schedule_score` VALUES (54, 33, 1, 4, 7, 9, 10, 26, 35, 1, 28, 65.8, 21.3, -27.6, -59.5, 1);
INSERT INTO `schedule_score` VALUES (55, 34, 0, 6, 2, 4, 5, 32, 12, 15, 39, 60.3, 19.2, -27.9, -51.6, 1);
INSERT INTO `schedule_score` VALUES (56, 34, 1, 6, 5, 2, 4, 36, 18, 2, 19, 59.4, 14.0, -24.2, -49.2, 1);
INSERT INTO `schedule_score` VALUES (57, 35, 0, 1, 6, 17, 10, 11, 36, 69, 37, 81.9, 5.2, -31.2, -55.9, 1);
INSERT INTO `schedule_score` VALUES (58, 35, 1, 17, 10, 1, 6, 65, 28, 25, 32, 100.2, 3.5, -33.6, -70.1, 1);
INSERT INTO `schedule_score` VALUES (59, 36, 0, 2, 5, 3, 7, 13, 27, 17, 9, 87.1, -5.9, -29.5, -51.7, 1);
INSERT INTO `schedule_score` VALUES (60, 36, 1, 2, 7, 3, 5, 13, 33, 10, 39, 60.1, 5.0, -21.6, -43.5, 1);
INSERT INTO `schedule_score` VALUES (61, 37, 0, 4, 3, 17, 6, 26, 16, 68, 38, 61.5, 7.6, -22.6, -46.5, 1);
INSERT INTO `schedule_score` VALUES (62, 37, 1, 17, 4, 3, 6, 64, 15, 7, 6, 59.5, 11.9, -13.9, -57.5, 1);
INSERT INTO `schedule_score` VALUES (63, 38, 0, 9, 17, 10, 7, 8, 69, 37, 35, 65.8, 19.2, -13.4, -71.6, 1);
INSERT INTO `schedule_score` VALUES (64, 38, 1, 10, 7, 9, 17, 31, 9, 29, 65, 66.1, 13.2, -21.6, -57.7, 1);
INSERT INTO `schedule_score` VALUES (65, 39, 0, 2, 3, 5, 7, 12, 17, 39, 33, 59.1, 10.5, -23.7, -45.9, 1);
INSERT INTO `schedule_score` VALUES (66, 39, 1, 2, 7, 3, 5, 2, 9, 17, 18, 57.7, 13.0, -16.2, -54.5, 1);
INSERT INTO `schedule_score` VALUES (67, 40, 0, 9, 4, 6, 1, 22, 19, 32, 25, 55.8, 15.1, -22.6, -48.3, 1);
INSERT INTO `schedule_score` VALUES (68, 40, 1, 9, 4, 1, 6, 1, 26, 24, 6, 59.9, 17.7, -19.6, -58.0, 1);
INSERT INTO `schedule_score` VALUES (69, 41, 0, 7, 10, 5, 17, 35, 28, 18, 68, 53.3, 12.6, -14.4, -51.5, 1);
INSERT INTO `schedule_score` VALUES (70, 41, 1, 7, 17, 5, 10, 9, 65, 27, 37, 55.0, 11.6, -23.1, -43.5, 1);
INSERT INTO `schedule_score` VALUES (71, 42, 0, 2, 17, 10, 9, 2, 69, 30, 8, 65.2, 21.8, -18.9, -68.1, 1);
INSERT INTO `schedule_score` VALUES (72, 42, 1, 17, 2, 9, 10, 64, 23, 1, 28, 69.6, 9.4, -20.9, -58.1, 1);
INSERT INTO `schedule_score` VALUES (73, 43, 0, 6, 4, 1, 9, 6, 15, 25, 29, 99.1, 21.2, -29.4, -90.9, 1);
INSERT INTO `schedule_score` VALUES (74, 43, 1, 1, 6, 4, 9, 11, 38, 26, 1, 52.6, 8.7, -15.5, -45.8, 1);
INSERT INTO `schedule_score` VALUES (75, 44, 0, 4, 2, 10, 17, 19, 2, 37, 69, 77.2, 12.1, -31.2, -58.1, 1);
INSERT INTO `schedule_score` VALUES (76, 44, 1, 10, 17, 4, 2, 31, 65, 26, 12, 54.4, 5.8, -19.0, -41.2, 1);
INSERT INTO `schedule_score` VALUES (77, 45, 0, 3, 7, 1, 2, 17, 35, 24, 23, 77.7, 18.6, -35.6, -60.7, 1);
INSERT INTO `schedule_score` VALUES (78, 45, 1, 7, 1, 2, 3, 33, 21, 12, 7, 53.3, 8.3, -17.7, -43.9, 1);
INSERT INTO `schedule_score` VALUES (79, 46, 0, 1, 4, 9, 3, 11, 14, 22, 10, 68.9, 16.4, -30.3, -55.0, 1);
INSERT INTO `schedule_score` VALUES (80, 46, 1, 1, 4, 9, 3, 21, 15, 1, 16, 64.7, 17.8, -15.2, -67.3, 1);
INSERT INTO `schedule_score` VALUES (81, 47, 0, 17, 10, 2, 4, 64, 30, 13, 19, 52.6, 11.3, -19.6, -44.3, 1);
INSERT INTO `schedule_score` VALUES (82, 47, 1, 4, 17, 2, 10, 14, 65, 2, 37, 54.3, 5.0, -19.5, -39.8, 1);
INSERT INTO `schedule_score` VALUES (83, 48, 0, 7, 9, 5, 3, 33, 29, 20, 7, 55.7, 6.8, -16.1, -46.4, 1);
INSERT INTO `schedule_score` VALUES (84, 48, 1, 7, 3, 5, 9, 34, 17, 27, 8, 85.0, 13.4, -37.8, -60.6, 1);
INSERT INTO `schedule_score` VALUES (85, 49, 0, 2, 3, 1, 6, 13, 16, 25, 36, 59.0, 5.9, -16.6, -48.3, 1);
INSERT INTO `schedule_score` VALUES (86, 49, 1, 2, 1, 6, 3, 2, 21, 38, 16, 48.1, 7.0, -15.0, -40.1, 1);
INSERT INTO `schedule_score` VALUES (87, 50, 0, 6, 9, 7, 1, 32, 22, 9, 11, 56.4, 7.1, -18.1, -45.4, 1);
INSERT INTO `schedule_score` VALUES (88, 50, 1, 9, 6, 7, 1, 1, 6, 35, 24, 53.9, 10.8, -21.5, -43.2, 1);
INSERT INTO `schedule_score` VALUES (89, 51, 0, 3, 7, 9, 5, 7, 33, 29, 27, 50.6, 9.6, -16.6, -43.6, 1);
INSERT INTO `schedule_score` VALUES (90, 51, 1, 7, 9, 5, 3, 34, 1, 18, 7, 61.8, 18.7, -23.2, -57.3, 1);
INSERT INTO `schedule_score` VALUES (91, 52, 0, 17, 4, 6, 1, 65, 15, 36, 21, 55.7, 5.9, -17.5, -44.1, 1);
INSERT INTO `schedule_score` VALUES (92, 52, 1, 6, 17, 1, 4, 6, 69, 24, 14, 66.6, 1.1, -19.0, -48.7, 1);
INSERT INTO `schedule_score` VALUES (93, 53, 0, 2, 5, 3, 17, 13, 20, 17, 68, 61.4, 13.5, -20.8, -54.1, 1);
INSERT INTO `schedule_score` VALUES (94, 53, 1, 17, 2, 3, 5, 64, 12, 10, 39, 63.0, 11.5, -20.9, -53.6, 1);
INSERT INTO `schedule_score` VALUES (95, 54, 0, 5, 6, 9, 2, 18, 6, 8, 12, 61.3, 20.3, -29.7, -51.9, 1);
INSERT INTO `schedule_score` VALUES (96, 54, 1, 2, 9, 5, 6, 13, 1, 39, 32, 63.1, 13.8, -17.0, -59.9, 1);
INSERT INTO `schedule_score` VALUES (97, 55, 0, 6, 1, 17, 4, 32, 25, 64, 19, 89.3, 0.4, -21.2, -68.5, 1);
INSERT INTO `schedule_score` VALUES (98, 55, 1, 1, 6, 17, 4, 24, 6, 65, 26, 54.2, 9.4, -20.6, -43.0, 1);
INSERT INTO `schedule_score` VALUES (99, 56, 0, 10, 7, 2, 9, 30, 33, 2, 8, 80.4, 15.5, -32.9, -63.0, 1);
INSERT INTO `schedule_score` VALUES (100, 56, 1, 9, 10, 7, 2, 1, 28, 34, 13, 73.2, 9.1, -20.1, -62.2, 1);
INSERT INTO `schedule_score` VALUES (101, 57, 0, 10, 9, 4, 1, 31, 29, 19, 11, 58.8, 1.9, -19.4, -41.3, 1);
INSERT INTO `schedule_score` VALUES (102, 57, 1, 1, 9, 4, 10, 24, 22, 14, 28, 68.4, 8.7, -11.8, -65.3, 1);
INSERT INTO `schedule_score` VALUES (103, 58, 0, 3, 4, 17, 5, 10, 15, 65, 20, 60.5, 8.1, -12.7, -55.9, 1);
INSERT INTO `schedule_score` VALUES (104, 58, 1, 4, 17, 5, 3, 14, 68, 18, 16, 80.9, 10.9, -21.8, -70.0, 1);
INSERT INTO `schedule_score` VALUES (105, 59, 0, 7, 2, 9, 10, 35, 12, 22, 30, 57.2, 8.8, -20.0, -46.0, 1);
INSERT INTO `schedule_score` VALUES (106, 59, 1, 7, 9, 10, 2, 35, 1, 28, 23, 59.7, 11.8, -20.7, -50.8, 1);
INSERT INTO `schedule_score` VALUES (107, 60, 0, 3, 10, 5, 6, 17, 31, 18, 36, 55.0, 9.8, -21.2, -43.6, 1);
INSERT INTO `schedule_score` VALUES (108, 60, 1, 3, 5, 10, 6, 10, 20, 28, 6, 96.9, 6.0, -36.1, -66.8, 1);
INSERT INTO `schedule_score` VALUES (109, 61, 0, 3, 7, 9, 17, 16, 33, 29, 64, 63.9, 3.2, -17.9, -49.2, 1);
INSERT INTO `schedule_score` VALUES (110, 61, 1, 7, 3, 9, 17, 34, 7, 1, 69, 99.6, 4.1, -25.5, -78.2, 1);
INSERT INTO `schedule_score` VALUES (111, 62, 0, 1, 2, 7, 10, 25, 13, 35, 30, 67.9, 4.1, -18.1, -53.9, 1);
INSERT INTO `schedule_score` VALUES (112, 62, 1, 7, 2, 10, 1, 34, 12, 37, 25, 89.8, 19.3, -32.9, -76.2, 1);

-- ----------------------------
-- Table structure for season_titles
-- ----------------------------
DROP TABLE IF EXISTS `season_titles`;
CREATE TABLE `season_titles`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `season_id` int NOT NULL,
  `player_id` int NOT NULL,
  `title_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '头衔名称：MVP, 避四率, 最高打点',
  `prize_money` decimal(10, 2) NULL DEFAULT NULL COMMENT '奖金',
  `display_status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_title_season`(`season_id` ASC) USING BTREE,
  INDEX `fk_title_player`(`player_id` ASC) USING BTREE,
  CONSTRAINT `fk_title_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_title_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '赛季个人奖项' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of season_titles
-- ----------------------------
INSERT INTO `season_titles` VALUES (1, 1, 33, '避四赏', 1000000.00, 1);
INSERT INTO `season_titles` VALUES (2, 1, 35, 'MVP', 2000000.00, 1);
INSERT INTO `season_titles` VALUES (3, 1, 25, '最高打点', 1000000.00, 1);

-- ----------------------------
-- Table structure for seasons
-- ----------------------------
DROP TABLE IF EXISTS `seasons`;
CREATE TABLE `seasons`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '赛季ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '赛季名称，如 2023-24 Season',
  `start_date` date NULL DEFAULT NULL COMMENT '开始日期',
  `end_date` date NULL DEFAULT NULL COMMENT '结束日期',
  `is_current` tinyint(1) NULL DEFAULT 0 COMMENT '是否为当前赛季 1:是 0:否',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'M联赛赛季信息' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seasons
-- ----------------------------
INSERT INTO `seasons` VALUES (1, '2024-25 Season', NULL, NULL, 1, 1);

-- ----------------------------
-- Table structure for team_season_stats
-- ----------------------------
DROP TABLE IF EXISTS `team_season_stats`;
CREATE TABLE `team_season_stats`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `team_id` int NOT NULL,
  `season_id` int NOT NULL,
  `regular_score` decimal(10, 1) NULL DEFAULT NULL COMMENT '常规赛分数',
  `semifinal_score` decimal(10, 1) NULL DEFAULT NULL COMMENT '半决赛分数',
  `final_score` decimal(10, 1) NULL DEFAULT NULL COMMENT '决赛分数',
  `total_rank` int NULL DEFAULT NULL COMMENT '赛季最终排名',
  `display_status` tinyint(1) NOT NULL DEFAULT 1,
  `total_score` decimal(10, 1) NULL DEFAULT 0.0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_team_season`(`team_id` ASC, `season_id` ASC) USING BTREE,
  INDEX `fk_teamstats_season`(`season_id` ASC) USING BTREE,
  CONSTRAINT `fk_teamstats_season` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_teamstats_team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '队伍分赛季成绩' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of team_season_stats
-- ----------------------------
INSERT INTO `team_season_stats` VALUES (1, 1, 1, -1143.6, NULL, NULL, 9, 1, -1143.6);
INSERT INTO `team_season_stats` VALUES (2, 2, 1, -751.3, NULL, NULL, 8, 1, -751.3);
INSERT INTO `team_season_stats` VALUES (3, 3, 1, -413.9, NULL, NULL, 7, 1, -413.9);
INSERT INTO `team_season_stats` VALUES (4, 4, 1, 208.0, -188.5, NULL, 5, 1, -84.5);
INSERT INTO `team_season_stats` VALUES (5, 5, 1, 330.3, -175.1, 1.4, 4, 1, -3.6);
INSERT INTO `team_season_stats` VALUES (6, 6, 1, 481.2, 386.1, -24.8, 2, 1, 288.6);
INSERT INTO `team_season_stats` VALUES (7, 7, 1, 339.8, 343.0, 97.9, 1, 1, 354.3);
INSERT INTO `team_season_stats` VALUES (8, 9, 1, -206.0, -522.5, NULL, 6, 1, -625.5);
INSERT INTO `team_season_stats` VALUES (9, 10, 1, 1115.5, 157.0, -74.5, 3, 1, 282.9);

-- ----------------------------
-- Table structure for teams
-- ----------------------------
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '队伍ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '队伍名称',
  `supervisor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '监督/教练姓名',
  `company` int NULL DEFAULT NULL COMMENT '所属企业',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '队伍简介',
  `display_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '展示状态 1:显示 0:隐藏',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `intro_video` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '队伍介绍视频',
  `video_cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '视频封面',
  `supervisor_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '监督照片',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `idx_name`(`name` ASC) USING BTREE,
  INDEX `team`(`company` ASC) USING BTREE,
  CONSTRAINT `team` FOREIGN KEY (`company`) REFERENCES `company` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'M联赛参赛队伍' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of teams
-- ----------------------------
INSERT INTO `teams` VALUES (1, 'BEAST X', '高桥晓', 1, '一支由 BS 广播电台“BS10”制作的团队。它的名字旨在成为一支勇敢战斗的队伍，拥有压倒性的力量，能够互相帮助、与朋友一起成长。通过 BS 节目和团队活动的播出，我们将与观众和粉丝建立联系，将麻将的乐趣与奇妙传播到日本各地。让我们继续欢呼胜利！', 1, 'team_1766071410_954.png', 'BEAST.mp4', 'BEAST.jpg', 'XIAO.jpg');
INSERT INTO `teams` VALUES (2, 'EX风林火山', '二阶堂亚树', 2, '由朝日电视株式会社制作的团队。“疾如风，徐如林，火攻如山。”不用说，凭借孙子兵法的极致，他赢得了英雄 M 联赛。', 1, 'team_1766071458_634.png', 'EX.mp4', 'EX.jpg', 'YASHU.jpg');
INSERT INTO `teams` VALUES (3, 'KADOKAWA樱花骑士团', '森井巧', 3, '它以“酷日本森林概念”的基地设施“”所泽樱町命名，该理念在一片绿意盎然的土地上创造前沿文化和产业，并将其传播给世界。 与那些如“樱花般美丽绽放”、拥有“骑士”心智的队员一起，我们将在 M 联赛舞台上创造一个新故事。', 1, 'team_1766071472_258.png', 'SAKURA.mp4', 'SAKURA.jpg', 'SENJING.jpg');
INSERT INTO `teams` VALUES (4, 'KONAMI麻将格斗俱乐部', '泷泽和典', 4, '它以游戏名为《麻将格斗俱乐部》，该游戏在街机、智能手机、个人电脑等平台上开发。为了不名副其实地名副其实，我们会坚持下去，争取第一名。', 1, 'team_1766071481_223.png', 'KONAMI.mpp4', 'KONAMI.jpg', 'LONGZE.jpg');
INSERT INTO `teams` VALUES (5, 'TEAM 雷电', '高柳宽哉', 5, '它的名字来源于它结合了传奇相扑选手雷电的力量与礼貌，雷电被认为是相扑史上最强者，同时也以闪电般的攻击为目标。我们的目标是成为一个能够吸引人们的团队。', 1, 'team_1766071495_476.png', 'RAIDEN.mp4', 'RAIDEN.jpg', 'GAOLIU.jpg');
INSERT INTO `teams` VALUES (6, 'U-NEXT Pirates', '木下尚', 6, 'U-NEXT 有限公司的团队，它的名字源自将麻将划入未被探索的海洋，以及那些顺风驾帆而驰骋的选手们。进击吧，海盗队，海盗队员们！', 1, 'team_1766071503_870.png', 'U-NEXT.mp4', 'U-NEXT.jpg', 'MUXIA.jpg');
INSERT INTO `teams` VALUES (7, '世嘉飒美Phoenix', '茅森早香', 7, '这是娱乐公司“世嘉飒美集团”的团队。如凤凰般，他以不屈不挠的精神战斗，直到最后都不放弃。', 1, 'team_1766071514_387.png', 'PHOENIX.mp4', 'PHOENIX.jpg', 'MAOSEN.jpg');
INSERT INTO `teams` VALUES (9, '涉谷ABEMAS', '塚本泰隆', 8, '它以公司角色阿贝玛和涩谷命名，该角色自成立以来一直以该剧为基地。队伍标志位于涩谷，汉字“渋”以闪电和皇冠为主题。 白色方形画的麻雀桌表示公平竞赛的态度。', 1, 'team_1766071528_580.png', 'ABEMAS.mp4', 'ABEMAS.jpg', 'ZHONGBEN.jpg');
INSERT INTO `teams` VALUES (10, '赤坂Drivens', '越山刚', 9, '比如队伍的吉祥物犀牛，这支球队既有坚固的护甲防守，也有强大的进攻火力。基于选手的技术和经验、客观数据以及与粉丝协作的体系等多项基本因素，我们将明智有力地推动麻将游戏的未来。', 1, 'team_1766071543_804.png', 'DRIVENS.mp4', 'DRIVENS.jpg', 'YUESHAN.jpg');
INSERT INTO `teams` VALUES (17, 'Earth Jests', '川村芳範', 10, '就像变色龙一样，可以自由变色并迅速捕捉昆虫，它会自由地攻击和防御，然后迅速而锐利地击败对手。 有两种颜色的变色龙、昆虫网和代表攻防的土=土，已被象征。', 1, 'team_1766071553_110.png', 'JETS.mp4', 'JETS.jpg', 'CHUANCUN.jpg');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint NOT NULL DEFAULT 10,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `verification_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '用户头像',
  `nickname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '昵称',
  `bio` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '个人简介',
  `favorite_team_id` int NULL DEFAULT NULL COMMENT '最喜爱的队伍ID',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  UNIQUE INDEX `password_reset_token`(`password_reset_token` ASC) USING BTREE,
  INDEX `idx_favorite_team`(`favorite_team_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (2, '111', 'URNf9WzDC8mnA6a_ADNPIwvxoMPLVl--', '$2y$13$UjvGXAPGIeAYXeEnD0V2ceV5MPgNLCT.nDjoEUr19lm6FbazAhVEi', NULL, 'linganshikake@outlook.com', 9, 1766053432, 1766053432, 'SumOXLcpw-jN_tgcE0YY43xXBQFKmgep_1766053432', NULL, NULL, NULL, NULL);
INSERT INTO `user` VALUES (3, '123', 'EpkN9znuMdAqvGv6DKJrvokLdt-0d5VM', '$2y$13$Cc9.j88aB1LSAH1em76GTuAEDuR4w8Lo.Q/qqYLid6H6Ugt7.KGie', NULL, '1572456506@qq.com', 9, 1766054855, 1766054855, 'QihsSZBtFn6YqEnFXMpeqDqp0WOA4wPD_1766054855', NULL, NULL, NULL, NULL);
INSERT INTO `user` VALUES (4, 'admin', 'k5rguj0_uwfIbIxEy7nFXC8McyleElbw', '$2y$13$XHxNwaOl5WFzNSpMm7RH3.X5hyWAzTTGOEbCSF3Z70x0IXehWP2MG', NULL, '1572456509@qq.com', 10, 1766055592, 1766055592, 'aKqnztxbgpYL0eTfz4p6in89U7m6a6zr_1766055592', NULL, NULL, NULL, NULL);
INSERT INTO `user` VALUES (5, 'MHN', 'X6NMx5wtM_JY4QNVVH3piX6xe6Zsu5Nh', '$2y$13$Ktgu4pb/coycbUsgXwCwVuyGrLoW8.bGcK1hoCwWngIBFqT4e3T4W', NULL, '1572456510@qq.com', 10, 1766055763, 1766055763, 'CV0vDleNiuAhnmfw8pPJg2T7y2GY-pvL_1766055763', NULL, NULL, NULL, NULL);
INSERT INTO `user` VALUES (6, 'admin1', 'Ew82ikqwY_UpB2elaxoOOYXbSJ-4_DxJ', '$2y$13$7vy90nRo.NZXmb3Xb3lGx.H54aWGqwyYjiTumJcLPAmb6IZpmTx3G', NULL, '1572456567@qq.com', 10, 1766056302, 1766056302, 'dHoH6HPsc0Sp55QxaCGzqx94U9g9ZIP6_1766056302', NULL, NULL, NULL, NULL);
INSERT INTO `user` VALUES (7, 'Xwy', 'chzjKDzKlWUn9n8tXK0Ifk14sK0mB0cP', '$2y$13$87/ydz1D4q1hN0XuriqHXeay04SgtrbTW6uUPa.cdwASm80kRL1gS', NULL, '1349841390@qq.com', 10, 1766056598, 1766390248, 'WqY7_TliiSTKGzliolZQc0LMklKX6S62_1766056598', 'avatar_7_1766390248.jpg', '', '', 3);

SET FOREIGN_KEY_CHECKS = 1;
