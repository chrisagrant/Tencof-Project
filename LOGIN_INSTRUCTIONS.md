# 🔐 Petunjuk Login Tencof Application

## ⚠️ PENTING: Akses URL yang BENAR!

**HARUS akses dari:** `http://127.0.0.1:8000`

❌ **JANGAN akses dari:**
- `localhost:8000`
- `127.0.0.1:8080` (port salah)
- Domain lain / IP address lain

---

## 📝 Test Accounts

### Account 1 - Admin (Full Access)
```
Email: admin@tencof.com
Password: password123
Role: Admin (dapat add/edit/delete semua data)
```

### Account 2 - Kasir (Limited Access)
```
Email: test@example.com
Password: password
Role: Kasir (dapat view dan record stock history)
```

---

## 🚀 Cara Login

1. **Buka browser** dan pergi ke: `http://127.0.0.1:8000`
2. **Klik "Login"** di halaman utama
3. **Masukkan email dan password** dari salah satu account di atas
4. **Klik "Sign In"**
5. ✅ Akan redirect ke dashboard

---

## ✅ Fitur yang bisa ditest

### Dashboard
- Lihat total Bahan Baku, Stock, dan Supplier
- Lihat aktivitas stock terakhir

### Bahan Baku
- ➕ Tambah Bahan Baku baru
- ✏️ Edit Bahan Baku
- 🗑️ Hapus Bahan Baku
- 🔍 Search dan Pagination

### Satuan
- ➕ Tambah Satuan
- ✏️ Edit Satuan
- 🗑️ Hapus Satuan

### Supplier
- ➕ Tambah Supplier
- ✏️ Edit Supplier (nama, telepon, alamat)
- 🗑️ Hapus Supplier

### Stock
- ➕ Tambah Stock baru
- ✏️ Edit Stock (quantity, harga, supplier)
- 🗑️ Hapus Stock

### Stock History
- 📊 Lihat riwayat stock (IN/OUT)
- 🔍 Filter berdasarkan Bahan Baku dan Tipe

### Users
- 👥 Lihat list semua users

---

## 🔄 Test Data Persistence

**Untuk memastikan data persists setelah refresh:**

1. Login dengan admin account
2. Tambah "Bahan Baku" baru (misal: "Gula Pasir")
3. Tekan **F5** atau **Ctrl+R** untuk refresh
4. ✅ Data "Gula Pasir" masih ada = **WORKING!**

---

## 🐛 Troubleshooting

### Error: "This password does not use the Bcrypt algorithm"
- ✅ **Solusi:** Password di database sudah di-reset
- 🔄 Silakan logout dan login ulang dengan credentials di atas

### Error: "Cannot connect"
- ❌ **Pastikan URL benar:** `http://127.0.0.1:8000` (bukan localhost:8000)
- ❌ **Pastikan server running:** Lihat terminal apakah ada "Server running on..."
- 🔄 Jika tidak, jalankan: `php artisan serve`

### Data hilang setelah refresh
- ✅ **Ini NORMAL jika tidak login** (page redirect ke login)
- ✅ **Jika sudah login:** Data harus tetap ada (stored di database)

---

## 📱 Database Status
- ✅ SQLite database aktif
- ✅ 2 test users sudah created
- ✅ Password sudah di-hash dengan Bcrypt
- ✅ Semua table sudah dibuat

---

## 🎯 Siap Testing!
Cukup akses `http://127.0.0.1:8000/login` dan gunakan credentials di atas!
