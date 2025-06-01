# 🏠 NganjukMase - Sistem Informasi Bantuan Rumah Tidak Layak Huni

**NganjukMase** adalah website informasi dan pengelolaan data bantuan rumah tidak layak huni (Rutilahu) di Kabupaten Nganjuk, Jawa Timur. Website ini dikembangkan sebagai media kolaborasi dengan **Dinas Perumahan Rakyat, Kawasan Permukiman dan Pertanahan Kabupaten Nganjuk**, guna memudahkan proses pendataan calon penerima bantuan, verifikasi data, serta penyampaian informasi berita dan jadwal kegiatan dinas terkait.

## 🔧 Teknologi yang Digunakan

Proyek ini dibangun menggunakan teknologi-teknologi modern dan populer untuk memastikan performa, skalabilitas, dan kemudahan pengembangan:

-   **Backend**:

    -   [Laravel 11](https://laravel.com/docs/11.x) – Framework PHP yang kuat dan mudah digunakan untuk pengembangan aplikasi web.
    -   [MySQL](https://www.mysql.com/) – Database relasional yang digunakan untuk menyimpan seluruh data aplikasi.

-   **Frontend**:
    -   [Bootstrap 5](https://getbootstrap.com/) – Framework CSS/JS untuk desain antarmuka yang responsif dan modern.
    -   HTML5 & CSS3
    -   JavaScript Vanilla untuk interaksi klien

## 📋 Fitur Utama Aplikasi

### Pengunjung Umum

![Landing Page](screenshoot/satu.png)

-   Melihat informasi berita terbaru dari Dinas Perumahan Rakyat, Kawasan Permukiman dan Pertanahan Kabupaten Nganjuk.
-   Melihat jadwal kegiatan dinas.
-   Menampilkan jumlah data CPB.
-   Menampilkan jumlah data Verifikasi.
-   Menampilkan jumlah data Tidak Terverifikasi.
-   Menampilkan jumlah data penerima RTLH.

### Role: Admin

![Admin Page](screenshoot/dua.png)

-   Menu profile.
-   Menu ganti password.
-   Menu semua aktivasi pengguna.
-   Mengelola berita (tambah, edit, hapus).
-   Mengelola jadwal kegiatan dinas (tambah, edit, hapus).
-   Bisa edit dan hapus data CPB yang diinput oleh petugas desa.
-   Melihat hasil verifikasi data.
-   Melihat laporan dan rekap data CPB per desa/kelurahan.
-   Melihat laporan dan rekap data Verifikasi per desa/kelurahan.

### Role: Petugas Input CPB (Kepala Desa)

![Ptugas Page](screenshoot/tiga.png)

-   Menu profile.
-   Menu ganti password.
-   Hanya bisa menginput/menambahkan data calon penerima bantuan (CPB) dari wilayah desa masing-masing.
-   Tidak diperbolehkan melihat data dari desa lain.
-   Hanya bisa mengedit data untuk desanya sendiri.

## 📥 Cara Menjalankan Proyek (untuk Developer)

### Prasyarat

Pastikan Anda sudah menginstal:

-   PHP >= 8.1
-   Composer
-   MySQL Server
-   Git

## 📬 Kontak

Jika Anda memiliki pertanyaan atau ingin bekerja sama lebih lanjut, silakan hubungi kami melalui email resmi Dinas Perumahan Rakyat, Kawasan Permukiman dan Pertanahan Kabupaten Nganjuk.

📩 **Hajuenter**  
Email: [bahrulahmad1945@gmail.com](mailto:bahrulahmad1945@gmail.com)

## 🌐 Website: https://nganjukmase.pbltifnganjuk.com/

## 👨‍💻 Developer: BuildUp / TIF NGK B2

### Clone Repository

```bash
git clone https://github.com/hajuenter/web_BuildUp.git
cd nganjukmase
```
