# Business Requirements Document (BRD) - Parkir 2077

## 1. Project Overview
**Parkir 2077** merupakan sistem manajemen parkir berstandar industri yang dirancang untuk mengelola operasional keluar-masuk kendaraan secara efisien dan akurat. Sistem ini memfasilitasi pencatatan kendaraan secara *real-time*, perhitungan tarif dinamis otomatis, pengaturan area parkir dan kapasitas ruang, serta pencetakan tiket parkir dan struk pembayaran.

## 2. Objective
Tujuan dari sistem ini adalah mendigitalisasi proses layanan parkir dari hulu ke hilir untuk:
- Mencegah kehilangan pendapatan akibat human-error.
- Mempercepat proses pelayanan gerbang parkir.
- Memberikan visibilitas laporan secara transparan.
- Memisahkan otorisasi antara pihak operasional (Petugas), manajerial (Admin), dan pengambil keputusan (Owner).

## 3. User Roles & Access 
Terdapat 3 level otorisasi di dalam sistem berdasarkan arsitektur **Role-Based Access Control (RBAC)**:
1. **Admin**
   - Menguasai seluruh master data sistem.
   - Punya kewenangan melakukan CRUD pada Manajemen User, Manajemen Role, Tarif Parkir, Area Parkir, Data Kendaraan.
   - Punya kewenangan melihat seluruh Log Aktivitas, Riwayat Transaksi, serta Rekap Laporan.
2. **Petugas**
   - Merupakan pengguna operasional (frontliner).
   - Memiliki akses *read-only* ke master Tarif dan Area Parkir.
   - Kewenangan mencatat masuknya kendaraan baru atau yang sudah ada (create/read Kendaraan).
   - Memproses pencatatan tiket kendaraan masuk dan mencetak struk keluar beserta perhitungan biaya.
3. **Owner**
   - Hanya memiliki akses pelaporan untuk tujuan manajerial (*Read-only*).
   - Memantau dasbor ringkasan transaksi, seluruh riwayat transaksi lintas waktu, dan merekapitulasi laporan finansial berdasarkan filter rentang tanggal.

## 4. Fitur Utama Sistem (Core Features)

### A. Manajemen Transaksi (Parking Handling)
- **Gate In (Masuk):** Pencatatan otomatis jam masuk, operator (petugas) bertugas, nomor plat, tipe kendaraan, dan area parkir. Pengecekan otomatis kapasitas area. Mencetak tiket masuk.
- **Gate Out (Keluar):** Pencatatan jam keluar. Sistem secara otomatis menghitung durasi waktu dan biaya secara dinamis (tarif dasar + pengali jam). Mencetak struk bukti pembayaran.

### B. Master Data Operasional
- **Tarif Parkir:** Klasifikasi perhitungan harga berdasarkan jenis kendaraan (Motor, Mobil, dsb) secara *hourly*.
- **Area Parkir:** Manajemen area sektor/zona parkir serta kontrol kapasitas maksimal.
- **Data Kendaraan:** Integrasi sistem pengenalan/data langganan kendaraan untuk mempercepat gate-in pada transaksi masa mendatang.

### C. Sistem Log & Pelaporan
- **Log Aktivitas:** Mencatat setiap tindakan di dalam sistem, khusus dikontrol untuk auditor/admin guna melacak modifikasi.
- **Rekap Laporan:** Laporan komprehensif terkait jumlah kendaraan masuk, keluar, dan agregasi total pendapatan.

## 5. Non-Functional Requirements (NFR)
- **Security:** Autentikasi ketat dan otorisasi terpusat memanfaatkan Laravel Fortify & Spatie Permissions. Validasi data sebelum penyimpanan.
- **Performance:** Penggunaan pagination untuk tabel-tabel data master yang banyak. Waktu proses pembuatan tiket sangat cepat (di bawah 1 detik).
- **Usability:** Antarmuka responsif dan terfokus (menggunakan ekosistem desain Flux UI) sehingga pengguna dapat bernavigasi tanpa pembelajaran teknis tinggi.
