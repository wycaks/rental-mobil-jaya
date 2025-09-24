# Rental Mobil Jaya

Aplikasi web sederhana untuk manajemen dan penyewaan mobil berbasis PHP dan MySQL.

## Fitur

- **Autentikasi**: Register, login, dan logout untuk user dan admin.
- **Dashboard**: Tampilan dashboard berbeda untuk admin dan user.
- **Manajemen Mobil**: Admin dapat menambah, mengedit, dan menghapus data mobil beserta gambar.
- **Manajemen Pelanggan**: Admin dapat mengelola data pelanggan.
- **Transaksi Sewa**: User dapat melakukan penyewaan mobil, melihat riwayat transaksi, dan admin dapat mengelola status transaksi.
- **Upload Gambar**: Mendukung upload gambar mobil.
- **Status Mobil**: Otomatis mengubah status mobil saat disewa/dikembalikan.

## Struktur Folder

```
config.php
dashboard.php
index.php
admin/
  mobil/
    mobil.php
    pelanggan.php
    pengembalian.php
    tambah.php
    transaksi.php
    upload/
auth/
  login.php
  logout.php
  register.php
user/
  sewa.php
  transaksi.php
uploads/
  mobil/
    (gambar mobil)
```

## Instalasi

1. **Clone atau copy** project ke folder web server (misal: `htdocs` atau `www`).
2. **Import database**  
   Buat database MySQL dengan nama `rental_mobil_jaya` dan import struktur tabel sesuai kebutuhan.
3. **Konfigurasi koneksi**  
   Edit file [`config.php`](../config.php) jika diperlukan (user, password, nama database).
4. **Jalankan aplikasi**  
   Akses melalui browser:  
   ```
   http://localhost/rental-mobil-jaya/
   ```

## Akun Default

- **Admin**:  
  - Email: (buat manual di tabel `users` dengan `role_id=1`)
- **User**:  
  - Register melalui halaman register.

## Teknologi

- PHP (Native)
- MySQL
- TailwindCSS & Bootstrap (CDN)
- HTML

## Catatan

- Password disimpan dengan MD5 (tidak direkomendasikan untuk produksi).
- Pastikan folder `uploads/mobil/` dapat ditulis oleh web server.
- Untuk menambah admin, edit langsung di database.

---

&copy; <?= date("Y") ?> Rental Mobil Jaya

## 🗃️ screenshot

![alt text](<Cuplikan layar 2025-09-17 110631.png>) ![alt text](<Cuplikan layar 2025-09-24 122110.png>) ![alt text](<Cuplikan layar 2025-09-24 122223 - Salin.png>)
---