# ============================================================
# deploy.ps1 — Build & Package HRM for Hosting
# ============================================================
# Usage: .\deploy.ps1
# Output: hrm-deploy.zip  (upload entire contents to hosting)
# After upload: rename .env.production → .env on the server
#               then run: php artisan migrate --force
# ============================================================

$root = Split-Path -Parent $MyInvocation.MyCommand.Path

Write-Host ""
Write-Host "===== [1/5] Building front-end assets =====" -ForegroundColor Cyan
Set-Location $root
npm run build
if ($LASTEXITCODE -ne 0) { Write-Host "npm build failed" -ForegroundColor Red; exit 1 }

Write-Host ""
Write-Host "===== [2/5] Installing PHP dependencies (production) =====" -ForegroundColor Cyan
$composerResult = & composer install --no-dev --optimize-autoloader --no-interaction 2>&1
$composerResult | ForEach-Object { Write-Host $_ }
# composer exit code 0 = success (funding warnings are not errors)

Write-Host ""
Write-Host "===== [3/5] Cleaning old deploy package =====" -ForegroundColor Cyan
if (Test-Path "$root\deploy_package") {
    Remove-Item -Recurse -Force "$root\deploy_package"
}
New-Item -ItemType Directory -Path "$root\deploy_package" | Out-Null

Write-Host ""
Write-Host "===== [4/5] Copying files to deploy_package =====" -ForegroundColor Cyan

# Copy everything EXCEPT: node_modules, .git, storage/logs, tests, .env, .env.example, deploy files
robocopy $root "$root\deploy_package" /E `
    /XD node_modules .git "$root\storage\logs" tests deploy_package `
    /XF .env .env.example .env.production phpunit.xml deploy.ps1 hrm-deploy.zip fix_grid.py

# robocopy exit codes 0-7 = success (8+ = error)
if ($LASTEXITCODE -ge 8) {
    Write-Host "robocopy failed with exit code $LASTEXITCODE" -ForegroundColor Red
    exit 1
}

# -- KEY STEP: copy .env.production as .env into deploy package --
# This is the DB credentials and app settings for hosting
Write-Host ""
Write-Host "  → Injecting .env.production as .env for hosting..." -ForegroundColor Yellow
if (-not (Test-Path "$root\.env.production")) {
    Write-Host "ERROR: .env.production not found! Create it first." -ForegroundColor Red
    exit 1
}
Copy-Item "$root\.env.production" "$root\deploy_package\.env" -Force

# Create writable storage directories that hosting needs
@(
    "$root\deploy_package\storage\app\public",
    "$root\deploy_package\storage\framework\cache\data",
    "$root\deploy_package\storage\framework\sessions",
    "$root\deploy_package\storage\framework\views",
    "$root\deploy_package\storage\logs"
) | ForEach-Object {
    if (-not (Test-Path $_)) { New-Item -ItemType Directory -Path $_ -Force | Out-Null }
    # Create .gitkeep so empty dirs are included in zip
    New-Item -ItemType File -Path "$_\.gitkeep" -Force | Out-Null
}

Write-Host ""
Write-Host "===== [5/5] Creating hrm-deploy.zip =====" -ForegroundColor Cyan
if (Test-Path "$root\hrm-deploy.zip") {
    Remove-Item "$root\hrm-deploy.zip" -Force
}
Compress-Archive -Path "$root\deploy_package\*" -DestinationPath "$root\hrm-deploy.zip"

Write-Host ""
Write-Host "====================================================" -ForegroundColor Green
Write-Host " DONE!  hrm-deploy.zip is ready to upload." -ForegroundColor Green
Write-Host "====================================================" -ForegroundColor Green
Write-Host ""
Write-Host "After uploading to hosting:" -ForegroundColor Yellow
Write-Host "  1. Extract zip to public_html (or subdomain folder)" -ForegroundColor Yellow
Write-Host "  2. .env is already set with hosting DB credentials" -ForegroundColor Yellow
Write-Host "  3. Set folder permissions: storage/ and bootstrap/cache/ → 755" -ForegroundColor Yellow
Write-Host "  4. Run: php artisan migrate --force" -ForegroundColor Yellow
Write-Host "  5. Run: php artisan storage:link" -ForegroundColor Yellow
Write-Host "  6. Run: php artisan config:cache" -ForegroundColor Yellow
Write-Host ""
