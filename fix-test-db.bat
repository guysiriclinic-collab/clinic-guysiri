@echo off
echo ========================================
echo    Fixing Test Database
echo ========================================
echo.

echo [1/3] Dropping existing test database...
mysql -u root -e "DROP DATABASE IF EXISTS cg_testing;"
echo      Database dropped!

echo [2/3] Creating fresh test database...
mysql -u root -e "CREATE DATABASE cg_testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
echo      Database created!

echo [3/3] Running migrations...
php artisan migrate:fresh --env=testing --seed --force
echo      Migrations completed!

echo.
echo ========================================
echo    Database Fixed! Now running tests...
echo ========================================
echo.

php artisan test --filter=SystemScenarioTest --env=testing

echo.
echo ========================================
echo    Test Completed!
echo ========================================
pause