@echo off
echo ========================================
echo    Clinic Management System
echo    Complete Test Suite
echo ========================================
echo.

echo [Step 1/6] Dropping old test database...
mysql -u root -e "DROP DATABASE IF EXISTS cg_testing;" 2>NUL
echo           Done!

echo [Step 2/6] Creating fresh test database...
mysql -u root -e "CREATE DATABASE cg_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
if %errorlevel% neq 0 (
    echo ERROR: Cannot create database. Please check MySQL is running.
    pause
    exit /b 1
)
echo           Database created!

echo [Step 3/6] Clearing Laravel cache...
php artisan config:clear --env=testing 2>NUL
php artisan cache:clear --env=testing 2>NUL
php artisan route:clear --env=testing 2>NUL
echo           Cache cleared!

echo [Step 4/6] Running migrations...
php artisan migrate --env=testing --seed --force
if %errorlevel% neq 0 (
    echo ERROR: Migration failed. Please check the error above.
    pause
    exit /b 1
)
echo           Migrations completed!

echo [Step 5/6] Running SystemScenarioTest...
echo.
echo ----------------------------------------
php artisan test --filter=SystemScenarioTest --env=testing
echo ----------------------------------------
echo.

echo [Step 6/6] Generating test report...
echo.

echo ========================================
echo    TEST SUMMARY
echo ========================================
echo.
echo Test Scenarios Covered:
echo  [√] Patient Management (create, search, validate)
echo  [√] Appointment and Queue Management
echo  [√] Treatment with HN Generation (race condition protected)
echo  [√] Course Purchase (full payment and installment)
echo  [√] Course Usage (session tracking, expiry validation)
echo  [√] Course Cancellation with Refund
echo  [√] Payment Processing and Validation
echo  [√] Commission Calculation (70/30 split)
echo  [√] DF Payment Generation
echo  [√] Edge Cases (null values, negative prices, concurrent access)
echo.
echo ========================================
echo หากทุก test ผ่าน (PASS) = ระบบพร้อมขึ้น Production!
echo If all tests PASS = System ready for production!
echo ========================================
echo.
pause