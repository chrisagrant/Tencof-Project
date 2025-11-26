Write-Host "=== TENCOF PROJECT DEBUG & FIX ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "1. Checking git status..." -ForegroundColor Yellow
git status
Write-Host ""

Write-Host "2. Showing latest commit..." -ForegroundColor Yellow
git log --oneline -1
Write-Host ""

Write-Host "3. Checking DatabaseSeeder.php..." -ForegroundColor Yellow
Write-Host "File should contain TWO users (admin@tencof.com and test@example.com)"
Write-Host "---" 
Get-Content database/seeders/DatabaseSeeder.php | Select-String -Pattern "email|password" -Context 2,2
Write-Host ""

Write-Host "4. Clearing Laravel cache..." -ForegroundColor Yellow
php artisan cache:clear
php artisan config:clear
php artisan view:clear
Write-Host ""

Write-Host "5. Resetting database..." -ForegroundColor Yellow
php artisan migrate:fresh --seed
Write-Host ""

Write-Host "6. Verifying database users..." -ForegroundColor Yellow
Write-Host "Users in database:"
sqlite3 database/database.sqlite "SELECT id, name, email, role FROM users;"
Write-Host ""

Write-Host "=== COMPLETE! ===" -ForegroundColor Green
Write-Host ""
Write-Host "Test credentials:" -ForegroundColor Cyan
Write-Host "  1. Email: admin@tencof.com" -ForegroundColor White
Write-Host "     Password: password123" -ForegroundColor White
Write-Host ""
Write-Host "  2. Email: test@example.com" -ForegroundColor White
Write-Host "     Password: password" -ForegroundColor White
