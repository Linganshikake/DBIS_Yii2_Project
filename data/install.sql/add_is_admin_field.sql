-- 添加管理员字段到 user 表
-- 在 Navicat 或 phpMyAdmin 中执行此 SQL

-- 1. 添加 is_admin 字段
ALTER TABLE `user` ADD COLUMN `is_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否为管理员 0:否 1:是' AFTER `status`;

-- 2. 将 ID=1 的用户设为管理员（通常是超级管理员）
UPDATE `user` SET `is_admin` = 1 WHERE `id` = 1;

-- 3. 如果你想将特定用户设为管理员，修改下面的 SQL 中的用户名
-- UPDATE `user` SET `is_admin` = 1 WHERE `username` = 'admin';

-- 验证：查看所有用户的管理员状态
SELECT `id`, `username`, `email`, `is_admin`, `status` FROM `user`;
