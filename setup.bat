@echo off
title Smart Palao - Automated Local Setup
color 0E

echo ======================================================================
echo                     🥚 SMART PALAO INCUBATION PLATFORM 
echo                  1-Click Team Member Local Setup
echo ======================================================================
echo.

:: 1. Copy .env if missing
if not exist .env (
    echo [1/5] Setting up local environment (.env)...
    copy .env.example .env >nul
) else (
    echo [1/5] Existing .env file found.
)

:: 2. Composer Install
echo.
echo [2/5] Installing PHP vendor dependencies via Composer...
where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Composer was not found in your PATH!
    echo Please install Composer from: https://getcomposer.org
    pause
    exit /b
)
call composer install --no-interaction

:: 3. Application Key
echo.
echo [3/5] Generating application key...
call php artisan key:generate --ansi

:: 4. Database Setup & Seed
echo.
echo [4/5] Setting up MySQL database and seeding initial incubator data...
echo (Ensure MySQL or Laragon/XAMPP is running!)
call php artisan migrate:fresh --seed

:: 5. Start Server
echo.
echo ======================================================================
echo   SUCCESS! Smart Palao is ready to run locally!
echo.
echo   URL:               http://localhost:8000
echo   Owner / Admin:     owner@smartpalao.com   ^| password123
echo   Buyer / User:      buyer@gmail.com        ^| password123
echo ======================================================================
echo.
echo Launching browser and starting development server...
start http://localhost:8000
call php artisan serve
pause
