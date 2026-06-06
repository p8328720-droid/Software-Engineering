SiRUKA — Sistem Informasi Rusak Kampus

Aplikasi web untuk pelaporan dan penanganan kerusakan fasilitas kampus
(ruang kelas dan laboratorium). Menggantikan proses manual dengan sistem
terpusat yang terstruktur, terdokumentasi, dan mudah dipantau.

Fitur Utama

Pelaporan kerusakan dengan lokasi, jenis fasilitas, deskripsi, dan foto bukti
Pelacakan status laporan secara real-time
Manajemen prioritas dan deadline penanganan
Otorisasi berbasis peran: Pelapor, Teknisi, dan Administrator
Dashboard statistik laporan berdasarkan status dan kategori

Peran Pengguna

Pelapor: Membuat dan memantau laporan kerusakan
Teknisi: Menangani laporan dan memperbarui status perbaikan
Administrator Mengelola pengguna, ruangan, fasilitas, dan prioritas

Tech Stack

Backend   Laravel (PHP)
Frontend  Blade Template, HTML, CSS, JavaScript
Database  MySQL atau SQLite

Cara Menjalankan

git clone <link-repository>
cd Software-Engineering
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

Akses aplikasi di http://127.0.0.1:8000
