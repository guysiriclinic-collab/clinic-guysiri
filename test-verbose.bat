@echo off
chcp 65001 > nul
echo ========================================
echo    Running PHPUnit Tests
echo ========================================
echo.

cd /d C:\laragon\www\new\clinic

echo Clearing cache...
php artisan config:clear --env=testing
echo.

echo Running tests with detailed output...
echo ========================================
php artisan test --filter=SystemScenarioTest --env=testing --testdox
echo ========================================
echo.

echo Test execution completed.
echo.
echo Legend:
echo  PASS = Test passed successfully
echo  FAIL = Test failed (needs fixing)
echo.
pause