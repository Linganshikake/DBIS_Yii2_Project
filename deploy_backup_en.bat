@echo off
setlocal enabledelayedexpansion
chcp 936 >nul
title M-League Deploy Tool

:: Save project root
set "PROJECT_ROOT=%~dp0"
cd /d "%PROJECT_ROOT%"

echo ============================================
echo   M-League Info System - Deploy Tool
echo ============================================
echo.
echo   Note: vendor directory is included
echo         No need to run composer install!
echo.

:: Check directory
if not exist "source\composer.json" (
    echo [Error] Please run this script in project root!
    echo Current: %CD%
    pause
    exit /b 1
)

:: Step 1: Init project
echo [1/4] Initializing project...
cd source
echo 0 | php init --env=Development --overwrite=All
cd /d "%PROJECT_ROOT%"
echo [1/4] Project initialized!
echo.

:: Step 2: Database config
echo [2/4] Configuring database...
echo.
set /p DB_NAME="Database name (default: mleague_db): "
if "%DB_NAME%"=="" set DB_NAME=mleague_db

set /p DB_USER="Database user (default: root): "
if "%DB_USER%"=="" set DB_USER=root

set /p DB_PASS="Database password (XAMPP default is empty, press Enter): "

:: Generate config file
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

echo [2/4] Database configured!
echo.

:: Step 3: Create database and import SQL
echo [3/4] Creating database and importing data...
echo.

:: Check mysql command
where mysql >nul 2>&1
if %errorlevel% neq 0 (
    echo [Info] mysql not found in PATH
    echo.
    
    set "XAMPP_MYSQL=C:\xampp\mysql\bin"
    set "MYSQL8_PATH=C:\Program Files\MySQL\MySQL Server 8.0\bin"
    set "MYSQL57_PATH=C:\Program Files\MySQL\MySQL Server 5.7\bin"
    
    set "FOUND_PATH="
    if exist "!XAMPP_MYSQL!\mysql.exe" set "FOUND_PATH=!XAMPP_MYSQL!"
    if "!FOUND_PATH!"=="" if exist "!MYSQL8_PATH!\mysql.exe" set "FOUND_PATH=!MYSQL8_PATH!"
    if "!FOUND_PATH!"=="" if exist "!MYSQL57_PATH!\mysql.exe" set "FOUND_PATH=!MYSQL57_PATH!"
    
    if "!FOUND_PATH!"=="" (
        echo [Error] MySQL client not found!
        echo.
        echo Please manually:
        echo   1. Open phpMyAdmin: http://localhost/phpmyadmin
        echo   2. Create database: %DB_NAME%
        echo   3. Import: data\install.sql\yii2_final_version.sql
        echo.
        pause
        goto :step4
    )
    
    echo Found MySQL: !FOUND_PATH!
    echo.
    echo Add MySQL to user PATH?
    echo   [1] Yes, add to PATH (recommended)
    echo   [2] No, use only this time
    echo.
    set /p ADD_PATH="Select (1/2, default 2): "
    
    if "!ADD_PATH!"=="1" (
        echo Adding to user PATH...
        for /f "tokens=2*" %%a in ('reg query "HKCU\Environment" /v PATH 2^>nul') do set "USER_PATH=%%b"
        if "!USER_PATH!"=="" (
            setx PATH "!FOUND_PATH!" >nul
        ) else (
            echo !USER_PATH! | find /i "!FOUND_PATH!" >nul
            if errorlevel 1 (
                setx PATH "!USER_PATH!;!FOUND_PATH!" >nul
            ) else (
                echo PATH already contains this path
            )
        )
        echo [Done] MySQL added to PATH. Restart terminal to take effect.
        echo.
    )
    
    set "PATH=!FOUND_PATH!;%PATH%"
    set "MYSQL_CMD=mysql"
) else (
    set "MYSQL_CMD=mysql"
)

:: Create database
echo Creating database %DB_NAME%...
if "%DB_PASS%"=="" (
    "!MYSQL_CMD!" -u %DB_USER% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
) else (
    "!MYSQL_CMD!" -u %DB_USER% -p%DB_PASS% -e "CREATE DATABASE IF NOT EXISTS %DB_NAME% CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
)

if %errorlevel% neq 0 (
    echo [Error] Failed to create database! Check MySQL service and credentials.
    pause
    goto :step4
)
echo Database created!

:: Import SQL
echo Importing data...
if "%DB_PASS%"=="" (
    "!MYSQL_CMD!" -u %DB_USER% %DB_NAME% < "data\install.sql\yii2_final_version.sql"
) else (
    "!MYSQL_CMD!" -u %DB_USER% -p%DB_PASS% %DB_NAME% < "data\install.sql\yii2_final_version.sql"
)

if %errorlevel% neq 0 (
    echo [Error] Failed to import data!
    pause
    goto :step4
)
echo [3/4] Database created and data imported!
echo.

:step4

:: Step 4: Start server
echo [4/4] Deploy complete!
echo.
set /p START_SERVER="Start server? (Y/N, default Y): "
if /i "%START_SERVER%"=="N" goto :end

echo.
echo Starting servers...
echo   Frontend: http://localhost:8080
echo   Backend:  http://localhost:8081
echo.
echo [Tip] Close the popup windows to stop servers
echo.

:: Start frontend server
cd source\frontend\web
start "Frontend-8080" php -S localhost:8080
cd /d "%PROJECT_ROOT%"

:: Start backend server
cd source\backend\web
start "Backend-8081" php -S localhost:8081
cd /d "%PROJECT_ROOT%"

:: Open browser
timeout /t 2 >nul
start http://localhost:8080

:end
echo.
echo ============================================
echo   Deploy Complete!
echo ============================================
echo.
echo URLs:
echo   Frontend: http://localhost:8080
echo   Backend:  http://localhost:8081
echo.
echo Default admin: admin / admin123
echo Please change the default password!
echo.
pause
endlocal
