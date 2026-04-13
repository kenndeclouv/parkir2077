# User Flowchart — Parkir 2077

Diagram alur pengguna berdasarkan 3 role yang ada di sistem.

---

## Alur Umum (Semua Role)

```mermaid
flowchart TD
    START([🌐 Buka Aplikasi]) --> LOGIN[/Halaman Login/]
    LOGIN -->|Credentials valid| AUTH{Autentikasi}
    LOGIN -->|Salah password| LOGIN
    AUTH -->|Role: admin| ADMIN_DASH[Dashboard Admin]
    AUTH -->|Role: petugas| PETUGAS_DASH[Dashboard Petugas]
    AUTH -->|Role: owner| OWNER_DASH[Dashboard Owner]

    ADMIN_DASH --> LOGOUT
    PETUGAS_DASH --> LOGOUT
    OWNER_DASH --> LOGOUT
    LOGOUT([🚪 Logout]) --> LOGIN
```

---

## 🔴 Admin

> Akses penuh ke seluruh master data dan konfigurasi sistem.

```mermaid
flowchart TD
    A([Admin Login]) --> DASH[Dashboard\nLihat statistik & ringkasan]

    DASH --> UM[Manajemen User]
    UM --> UM1[Lihat daftar user]
    UM --> UM2[Tambah user baru]
    UM --> UM3[Edit user & reset password]
    UM --> UM4[Hapus user]
    UM2 & UM3 --> UM_ROLE[Assign Role ke User]

    DASH --> RM[Manajemen Role]
    RM --> RM1[Lihat daftar role & permission]
    RM --> RM2[Buat role baru]
    RM --> RM3[Edit permission role]
    RM --> RM4[Hapus role]

    DASH --> TM[Tarif Parkir]
    TM --> TM1[Lihat daftar tarif]
    TM --> TM2[Tambah tarif baru\nMotor / Mobil / Lainnya]
    TM --> TM3[Edit tarif per jam]
    TM --> TM4[Hapus tarif]

    DASH --> AM[Area Parkir]
    AM --> AM1[Lihat daftar area & kapasitas]
    AM --> AM2[Tambah area baru]
    AM --> AM3[Edit nama & kapasitas]
    AM --> AM4[Hapus area]

    DASH --> KM[Data Kendaraan]
    KM --> KM1[Lihat semua kendaraan terdaftar]
    KM --> KM2[Tambah kendaraan]
    KM --> KM3[Edit data kendaraan]
    KM --> KM4[Hapus kendaraan]

    DASH --> LOG[Log Aktivitas]
    LOG --> LOG1[Lihat riwayat semua aktivitas\nmasuk/keluar kendaraan + timestamp + petugas]

    DASH --> TRX[Transaksi Parkir]
    TRX --> TRX_P[Alur sama seperti Petugas ↓]

    DASH --> LAP[Rekap Laporan]
    LAP --> LAP_O[Alur sama seperti Owner ↓]
```

---

## 🟡 Petugas

> Fokus pada operasional harian: catat kendaraan masuk & keluar, cetak tiket/struk.

```mermaid
flowchart TD
    P([Petugas Login]) --> DASH[Dashboard\nLihat kendaraan aktif & slot area]

    DASH --> TRX[Transaksi Parkir]

    TRX --> MASUK[Catat Kendaraan Masuk]
    MASUK --> CEK{Kendaraan\nsudah terdaftar?}
    CEK -->|Ya| PIL_K[Pilih dari dropdown kendaraan]
    CEK -->|Tidak| NEW_K[Isi form kendaraan baru\nPlat · Jenis · Warna · Pemilik]
    PIL_K & NEW_K --> ISI[Pilih Tarif + Area Parkir\nIsi Waktu Masuk]
    ISI --> SUBMIT[Submit Form]
    SUBMIT --> TIKET[🖨️ Halaman Tiket Masuk\nAuto-print]
    TIKET --> TRX

    TRX --> DAFTAR[Lihat Daftar Transaksi\nFilter: Masuk / Keluar / Semua]
    DAFTAR --> CEK_S{Status\nkendaraan?}
    CEK_S -->|Masuk| TIKET_UL[Cetak Ulang Tiket]
    CEK_S -->|Masuk| KELUAR[Proses Kendaraan Keluar]
    KELUAR --> ISI_K[Isi Waktu Keluar]
    ISI_K --> HITUNG[Sistem hitung durasi & biaya\nDurasi jam × tarif/jam\nMinimal 1 jam]
    HITUNG --> STRUK[🖨️ Halaman Struk Keluar\nAuto-redirect]
    STRUK --> TRX
    CEK_S -->|Keluar| STRUK_UL[Cetak Ulang Struk]
```

---

## 🟢 Owner

> Akses read-only ke laporan keuangan dan rekap transaksi.

```mermaid
flowchart TD
    O([Owner Login]) --> DASH[Dashboard\nLihat statistik ringkasan]
    DASH --> TRX[Lihat Daftar Transaksi\nRead-only, semua status]
    DASH --> LAP[Rekap Laporan]
    LAP --> FILTER[Filter Rentang Tanggal\ndari → sampai]
    FILTER --> HASIL[Tampilkan hasil:\n• Total transaksi selesai\n• Total pendapatan\n• Breakdown per jenis kendaraan\n• Tabel transaksi lengkap]
    HASIL --> FILTER
```

---

## Ringkasan Akses per Role

| Fitur | Admin | Petugas | Owner |
|---|:---:|:---:|:---:|
| Dashboard | ✅ | ✅ | ✅ |
| Manajemen User | ✅ | ❌ | ❌ |
| Manajemen Role | ✅ | ❌ | ❌ |
| Tarif Parkir (CRUD) | ✅ | 👁️ read | ❌ |
| Area Parkir (CRUD) | ✅ | 👁️ read | ❌ |
| Data Kendaraan (CRUD) | ✅ | ✅ create+read | ❌ |
| Log Aktivitas | ✅ | ❌ | ❌ |
| Catat Kendaraan Masuk | ✅ | ✅ | ❌ |
| Proses Kendaraan Keluar | ✅ | ✅ | ❌ |
| Cetak Tiket Masuk | ✅ | ✅ | ❌ |
| Cetak Struk Keluar | ✅ | ✅ | ❌ |
| Rekap Laporan | ✅ | ❌ | ✅ |
| Lihat Transaksi (read) | ✅ | ✅ | ✅ |
