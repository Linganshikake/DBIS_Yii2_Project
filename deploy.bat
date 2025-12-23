@echo off
setlocal enabledelayedexpansion
chcp 65001 >nul
title M联赛信息管理系统 - 一键部署工具

:: 保存项目根目录
set "PROJECT_ROOT=%~dp0"
cd /d "%PROJECT_ROOT%"

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
cd /d "%PROJECT_ROOT%"
echo [1/4] 项目初始化完成！
echo.

:: 步骤2: 配置数据库
echo [2/4] 配置数据库连接...
echo.
set /p DB_NAME=请输入数据库名称 (默认: mleague_db): 
if "%DB_NAME%"=="" set DB_NAME=mleague_db

set /p DB_USER=请输入数据库用户名 (默认: root): 
if "%DB_USER%"=="" set DB_USER=root

set /p DB_PASS=请输入数据库密码 (XAMPP默认为空,直接回车): 

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

:: 步骤3: 自动创建数据库并导入SQL
echo [3/4] 正在创建数据库并导入数据...
echo.

:: 检查 mysql 命令是否可用
where mysql >nul 2>&1
if %errorlevel% neq 0 (
    echo [提示] 系统 PATH 中未找到 mysql 命令
    echo.
    
    :: XAMPP 常见安装路径
    set "XAMPP_C=C:\xampp\mysql\bin"
    set "XAMPP_D=D:\xampp\mysql\bin"
    set "XAMPP_E=E:\xampp\mysql\bin"
    set "XAMPP_F=F:\xampp\mysql\bin"
    
    :: MySQL 独立安装路径
    set "MYSQL8_PATH=C:\Program Files\MySQL\MySQL Server 8.0\bin"
    set "MYSQL8_X86=C:\Program Files (x86)\MySQL\MySQL Server 8.0\bin"
    set "MYSQL57_PATH=C:\Program Files\MySQL\MySQL Server 5.7\bin"
    set "MYSQL57_X86=C:\Program Files (x86)\MySQL\MySQL Server 5.7\bin"
    
    :: phpStudy / WNMP 等集成环境
    set "PHPSTUDY=C:\phpstudy_pro\Extensions\MySQL8.0.12\bin"
    set "WNMP=C:\wnmp\mysql\bin"
    
    :: 自动查找 MySQL
    set "FOUND_PATH="
    for %%P in (
        "!XAMPP_C!" "!XAMPP_D!" "!XAMPP_E!" "!XAMPP_F!"
        "!MYSQL8_PATH!" "!MYSQL8_X86!" "!MYSQL57_PATH!" "!MYSQL57_X86!"
        "!PHPSTUDY!" "!WNMP!"
    ) do (
        if "!FOUND_PATH!"=="" if exist "%%~P\mysql.exe" set "FOUND_PATH=%%~P"
    )
    
    if "!FOUND_PATH!"=="" (
        echo [错误] 找不到 MySQL 客户端!
        echo.
        echo 请手动执行以下步骤:
        echo   1. 打开 phpMyAdmin: http://localhost/phpmyadmin
        echo   2. 创建数据库: %DB_NAME%
        echo   3. 导入 SQL 文件: data\install.sql\yii2_final_version.sql
        echo.
        pause
        goto :step4
    )
    
    echo 已找到 MySQL: !FOUND_PATH!
    echo.
    echo 是否将 MySQL 添加到系统 PATH?
    echo   [1] 是, 添加到用户 PATH (推荐)
    echo   [2] 否, 仅本次使用
    echo.
    set /p ADD_PATH="请选择 (1/2, 默认2): "
    
    if "!ADD_PATH!"=="1" (
        echo 正在添加到用户 PATH...
        for /f "tokens=2*" %%a in ('reg query "HKCU\Environment" /v PATH 2^>nul') do set "USER_PATH=%%b"
        if "!USER_PATH!"=="" (
            setx PATH "!FOUND_PATH!" >nul
        ) else (
            echo !USER_PATH! | find /i "!FOUND_PATH!" >nul
            if errorlevel 1 (
                setx PATH "!USER_PATH!;!FOUND_PATH!" >nul
            ) else (
                echo PATH 中已包含该路径
            )
        )
        echo [完成] MySQL 已添加到用户 PATH, 重启终端后生效
        echo.
    )
    
    set "PATH=!FOUND_PATH!;%PATH%"
    set "MYSQL_CMD=mysql"
) else (
    set "MYSQL_CMD=mysql"
)

:: 创建数据库
echo 正在创建数据库 %DB_NAME%...
if "%DB_PASS%"=="" (
    "!MYSQL_CMD!" -u %DB_USER% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
) else (
    "!MYSQL_CMD!" -u %DB_USER% -p%DB_PASS% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
)

if %errorlevel% neq 0 (
    echo [错误] 创建数据库失败! 请检查 MySQL 服务是否启动, 用户名密码是否正确.
    pause
    goto :step4
)
echo 数据库创建成功!

:: 导入SQL文件
echo 正在导入数据...
if "%DB_PASS%"=="" (
    "!MYSQL_CMD!" -u %DB_USER% %DB_NAME% < "data\install.sql\yii2_final_version.sql"
) else (
    "!MYSQL_CMD!" -u %DB_USER% -p%DB_PASS% %DB_NAME% < "data\install.sql\yii2_final_version.sql"
)

if %errorlevel% neq 0 (
    echo [错误] 导入数据失败!
    pause
    goto :step4
)
echo [3/4] 数据库创建并导入完成!
echo.

:step4

:: 步骤4: 启动服务器
echo [4/4] 部署完成!
echo.
set /p START_SERVER=启动服务器? (Y/N, 默认Y): 
if /i "%START_SERVER%"=="N" goto :end

echo.
echo 正在启动服务器...
echo   前台: http://localhost:8080
echo   后台: http://localhost:8081
echo.
echo [提示] 关闭弹出的窗口可停止服务器
echo.

:: 启动前台服务器
cd source\frontend\web
start "前台服务器-8080" php -S localhost:8080
cd /d "%PROJECT_ROOT%"

:: 启动后台服务器
cd source\backend\web
start "后台服务器-8081" php -S localhost:8081
cd /d "%PROJECT_ROOT%"

:: 打开浏览器
timeout /t 2 >nul
start http://localhost:8080

:end
echo.
echo ============================================
echo   部署完成!
echo ============================================
echo.
echo 访问地址:
echo   前台: http://localhost:8080
echo   后台: http://localhost:8081
echo.
echo 默认管理员账号: admin / admin123
echo 请及时修改默认密码!
echo.
pause
endlocal
