# Entity Relationship Diagram (ERD) - Parkir 2077

Dokumen ini memuat diagram relasi entitas untuk basis data aplikasi Parkir 2077.

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email
        string password
    }

    tb_kendaraan {
        bigint id_parkir PK
        string plat_nomor
        string jenis_kendaraan
        string warna
        string pemilik
        bigint id_user FK "nullable"
    }

    tb_tarif {
        bigint id_tarif PK
        string jenis_kendaraan
        int tarif_per_jam
    }

    tb_area_parkir {
        bigint id_area PK
        string nama_area
        int kapasitas_maksimal
        int terisi
    }

    tb_transaksi {
        bigint id_parkir PK
        bigint id_kendaraan FK
        datetime waktu_masuk
        datetime waktu_keluar
        bigint id_tarif FK
        int durasi_jam
        decimal biaya_total
        enum status "masuk/keluar"
        bigint id_user FK "Petugas"
        bigint id_area FK
    }

    tb_log_aktivitas {
        bigint id_log PK
        bigint id_user FK
        string aktivitas
        datetime waktu_aktivitas
    }

    %% Relationships
    users ||--o{ tb_kendaraan : "Mencatat / Memiliki"
    users ||--o{ tb_transaksi : "Memproses"
    users ||--o{ tb_log_aktivitas : "Melakukan"
    
    tb_kendaraan ||--o{ tb_transaksi : "Memiliki"
    tb_tarif ||--o{ tb_transaksi : "Dikenakan pada"
    tb_area_parkir ||--o{ tb_transaksi : "Menempati"
```

## Deskripsi Tabel & Relasi

1. **`users`**
   Merupakan tabel inti untuk mencatat pengguna (Admin, Petugas, Owner). Diatur aksesnya menggunakan Spatie Permission (Role-Based Access Control).

2. **`tb_kendaraan`**
   Menyimpan profil kendaraan yang masuk/terdaftar. Berelasi dengan `users` (siapa yang mendaftarkan/memiliki akun, bersifat opsional).

3. **`tb_tarif`**
   Master data klasifikasi harga parkir berdasarkan jenis kendaraan (Motor, Mobil, dll) dan perhitungannya dihitung per-jam.

4. **`tb_area_parkir`**
   Tabel kapasitas ruang dan gedung. Kolom `terisi` nilainya di-update secara dinamis oleh Transaksi.

5. **`tb_transaksi`**
   Tabel pusat. Menghubungkan semua data (`tb_kendaraan`, `tb_tarif`, `tb_area_parkir`, `users`). Menampung hitungan akhir operasional.

6. **`tb_log_aktivitas`**
   Digunakan untuk keperluan *tracing* audit. Merekam siapa (*trigger* dari user ID) yang melakukan manipulasi data (*aktivitas*) dan kapan waktunya.
