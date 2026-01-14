@echo off
echo ========================================
echo    Clinic Management System Test Suite
echo ========================================
echo.
echo กรุณารันไฟล์นี้ใน Laragon Terminal
echo Please run this file in Laragon Terminal
echo.
pause

echo [1/5] Creating test database...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cg_testing;"
if %errorlevel% neq 0 (
    echo ERROR: Cannot create database. Please check MySQL is running.
    pause
    exit /b 1
)
echo      Database ready!
echo.

echo [2/5] Clearing cache...
php artisan config:clear --env=testing
php artisan cache:clear --env=testing
echo      Cache cleared!
echo.

echo [3/5] Running migrations...
php artisan migrate:fresh --env=testing --seed
if %errorlevel% neq 0 (
    echo ERROR: Migration failed. Please check database connection.
    pause
    exit /b 1
)
echo      Migrations completed!
echo.

echo [4/5] Running test suite...
echo.
php artisan test --filter=SystemScenarioTest --env=testing
echo.

echo [5/5] Test completed!
echo.
echo ========================================
echo    Test Results Summary
echo ========================================
echo.
echo Please review the test results above.
echo หากทุก test ผ่าน (PASS) แสดงว่าระบบพร้อมขึ้น Production
echo If all tests PASS, the system is ready for production.
echo.
pause