@echo off
chcp 65001 >nul
title M联赛信息管理系统 - 快速启动

echo ============================================
echo   M联赛信息管理系统 - 快速启动
echo ============================================
echo.

:: 切换到脚本所在目录（项目根目录）
cd /d "%~dp0"

:: 检查项目文件是否存在
if not exist "source\frontend\web" (
    echo [错误] 找不到项目文件！
    echo 请确保此脚本位于项目根目录
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
cd source\frontend\web
start "前台服务器-8080" php -S localhost:8080
cd /d "%~dp0"

:: 启动后台服务器
cd source\backend\web
start "后台服务器-8081" php -S localhost:8081
cd /d "%~dp0"

:: 等待服务器启动
timeout /t 2 >nul

:: 打开浏览器
start http://localhost:8080

echo 服务器已启动！
echo.
pause
