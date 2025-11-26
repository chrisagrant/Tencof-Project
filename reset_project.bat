@echo off
REM Reset Project Script - Jalankan ini jika ada masalah dengan login

echo Stopping PHP server...
taskkill /F /IM php.exe 2>nul

echo.
echo Pulling latest changes from git...
git pull origin faiz

echo.
echo Clearing Laravel cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo.
echo Resetting database...
php artisan migrate:fresh --seed

echo.
echo Starting Laravel server...
php artisan serve

echo.
echo ========================================
echo Server running on: http://127.0.0.1:8000
echo Login credentials:
echo   Email: admin@tencof.com
echo   Password: password123
echo ========================================
pause
