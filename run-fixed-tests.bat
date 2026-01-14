@echo off
echo ========================================
echo    Running Fixed Test Suite
echo ========================================
echo.

cd /d C:\laragon\www\new\clinic

echo [1/5] Dropping old test database...
mysql -u root -e "DROP DATABASE IF EXISTS cg_testing;" 2>NUL
echo       Database dropped!

echo [2/5] Creating fresh test database...
mysql -u root -e "CREATE DATABASE cg_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo       Database created!

echo [3/5] Installing doctrine/dbal for migrations...
composer require doctrine/dbal --no-interaction --quiet
echo       Dependencies installed!

echo [4/5] Running migrations with fixes...
php artisan migrate --env=testing --seed --force
echo       Migrations completed!

echo [5/5] Running test suite...
echo.
echo ========================================
vendor\bin\phpunit tests/Feature/SystemScenarioTest.php --testdox
echo ========================================
echo.

echo Test Results:
echo - Check for PASS = Feature working correctly
echo - Check for FAIL = Need additional fixes
echo.
pause