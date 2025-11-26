#!/bin/bash
# Comprehensive Debug & Fix Script

echo "=== TENCOF PROJECT DEBUG & FIX ==="
echo ""

echo "1. Checking git status..."
git status
echo ""

echo "2. Showing latest commit..."
git log --oneline -1
echo ""

echo "3. Checking DatabaseSeeder.php content..."
echo "--- File should have TWO users (admin@tencof.com and test@example.com) ---"
grep -A 20 "public function run" database/seeders/DatabaseSeeder.php
echo ""

echo "4. Clearing all caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
echo ""

echo "5. Dropping and recreating database..."
php artisan migrate:fresh --seed
echo ""

echo "6. Verifying users in database..."
sqlite3 database/database.sqlite "SELECT id, name, email, role FROM users;"
echo ""

echo "=== DONE! ==="
echo ""
echo "If you still get 'This password does not use the Bcrypt algorithm' error:"
echo "  → Try these test accounts:"
echo "    Email: admin@tencof.com"
echo "    Password: password123"
echo ""
echo "    Email: test@example.com"
echo "    Password: password"
