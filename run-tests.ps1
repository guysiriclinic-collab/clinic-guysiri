Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Starting Test Suite on Laragon" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

Set-Location "C:\laragon\www\new\clinic"

Write-Host "Step 1: Creating test database..." -ForegroundColor Yellow
& mysql -u root -e "CREATE DATABASE IF NOT EXISTS cg_testing;" 2>$null
Write-Host "Database created/verified" -ForegroundColor Green

Write-Host ""
Write-Host "Step 2: Running migrations..." -ForegroundColor Yellow
& php artisan migrate:fresh --env=testing --seed
if ($LASTEXITCODE -eq 0) {
    Write-Host "Migrations completed successfully" -ForegroundColor Green
} else {
    Write-Host "Migration failed" -ForegroundColor Red
}

Write-Host ""
Write-Host "Step 3: Running SystemScenarioTest..." -ForegroundColor Yellow
& php artisan test --filter=SystemScenarioTest --env=testing

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Test Suite Completed" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan