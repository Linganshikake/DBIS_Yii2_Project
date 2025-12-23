# M.LEAGUE信息管理系统 - 部署指南

## 📋 环境要求

- **PHP** >= 7.2
- **MySQL** >= 5.7 / MariaDB >= 10.2
- **Composer** (PHP 依赖管理工具)
- **Web 服务器**: Apache / Nginx / XAMPP / WAMP

---

## 🚀 一键部署步骤

### 方法一：使用部署脚本（推荐）

#### 步骤 1：下载/克隆项目

#### 步骤 2：

**Windows 用户：**
```bash
# 双击运行或在命令行执行
deploy.bat
```

#### 步骤 3：按提示输入数据库信息

#### 步骤 4：向数据库中导入：yii2_final_version.sql文件

**手动部署请参考方法二。**

---

### 方法二：手动部署

#### 步骤 1：安装 PHP 依赖

```bash
cd source
composer install
```

#### 步骤 2：初始化项目环境

```bash
# Windows
init.bat

# Linux/Mac
php init
```

选择 `[0] Development` 开发环境。

#### 步骤 3：配置数据库连接

编辑 `source/common/config/main-local.php`：

```php
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=您的数据库名',
            'username' => '您的数据库用户名',
            'password' => '您的数据库密码',
            'charset' => 'utf8mb4',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
    ],
];
```

#### 步骤 4：创建数据库并导入数据

1. 在 MySQL 中创建新数据库：
```sql
CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

2. 导入 SQL 文件：
```bash
mysql -u root -p your_database_name < data/install.sql/yii2_final_version.sql
```

或使用 Navicat/phpMyAdmin 导入 `data/install.sql/yii2_final_version.sql`

3. 验证管理员账号（SQL 已包含，无需手动添加）：
```sql
-- 查看管理员账号
SELECT id, username, is_admin FROM user WHERE is_admin = 1;
```

#### 步骤 5：配置 Web 服务器

**Apache (XAMPP/WAMP)：**

将项目放入 `htdocs` 目录，配置虚拟主机指向：
- 前台：`source/frontend/web`
- 后台：`source/backend/web`

**简单方式 - 使用 PHP 内置服务器：**

```bash
# 终端1 - 启动前台 (端口 8080)
cd source/frontend/web
php -S localhost:8080

# 终端2 - 启动后台 (端口 8081)
cd source/backend/web
php -S localhost:8081
```

---

## 🔐 默认账号

| 类型 | 用户名 | 密码 | 说明 |
|------|--------|------|------|
| 管理员 | admin | (注册时设置的密码) | 可访问后台 |
| 管理员 | admin1 | (注册时设置的密码) | 可访问后台 |
| 普通用户 | 其他账号 | - | 仅前台权限 |

> ⚠️ **部署后请使用已有账号登录，或注册新账号后在数据库中将 is_admin 设为 1**

---

## 📁 访问地址

| 应用 | 地址 |
|------|------|
| 前台 | http://localhost:8080 |
| 后台 | http://localhost:8081 |

---

## 🛠️ 常见问题

### 1. Composer 安装失败
```bash
composer install --ignore-platform-reqs
```

### 2. 数据库连接失败
- 检查 `main-local.php` 中的数据库配置
- 确保 MySQL 服务已启动
- 确认数据库用户权限

### 3. 页面报 500 错误
- 检查 `runtime` 和 `web/assets` 目录是否有写权限
- 查看 `runtime/logs/app.log` 错误日志

### 4. 图片/文件上传失败
确保以下目录有写权限：
```
source/frontend/web/uploads/
source/backend/web/uploads/
```

---

## 🔑 日常启动

部署完成后，每次启动只需 双击 start.bat 即可。


## 📞 技术支持

如有问题，请联系项目开发团队。负责人邮箱：2313547@nankai.edu.cn.

