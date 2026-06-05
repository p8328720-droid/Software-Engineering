SIRUKA SISTEM INFORMASI RUSAK KAMPUS

SiRUKA atau Sistem Informasi Rusak Kampus merupakan aplikasi berbasis web yang dikembangkan untuk mendukung proses pelaporan dan penanganan kerusakan fasilitas di lingkungan kampus. Sistem ini berfokus pada fasilitas ruang kelas dan laboratorium karena kedua area tersebut memiliki intensitas penggunaan yang tinggi serta berpengaruh langsung terhadap kelancaran kegiatan akademik.
Pengembangan SiRUKA bertujuan untuk menyediakan sistem pelaporan kerusakan yang lebih terstruktur, terdokumentasi, dan mudah dipantau. Melalui sistem ini, pengguna dapat menyampaikan laporan kerusakan fasilitas, sedangkan teknisi dan administrator dapat memproses laporan, memperbarui status penanganan, serta memantau perkembangan perbaikan hingga laporan dinyatakan selesai.

DESKRIPSI PROJECT
Pada proses manual, pelaporan kerusakan fasilitas kampus umumnya dilakukan melalui komunikasi langsung atau pesan singkat. Mekanisme tersebut memiliki beberapa keterbatasan, seperti laporan yang sulit dilacak, dokumentasi yang kurang rapi, serta minimnya transparansi dalam proses penanganan.
SiRUKA dirancang sebagai solusi untuk mengatasi permasalahan tersebut dengan menyediakan sistem pelaporan yang terpusat. Setiap laporan kerusakan dapat disimpan dalam sistem, dikategorikan berdasarkan jenis kerusakan, diproses sesuai status penanganan, dan dipantau oleh pihak yang bertanggung jawab.

CAKUPAN SISTEM
Cakupan sistem SiRUKA meliputi pelaporan dan penanganan kerusakan fasilitas pada ruang kelas dan laboratorium. Pada ruang kelas, fasilitas yang dapat dilaporkan mencakup AC, kursi, meja, papan tulis, proyektor, lampu, dan pintu. Pada laboratorium, fasilitas yang dapat dilaporkan mencakup komputer, peralatan praktikum, meja laboratorium, AC, dan lampu.

FITUR UTAMA
Fitur utama dalam aplikasi SiRUKA adalah pelaporan dan penanganan kerusakan fasilitas kampus. Pengguna dapat membuat laporan kerusakan dengan menyertakan informasi lokasi, jenis fasilitas, deskripsi kerusakan, serta bukti pendukung berupa gambar.
Sistem ini juga menyediakan fitur otorisasi pengguna berdasarkan peran. Setiap pengguna memiliki hak akses yang berbeda sesuai dengan perannya, yaitu pelapor, teknisi, dan administrator. Pelapor berperan dalam membuat laporan kerusakan, teknisi berperan dalam menangani laporan dan memperbarui status perbaikan, sedangkan administrator berperan dalam mengelola data pengguna, ruangan, fasilitas, prioritas, serta pemantauan laporan.
Selain itu, SiRUKA menyediakan fitur pengelolaan prioritas dan deadline laporan. Administrator dapat menentukan tingkat prioritas kerusakan berdasarkan kategori tertentu, sehingga laporan dapat diproses sesuai tingkat urgensinya. Sistem juga mendukung pelacakan status laporan secara real time agar pengguna dapat mengetahui perkembangan penanganan laporan secara lebih transparan.
SiRUKA dilengkapi dengan dashboard statistik yang menampilkan ringkasan data laporan, seperti jumlah laporan berdasarkan status, kategori kerusakan, serta informasi pendukung lainnya. Fitur ini membantu administrator dalam melakukan evaluasi terhadap proses penanganan kerusakan fasilitas kampus.

TEKNOLOGI YANG DIGUNAKAN
Project ini dikembangkan menggunakan framework Laravel dengan bahasa pemrograman PHP. Tampilan aplikasi dibangun menggunakan Blade Template, HTML, CSS, dan JavaScript. Untuk pengelolaan data, sistem dapat menggunakan SQLite atau MySQL sesuai dengan kebutuhan pengembangan.

SOFTWARE PROCESS

Metode pengembangan perangkat lunak yang digunakan dalam project SiRUKA adalah Agile Development dengan pendekatan Kanban. Pendekatan ini dipilih karena kebutuhan sistem masih dapat berkembang selama proses pengembangan, sehingga diperlukan metode yang fleksibel dan mudah disesuaikan.
Dengan menggunakan Kanban, proses pengembangan dilakukan secara bertahap melalui pembagian pekerjaan ke dalam beberapa status, seperti To Do, In Progress, dan Done. Setiap fitur atau perbaikan sistem direpresentasikan sebagai task yang dapat dipantau perkembangannya oleh anggota tim.
Pendekatan Kanban membantu tim dalam mengelola pekerjaan secara lebih transparan, terstruktur, dan adaptif terhadap perubahan kebutuhan. Dengan demikian, pengembangan SiRUKA dapat dilakukan secara berkelanjutan berdasarkan evaluasi dan masukan dari pengguna.

CARA MENJALANKAN PROJECT

Clone repository project ke perangkat lokal.

git clone link-repository

Masuk ke folder project.

cd Software-Engineering

Install dependency Laravel.

composer install

Salin file environment.

cp .env.example .env

Generate application key.

php artisan key:generate

Jalankan migration database.

php artisan migrate

Jalankan server Laravel.

php artisan serve

Setelah server berjalan, aplikasi dapat diakses melalui browser dengan alamat berikut.

http://127.0.0.1:8000

