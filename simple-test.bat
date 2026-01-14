@echo off
echo ========================================
echo    Simple Test Runner
echo ========================================
echo.

cd /d C:\laragon\www\new\clinic

echo Step 1: Testing database connection...
php artisan tinker --execute="echo DB::connection('mysql')->getDatabaseName();" --env=testing
echo.

echo Step 2: Running PHPUnit tests...
echo ----------------------------------------
vendor\bin\phpunit --filter SystemScenarioTest
echo ----------------------------------------
echo.

echo If you see test results above, check:
echo  - Green/OK = Tests passed
echo  - Red/FAILURES = Tests failed
echo.
pause