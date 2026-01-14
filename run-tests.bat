@echo off
echo ========================================
echo Starting Test Suite on Laragon
echo ========================================
echo.

cd /d C:\laragon\www\new\clinic

echo Step 1: Creating test database...
mysql -u root -e "CREATE DATABASE IF NOT EXISTS cg_testing;"

echo Step 2: Running migrations...
php artisan migrate:fresh --env=testing --seed

echo Step 3: Running SystemScenarioTest...
php artisan test --filter=SystemScenarioTest

echo.
echo ========================================
echo Test Suite Completed
echo ========================================
pause