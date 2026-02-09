@echo off
SETLOCAL EnableDelayedExpansion

echo ====================================================
echo    JCSES-PTA Management System - Database Setup
echo ====================================================
echo.

REM Check if XAMPP MySQL is running
echo [1/5] Checking MySQL service...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="1" (
    echo [ERROR] MySQL is not running!
    echo.
    echo Please start MySQL from XAMPP Control Panel and try again.
    echo.
    pause
    exit /b 1
)
echo [OK] MySQL is running
echo.

REM Check if .env exists
echo [2/5] Checking environment configuration...
if not exist ".env" (
    echo [WARNING] .env file not found!
    echo.
    if exist ".env.example" (
        echo Copying .env.example to .env...
        copy ".env.example" ".env"
        echo.
        echo [ACTION REQUIRED] Please update .env file with your database credentials
        echo Then run this script again.
        echo.
        pause
        exit /b 1
    ) else (
        echo [ERROR] .env.example not found!
        pause
        exit /b 1
    )
)
echo [OK] Environment file exists
echo.

REM Check if vendor directory exists
echo [3/5] Checking dependencies...
if not exist "vendor" (
    echo [WARNING] Dependencies not installed!
    echo.
    echo Installing Composer dependencies...
    call composer install
    if !ERRORLEVEL! NEQ 0 (
        echo [ERROR] Failed to install dependencies
        pause
        exit /b 1
    )
)
echo [OK] Dependencies installed
echo.

REM Generate application key if needed
findstr /C:"APP_KEY=base64:" .env >nul
if %ERRORLEVEL% NEQ 0 (
    echo Generating application key...
    php artisan key:generate
    echo.
)

REM Run the database setup
echo [4/5] Setting up database...
echo.
php artisan db:setup --fresh --seed
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Database setup failed!
    echo.
    echo Common issues:
    echo - MySQL not running
    echo - Incorrect database credentials in .env
    echo - Database doesn't exist (create it manually first)
    echo.
    pause
    exit /b 1
)
echo.

REM Install npm dependencies and build assets
echo [5/5] Building frontend assets...
if not exist "node_modules" (
    echo Installing npm dependencies...
    call npm install
)
echo Building assets...
call npm run build
echo.

echo ====================================================
echo    Setup Complete! 
echo ====================================================
echo.
echo Your JCSES-PTA Management System is ready!
echo.
echo To start the development server, run:
echo    php artisan serve
echo.
echo Then visit: http://127.0.0.1:8000
echo.
echo Test accounts are available in TEST_ACCOUNTS.md
echo.
pause
