# Dokumentasi Debugging - Parkir 2077

Dokumen ini mendeskripsikan proses pencarian dan penanganan beberapa *bug* (cacat perangkat lunak) yang ditemukan selama fase pengembangan sistem Parkir 2077.

## 1. Isu Integrasi Database (Driver SQLite -> MySQL)

**Deskripsi Isu:**  
Pada tahap awal migrasi database, muncul peringatan *Exception* `"could not find driver"` ketika berusaha menjalankan perintah `php artisan migrate`.

**Proses Debugging:**  
1. Mengecek daftar ekstensi PHP aktif dengan `php -m | grep pdo`. Ditemukan bahwa driver untuk SQLite belum terinstal.
2. Memeriksa konfigurasi `.env`. Disadari bahwa aplikasi lebih membutuhkan MySQL dibandingkan SQLite untuk performa relasional tingkat lanjut.

**Solusi (Resolusi):**  
Diubah `.env` untuk menggunakan koneksi `mysql` secara langsung. Nilai `DB_CONNECTION` diganti dari `sqlite` ke `mysql`. Database `parkir2077` diinisialisasi melalui XAMPP/MySQL Server, kemudian migrasi dilakukan dengan lancar.

---

## 2. Isu Kapasitas Area Mengambang (Minus/Lebih Kapasitas)

**Deskripsi Isu:**  
Ketika *Petugas* menekan tombol "Keluar" berulang kali (spam klik) pada baris transaksi yang sama sebelum halaman sempat direfresh, nilai kolom `terisi` pada Area Parkir dapat menjadi di bawah 0 (negatif).

**Proses Debugging:**  
1. Menyimulasikan *Race Condition* di menu *Gate Out*.
2. Membaca kueri pada `Controller` Transaksi untuk melihat apakah pengurangan kuota area dilakukan tanpa validasi status kendaraan.

**Solusi (Resolusi):**  
Di dalam `TransaksiController` di-implementasikan proteksi `if ($transaksi->status == 'keluar') { return }`. Sehingga jika kendaraan sudah berstatus keluar, pengurangan nilai `terisi` tidak dijalankan lebih dari 1 kali.

---

## 3. Kesalahan Komputasi Harga (Minimum Charge)

**Deskripsi Isu:**  
Pada saat uji coba, kendaraan yang terparkir hanya selama 15 menit ketika keluar tidak dikenakan biaya (dihitung sebagai 0 Jam = Rp 0). Hal ini merugikan pihak pengelola parkir.

**Proses Debugging:**  
1. Mengecek fungsi perhitungan matematika pada Controller `update()` saat transaksi ditutup.
2. Ditemukan penggunaan algoritma fungsi *diffInHours* standar yang melakukan pembulatan nilai ke bawah secara default.

**Solusi (Resolusi):**  
Fungsi `diffInHours()` diganti dengan kalkulasi minimal matematis: memastikan nilai setidaknya dikenakan charge "1 jam" pertama. 
```php
$durasi = max(1, ceil($waktu_masuk->floatDiffInHours($waktu_keluar)));
```
Batas minimal ini menyelesaikan isu tagihan yang nihil untuk kendaraan dengan waktu parkir di bawah durasi dasar.
