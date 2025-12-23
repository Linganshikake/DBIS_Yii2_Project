@echo off
chcp 65001 >nul
title M联赛信息管理系统 - 快速启动

echo ============================================
echo   M联赛信息管理系统 - 快速启动
echo ============================================
echo.

cd /d "%~dp0"

if not exist "source\frontend\web" (
    echo [错误] 找不到项目文件！
    pause
    exit /b 1
)

echo 正在启动服务器...
echo.
echo   前台: http://localhost:8080
echo   后台: http://localhost:8081
echo.
echo [提示] 关闭此窗口可停止所有服务器
echo.

:: 启动前台服务器
start "前台服务器-8080" cmd /k "cd /d %~dp0source\frontend\web && php -S localhost:8080"

:: 启动后台服务器
start "后台服务器-8081" cmd /k "cd /d %~dp0source\backend\web && php -S localhost:8081"

:: 等待服务器启动
timeout /t 2 >nul

:: 打开浏览器
start http://localhost:8080

echo 服务器已启动！
echo.
pause
