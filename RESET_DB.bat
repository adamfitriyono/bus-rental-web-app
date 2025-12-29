@echo off
title RESET DATABASE - SISTEM TIKET BIS
color 0C

echo ========================================
echo   RESET DATABASE
echo   WARNING: This will delete all data!
echo ========================================
echo.

set /p confirm="Are you sure? (yes/no): "
if /i "%confirm%" NEQ "yes" (
    echo Operation cancelled.
    pause
    exit
)

echo.
echo [1/2] Resetting database...
php artisan migrate:fresh --seed
echo ✓ Database reset complete!
echo.

echo [2/2] Data dummy has been created:
echo   - 3 Buses (Ekonomi, Bisnis, Eksekutif)
echo   - 5 Popular routes
echo   - 10 Schedules for this week
echo   - Sample bookings
echo   - Admin account: admin@admin.com / admin123
echo.

echo ========================================
echo   DATABASE READY!
echo ========================================
echo.

pause
