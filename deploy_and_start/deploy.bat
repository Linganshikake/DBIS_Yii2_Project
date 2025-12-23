@echo off
chcp 65001 >nul
title M联赛信息管理系统 - 一键部署工具

echo ============================================
echo   M联赛信息管理系统 - 一键部署工具
echo ============================================
echo.
echo   注意: 本项目已包含 vendor 目录
echo         无需运行 composer install！
echo.

:: 检查是否在正确目录
if not exist "source\composer.json" (
    echo [错误] 请在项目根目录运行此脚本！
    echo 当前目录: %CD%
    pause
    exit /b 1
)

:: 步骤1: 初始化项目
echo [1/4] 正在初始化项目环境...
cd source
echo 0 | php init --env=Development --overwrite=All
cd ..
echo [1/4] 项目初始化完成！
echo.

:: 步骤2: 配置数据库
echo [2/4] 配置数据库连接...
echo.
set /p DB_NAME=请输入数据库名称 (默认: mleague_db): 
if "%DB_NAME%"=="" set DB_NAME=mleague_db

set /p DB_USER=请输入数据库用户名 (默认: root): 
if "%DB_USER%"=="" set DB_USER=root

set /p DB_PASS=请输入数据库密码: 

:: 生成数据库配置文件
echo ^<?php > source\common\config\main-local.php
echo return [ >> source\common\config\main-local.php
echo     'components' =^> [ >> source\common\config\main-local.php
echo         'db' =^> [ >> source\common\config\main-local.php
echo             'class' =^> 'yii\db\Connection', >> source\common\config\main-local.php
echo             'dsn' =^> 'mysql:host=localhost;dbname=%DB_NAME%', >> source\common\config\main-local.php
echo             'username' =^> '%DB_USER%', >> source\common\config\main-local.php
echo             'password' =^> '%DB_PASS%', >> source\common\config\main-local.php
echo             'charset' =^> 'utf8mb4', >> source\common\config\main-local.php
echo         ], >> source\common\config\main-local.php
echo         'mailer' =^> [ >> source\common\config\main-local.php
echo             'class' =^> 'yii\swiftmailer\Mailer', >> source\common\config\main-local.php
echo             'viewPath' =^> '@common/mail', >> source\common\config\main-local.php
echo             'useFileTransport' =^> true, >> source\common\config\main-local.php
echo         ], >> source\common\config\main-local.php
echo     ], >> source\common\config\main-local.php
echo ]; >> source\common\config\main-local.php

echo [2/4] 数据库配置完成！
echo.

:: 步骤3: 导入数据库
echo [3/4] 准备导入数据库...
echo.
echo 请手动执行以下步骤：
echo   1. 打开 Navicat 或 phpMyAdmin
echo   2. 创建数据库: %DB_NAME%
echo   3. 导入 SQL 文件: data\install.sql\yii2_final_version.sql
echo.
echo 或使用命令行：
echo   mysql -u %DB_USER% -p -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4"
echo   mysql -u %DB_USER% -p %DB_NAME% ^< data\install.sql\yii2_final_version.sql
echo.
pause

:: 步骤4: 启动服务器
echo [4/4] 部署完成！是否启动开发服务器？
echo.
set /p START_SERVER=启动服务器? (Y/N, 默认Y): 
if /i "%START_SERVER%"=="N" goto :end

echo.
echo 正在启动服务器...
echo   前台: http://localhost:8080
echo   后台: http://localhost:8081
echo.
echo [提示] 按 Ctrl+C 可停止服务器
echo.

:: 启动前台服务器
start "前台服务器" cmd /k "cd /d %CD%\source\frontend\web && php -S localhost:8080"

:: 启动后台服务器
start "后台服务器" cmd /k "cd /d %CD%\source\backend\web && php -S localhost:8081"

:: 打开浏览器
timeout /t 2 >nul
start http://localhost:8080

:end
echo.
echo ============================================
echo   部署完成！
echo ============================================
echo.
echo 访问地址:
echo   前台: http://localhost:8080
echo   后台: http://localhost:8081
echo.
echo 默认管理员账号: admin / admin123
echo 请及时修改默认密码！
echo.
pause
