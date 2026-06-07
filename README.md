# SiRUKA
Sistem Informasi Rusak Kampus

Aplikasi web untuk pelaporan dan penanganan kerusakan fasilitas kampus
(ruang kelas dan laboratorium). Menggantikan proses manual dengan sistem
terpusat yang terstruktur, terdokumentasi, dan mudah dipantau.


## Fitur Utama

Pelaporan kerusakan
Sertakan lokasi, jenis fasilitas, deskripsi, dan foto bukti

Pelacakan status real-time
Status laporan diperbarui langsung oleh teknisi

Manajemen prioritas dan deadline
Admin mengatur tingkat urgensi setiap laporan

Otorisasi berbasis peran
Hak akses berbeda untuk Pelapor, Teknisi, dan Administrator

Dashboard statistik
Ringkasan laporan berdasarkan status dan kategori


## Peran Pengguna

Pelapor
Membuat dan memantau laporan kerusakan

Teknisi
Menangani laporan dan memperbarui status perbaikan

Administrator
Mengelola pengguna, ruangan, fasilitas, dan prioritas


## Tech Stack

Backend
Laravel (PHP)

Frontend
Blade Template, HTML, CSS, JavaScript

Database
MySQL untuk production, SQLite untuk development



## Cara Menjalankan
### Prasyarat
- PHP >=8.2
- Composer
- MySQL/MariaDB

### Langkah
1. Clone repository
   git clone <link-repository>

2. Masuk ke folder project
   cd Software-Engineering

3. Install dependency
   composer install

4. Salin file environment
   cp .env.example .env

5. Generate application key
   php artisan key:generate

6. Jalankan migration
   php artisan migrate

7. Jalankan server
   php artisan serve

## Menjalankan dengan Docker
### Prasyarat
- Docker
- Docker Compose
### Clone Repository
- git clone <repository-url>
- cd Software-Engineering
### Jalankan Aplikasi
Karena image aplikasi telah tersedia, cukup jalankan:
- docker compose up -d
### Akses Aplikasi
Setelah seluruh container berjalan, aplikasi dapat diakses melalui:
http://localhost:8000
### Menghentikan Aplikasi
- docker compose down
### Melihat Log Container
- docker compose logs -f

Akses aplikasi di http://127.0.0.1:8000
