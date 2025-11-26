# 🔧 TROUBLESHOOTING: "Seed yang Lama" Error

## Masalah
- `git pull` sudah dilakukan
- Tapi login masih gagal dengan "This password does not use the Bcrypt algorithm"
- Atau DatabaseSeeder.php masih menunjukkan kode lama

## Solusi - PILIH SATU:

### ✅ SOLUSI 1: Jalankan Reset Script (RECOMMENDED)
**Windows:**
```bash
reset_project.bat
```

**Mac/Linux:**
```bash
bash reset_project.bat  # atau chmod +x reset_project.bat && ./reset_project.bat
```

Tunggu sampai selesai. Server akan langsung running di `http://127.0.0.1:8000`

---

### ✅ SOLUSI 2: Manual Commands
Jalankan commands ini di terminal (satu per satu):

```bash
# 1. Stop server (Ctrl+C jika sedang running)

# 2. Pull terbaru
git pull origin faiz

# 3. Clear cache
php artisan cache:clear
php artisan config:clear  
php artisan view:clear

# 4. Reset database dengan seeder
php artisan migrate:fresh --seed

# 5. Check users di database
sqlite3 database/database.sqlite "SELECT id, name, email, role FROM users;"
# Output harus seperti ini:
# 1|Admin User|admin@tencof.com|admin
# 2|Test User|test@example.com|kasir

# 6. Jalankan server
php artisan serve
```

---

### ✅ SOLUSI 3: Cek File Sebelum Menjalankan Seeding

Buka file `database/seeders/DatabaseSeeder.php` dan pastikan isinya seperti ini:

```php
public function run(): void
{
    // Create test users with known credentials
    User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@tencof.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        'role' => 'admin',
    ]);

    User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'role' => 'kasir',
    ]);
}
```

**Jika berbeda**, berarti git pull belum sepenuhnya update:
- Close VS Code / Text Editor
- Jalankan: `git reset --hard origin/faiz`
- Buka kembali

---

## ✅ Test Credentials (Setelah Fix)

**Account 1 - Admin:**
```
Email: admin@tencof.com
Password: password123
```

**Account 2 - Kasir:**
```
Email: test@example.com
Password: password
```

Login di: `http://127.0.0.1:8000/login`

---

## 🐛 Masih Error?

Jalankan debug script untuk melihat detailed info:

**Windows (PowerShell):**
```bash
powershell -ExecutionPolicy Bypass -File debug.ps1
```

**Mac/Linux:**
```bash
bash debug.sh
```

Script ini akan:
1. Show git status
2. Show DatabaseSeeder.php content
3. Clear all cache
4. Reset database
5. Show users yang ter-seed di database

---

## 💡 Fast Facts

- ✅ DatabaseSeeder.php: Updated dengan 2 users + password hashing
- ✅ Database: Ready dengan users (admin@tencof.com & test@example.com)  
- ✅ Passwords: Sudah di-hash dengan Bcrypt
- ✅ Server: Running di http://127.0.0.1:8000

**Kalau sudah ikuti steps ini dan masih error, laporin error messagenya lengkap! 🎯**
