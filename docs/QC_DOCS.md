# Dokumen Quality Control (QC) & Uji Skenario - Parkir 2077

Dokumen ini merinci checklist pengujian kualitas aplikasi guna memastikan fitur-fitur yang dirilis bebas cacat (bug) dan berjalan selaras dengan dokumen Business Requirements.

---

## Skenario Utama (UAT - User Acceptance Testing)

### 1. Skenario Autentikasi dan Otorisasi (RBAC)
- [ ] **[Login Multi Role]** Menguji proses Login dengan akun Admin, Petugas, dan Owner. Memastikan ketiga role masuk ke Dashboard mereka masing-masing.
- [ ] **[Restriksi Halaman]** Petugas mencoba memaksa (`force-navigate`) ke URL `/admin/users` atau `/admin/tarif`. Sistem wajib menolak dan me-lempar layar `403 Unauthorized`.
- [ ] **[Logout]** Memastikan sesi dibersihkan sempurna setelah menekan tombol keluar.

### 2. Skenario Master Data (Admin-only)
- [ ] **[Tarif Baru]** Memasukkan data tarif menggunakan nominal valid (integer). Simpan dan pastikan tersimpan di *database*.
- [ ] **[Area Parkir Baru]** Memasukkan Area ("Gedung A") dengan kapasitas 10. Pastikan nilai `terisi` berstatus awal 0.
- [ ] **[Validasi Data]** Mencoba menanamkan form *Area Parkir* kosong. Pastikan penanganan *Request Validation Laravel* mencegahnya dengan pesan peringatan di UI.

### 3. Skenario Transaksi (Alur Petugas Utama)
- [ ] **[Gate In - Kendaraan Lama]** Petugas memilih plat nomor yang sudah tercatat di sistem dari dropdown, memilih tarif, lalu catat waktu masuk. Transaksi harus berhasil, cetak *halaman tiket* tampil, dan kapasitas Area di *Dashboard* bertambah 1 `terisi`.
- [ ] **[Gate In - Kendaraan Baru]** Petugas menginput informasi plat baru yang asing beserta data warna kendaraannya. Pastikan di *database*, entri baru tercatat diam-diam di tabel kendaraan, dan transaksi berlanjut tanpa hambatan.
- [ ] **[Terintegrasi Penuh]** Memastikan Log Aktivitas memunculkan baris rekaman *"Petugas [Nama] mencatat Kendaraan masuk..."*.

### 4. Skenario Out & Perhitungan (Kalkulasi Cerdas)
- [ ] **[Gate Out]** Mengubah status kendaraan dari tabel berjalan menjadi "Keluar".
- [ ] **[Akurasi Tarif 1: Normal]** Masuk jam 10.00, keluar jam 12.00 (Tarif motor = Rp 2000). Total biaya harus terhitung presisi sebesar = Rp 4000.
- [ ] **[Akurasi Tarif 2: Minimal Jam]** Masuk jam 13.00, keluar 13.15. Karena durasinya kurang dari 1 jam (15 menit), fungsi pembulatan harus tetap menghargai batas minimal tagihan Rp 2000 (1x lipat jam dasar).
- [ ] **[Kapasitas Pulih]** Mengecek data kapasitas area: "terisi", harus berkurang 1 usai kendaraan ditekan Keluar.

### 5. Skenario Laporan Manajerial (Uji Owner)
- [ ] **[Filter Tanggal]** Pindah login ke *Owner*. Ubah input filter start date ke 1 bulan lalu dan end date ke hari ini. Eksekusi filter.
- [ ] **[Akurasi Sum]** Memastikan seluruh sumbu perbandingan (Jumlah Transaksi Keluar, Total Pendapatan) menampilkan angka akurat bersumber dari sekumpulan kendaraan yang baru saja di-uji diskenario Gate Out.
- [ ] **[Cegat Data Aktif]** Pastikan kendaraan yang *masih berada di area parkir* belum dimasukkan ke data finansial laporan agar menghindari nilai mengambang (floating).

---

## Log Cacat (Bug Log Tracker Form)

Jika ditemukan masalah di skenario atas, catat pada log berikut:

| ID | Modul Terkait | Deskripsi Isu (Langkah Replikasi) | Status Bug | Prioritas |
|:---|:---|:---|:---|:---|
| QC-001 | Modul Contoh | (Contoh) Gagal load saat masuk ke Transaksi. | Terbuka | P1 - Tinggi |
| ... | ... | ... | ... | ... |

*(Digunakan manual saat tim QA meninjau sistem produksi)*
