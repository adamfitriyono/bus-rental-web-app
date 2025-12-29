@echo off
title SISTEM TIKET BIS - QUICK START
color 0A

echo ========================================
echo   SISTEM PENJUALAN TIKET BIS ONLINE
echo   Quick Start Script
echo ========================================
echo.

echo [1/4] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ✓ Cache cleared!
echo.

echo [2/4] Checking database connection...
php artisan migrate:status
echo.

echo [3/4] Starting Laravel server...
echo.
echo ========================================
echo   SERVER INFORMATION
echo ========================================
echo   URL: http://127.0.0.1:8000
echo   Admin Login: http://127.0.0.1:8000/login
echo   
echo   Admin Credentials:
echo   Email: admin@admin.com
echo   Password: admin123
echo ========================================
echo.
echo Press Ctrl+C to stop the server
echo.

php artisan serve

pause
