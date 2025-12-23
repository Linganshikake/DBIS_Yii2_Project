# M.LEAGUE信息管理系统 - 部署指南

## 📋 环境要求

- **PHP** >= 7.2（需添加到系统 PATH）
- **MySQL** >= 5.7 / MariaDB >= 10.2
- **推荐环境**: XAMPP（已包含 PHP + MySQL + Apache）

> ⚡ **注意**: 本项目已包含 vendor 目录，**无需运行 composer install**！

---

## 🚀 一键部署（推荐）

### 步骤 1：下载/克隆项目

```bash
git clone -b dev https://github.com/Linganshikake/DBIS_Yii2_Project.git
```

### 步骤 2：确保 MySQL 服务已启动

- **XAMPP 用户**：打开 XAMPP Control Panel，启动 MySQL
- **独立 MySQL 用户**：确保 MySQL 服务正在运行

### 步骤 3：双击运行 `deploy.bat`

脚本会自动完成以下操作：
1. ✅ 初始化项目环境
2. ✅ 配置数据库连接（按提示输入数据库名、用户名、密码）
3. ✅ 自动创建数据库
4. ✅ 自动导入 SQL 数据
5. ✅ 启动开发服务器

> 💡 **MySQL 自动检测**：脚本会自动查找以下位置的 MySQL：
> - XAMPP：`C/D/E/F:\xampp\mysql\bin`
> - MySQL 8.0/5.7：`C:\Program Files\MySQL\...`
> - phpStudy、WNMP 等集成环境

### 步骤 4：访问系统

部署完成后，浏览器会自动打开前台页面。

---

## 🔧 手动部署（备选）

如果一键部署遇到问题，可以按以下步骤手动操作：

### 步骤 1：初始化项目环境

```bash
cd source

# Windows
init.bat

# Linux/Mac
php init
```

选择 `[0] Development` 开发环境。

### 步骤 2：配置数据库连接

编辑 `source/common/config/main-local.php`：

```php
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=mleague_db',
            'username' => 'root',
            'password' => '',  // XAMPP 默认密码为空
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

### 步骤 3：创建数据库并导入数据

1. 在 MySQL 中创建新数据库：
```sql
CREATE DATABASE mleague_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

2. 导入 SQL 文件：
```bash
mysql -u root -p mleague_db < data/install.sql/yii2_final_version.sql
```

或使用 **phpMyAdmin** 导入：
- 访问 http://localhost/phpmyadmin
- 创建数据库 `mleague_db`
- 选择该数据库，点击"导入"
- 选择文件 `data/install.sql/yii2_final_version.sql`

### 步骤 4：启动服务器

**方式一：使用 start.bat（推荐）**
```bash
双击 start.bat
```

**方式二：手动启动 PHP 内置服务器**
```bash
# 终端1 - 启动前台 (端口 8080)
cd source/frontend/web
php -S localhost:8080

# 终端2 - 启动后台 (端口 8081)
cd source/backend/web
php -S localhost:8081
```

**方式三：使用 Apache (XAMPP/WAMP)**

将项目放入 `htdocs` 目录，配置虚拟主机指向：
- 前台：`source/frontend/web`
- 后台：`source/backend/web`

---

## 🔐 默认账号

| 类型 | 用户名 | 密码 | 说明 |
|------|--------|------|------|
| 管理员 | admin | admin123 | 可访问后台管理 |

> ⚠️ **安全提示**：部署到生产环境前，请务必修改默认密码！

---

## 📁 访问地址

| 应用 | 地址 |
|------|------|
| 前台 | http://localhost:8080 |
| 后台 | http://localhost:8081 |

---

## 🛠️ 常见问题

### 1. "php 不是内部或外部命令"
PHP 未添加到系统 PATH。解决方法：
- XAMPP 用户：将 `C:\xampp\php` 添加到系统环境变量 PATH
- 或直接使用完整路径：`C:\xampp\php\php.exe -S localhost:8080`

### 2. 数据库连接失败
- 检查 MySQL 服务是否已启动（XAMPP Control Panel）
- 检查 `source/common/config/main-local.php` 中的配置
- XAMPP 默认：用户名 `root`，密码为**空**

### 3. deploy.bat 找不到 MySQL
脚本会自动检测常见路径，如果仍找不到：
- 确保 MySQL 服务已启动
- 可手动将 MySQL bin 目录添加到系统 PATH
- 或使用 phpMyAdmin 手动导入 SQL

### 4. 页面报 500 错误
- 检查 `source/*/runtime` 和 `source/*/web/assets` 目录是否有写权限
- 查看错误日志：`source/frontend/runtime/logs/app.log`

### 5. 端口被占用
修改 `start.bat` 中的端口号，或关闭占用端口的程序：
```bash
netstat -ano | findstr :8080
taskkill /PID <进程ID> /F
```

---

## 🔑 日常启动

部署完成后，每次启动只需：

```
双击 start.bat
```

会自动启动前后台服务器并打开浏览器。

---

## 📁 项目文件说明

| 文件/目录 | 说明 |
|-----------|------|
| `deploy.bat` | 一键部署脚本（首次使用） |
| `start.bat` | 快速启动脚本（日常使用） |
| `source/` | 项目源代码 |
| `data/install.sql/` | 数据库 SQL 文件 |
| `DEPLOY.md` | 本部署文档 |

---

## 📞 技术支持

如有问题，请联系项目开发团队。

负责人邮箱：2313547@nankai.edu.cn

