# 🍽️ Warung App - Panduan Instalasi

## Cara Setup (XAMPP)

### Langkah 1 - Copy File
Ekstrak folder `warung` ke dalam:
```
C:\xampp\htdocs\warung
```

### Langkah 2 - Setup Database
1. Buka browser → http://localhost/phpmyadmin
2. Klik **"New"** untuk buat database baru
3. Nama database: `warung_db` → klik **Create**
4. Klik tab **"SQL"** 
5. Copy-paste isi file `database.sql` → klik **Go**

### Langkah 3 - Jalankan Aplikasi
Buka browser → http://localhost/warung

---

## Akun Login

| Role  | Username | Password |
|-------|----------|----------|
| Admin | admin    | admin123 |
| User  | budi     | user123  |
| User  | siti     | user123  |

---

## Struktur Folder

```
warung/
├── index.php          ← Halaman Login
├── logout.php         ← Proses Logout
├── config.php         ← Koneksi Database
├── database.sql       ← Setup Database
├── assets/
│   └── css/
│       └── style.css  ← Tampilan CSS
├── admin/
│   ├── dashboard.php  ← Dashboard Admin
│   ├── menu.php       ← CRUD Menu (Tambah/Edit/Hapus)
│   └── pesanan.php    ← Kelola Pesanan
└── user/
    ├── menu.php       ← Lihat Menu & Pesan
    └── pesanan_saya.php ← Riwayat Pesanan User
```

---

## Fitur Aplikasi

### Panel Admin
- ✅ Login dengan role admin
- ✅ Dashboard dengan statistik
- ✅ **CREATE** - Tambah menu baru
- ✅ **READ** - Lihat semua menu
- ✅ **UPDATE** - Edit nama, harga, kategori menu
- ✅ **DELETE** - Hapus menu
- ✅ Kelola status pesanan (Pending → Diproses → Selesai)

### Panel User
- ✅ Login dengan role user
- ✅ Lihat daftar menu makanan & minuman
- ✅ Pesan menu dengan pilihan jumlah
- ✅ Lihat riwayat pesanan sendiri
- ✅ Cek status pesanan (real-time)
