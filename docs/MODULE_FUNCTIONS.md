# Dokumen Fungsi Penting Per Modul - Parkir 2077

Dokumen ini menjelaskan fungsionalitas dan fungsi-fungsi kunci dari fitur backend (`Controller`) yang esensial dalam aplikasi.

---

## 1. Modul Transaksi (`TransaksiController`)
Merupakan "jantung" aplikasi dengan logika bisnis paling kompleks terkait pengelolaan *gate in/out*.

- **`index()` & `create()`**
  - **Fungsi**: Memuat daftar riwayat transaksi berdasarkan filter status (masuk/keluar). Memuat relasi data kendaraan, petugas, tarif, dan area terkait.
- **`store()`**
  - **Fungsi**: Mencatat kendaraan *Masuk*.
  - **Logika Kunci**: Mengecek apakah ini pendaftaran kendaraan baru atau kendaraan lama. Meregistrasi ID kendaraan jika baru. Membuat data transaksi dengan timestamp `waktu_masuk`, meng-assign operator (Petugas id), dan otomatis **menambah jumlah kapasitas terisi (`terisi`) pada tabel terkait Area Parkir**.
  - Menyisipkan log secara otomatis bahwa petugas mencatat kendaraan dengan plat tertentu masuk.
- **`update()`**
  - **Fungsi**: Meresolusikan kendaraan *Keluar*.
  - **Logika Kunci**: Melakukan injeksi `waktu_keluar` dari sisi client, kemudian melakukan perhitungan perbedaan jam (selisih waktu). Mengeksekusi rumus **`durasi * tarif_per_jam`** (dengan *fallback* minimun durasi pembayaran 1 jam). Mengkalkulasi `biaya_total`, lalu mendaftarkan transaksi tersebut menjadi 'keluar'. Mengurangi jumlah unit dari `terisi` pada Area Parkir yang dialokasikan.
- **`tiket()` & `struk()`**
  - **Fungsi**: Memanggil relasi Data yang lengkap dan menyajikannya dalam format cetak (POS / thermal print view layout).

---

## 2. Modul Area Parkir (`AreaParkirController`)
Mengelola data kapasitas ruang parkir.

- **`store()` / `update()`**
  - **Fungsi**: Menambahkan atau mengubah detail nama area dan parameter **kapasitas maksimal**.
- **Logika Eksternal yang Terkait (Side Effects)**: Fungsionalitas Modul Transaksi sangat bergantung pada kolom kapasitas di model ini untuk memastikan tidak adanya kelebihan muatan di suatu area.

---

## 3. Modul Tarif (`TarifController`)
Mengatur parameter biaya.

- **Fungsi Utama**: CRUD jenis kendaraan dan besaran `tarif_per_jam`.
- **Integrasi**: Tarif dipatok dan diamankan sebagai *foreign key* dalam `tb_transaksi`. Modifikasi tarif per jam yang baru tidak memengaruhi catatan historis transaksi lama karena nilai yang lama mungkin berbeda, tetapi integrasi harga mutlak terjadi pada fungsi `update()` proses *Kendaraan Keluar* dari transaksi yang sedang berjalan berdasarkan referensi tarif yang terhubung di id kendaraan tersebut.

---

## 4. Modul Manajemen Hak Akses (`RoleController`, `UserController`)
Terpusat pada pengelolaan otorisasi pengguna.

- **`assignRole()` / Middleware**:
  - **Fungsi**: Memastikan setiap pengguna hanya dapat memanggil *route* berdasarkan tingkat otorisasi RBAC (Admin, Petugas, Owner). Digunakan secara global di konstruktor *controller*.

---

## 5. Modul Pelaporan (`LaporanController`)
Antarmuka analisis untuk manajerial (Owner & Admin).

- **`index()`**
  - **Fungsi**: Mengolah agregasi kueri dari tabel `tb_transaksi` berstatus 'keluar' berdasar rentang tanggal spesifik (tanggal awal s.d. tanggal akhir).
  - Melakukan komputasi perhitungan SUM `biaya_total` untuk menjumlahkan rekap pendapatan finansial serta mem-breakdown total distribusi jenis kendaraan yang terparkir.

---

## 6. Modul Log Aktivitas (`LogAktivitasController`)
Sistem audit internal aplikasi.

- **Fungsi**: Bertindak sebagai *viewer* (hanya dibaca oleh Admin) bagi riwayat yang dihasilkan oleh aksi aplikasi. Semua penyisipan data berasal dari *Observer* atau secara eksplisit dipanggil dari event-event mutasi seperti (Masuk/Keluarnya Kendaraan di *TransaksiController*, atau perubahan pada data User).
