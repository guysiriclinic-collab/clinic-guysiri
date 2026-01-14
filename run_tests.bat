@echo off
echo ================================================================================
echo                  GCMS CLINIC SYSTEM - PRODUCTION TESTING
echo ================================================================================
echo.

REM Set Laragon PHP path
set PHP_PATH=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe

REM Check if PHP exists
if not exist "%PHP_PATH%" (
    echo ERROR: PHP not found at %PHP_PATH%
    echo Please update the PHP_PATH in this batch file
    pause
    exit /b 1
)

echo Using PHP: %PHP_PATH%
echo.

REM Change to clinic directory
cd /d C:\laragon\www\new\clinic

echo Running Production Tests...
echo ================================================================================
echo.

REM Run the production test suite
"%PHP_PATH%" run_production_tests.php

echo.
echo ================================================================================
echo Testing completed! Check the results above.
echo Test results saved to: test_results_*.json
echo ================================================================================
echo.

pause