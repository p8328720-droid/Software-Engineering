Maintenance Measurement Initial Check

Repository: https://github.com/p8328720-droid/Software-Engineering
Framework: Laravel (PHP)
Measurement Date: 2026-05-21
Measured by: RaYanA202

Laporan ini berisi hasil maintenance measurement awal pada project Software-Engineering. Tujuan dari pengukuran ini adalah untuk melihat apakah project sudah cukup mudah disiapkan, dijalankan, dirawat, dan dikembangkan kembali.
Pengukuran dilakukan berdasarkan beberapa aspek, yaitu kondisi repository, struktur project, environment setup, dependency, audit keamanan, proses menjalankan aplikasi, migration database, dan dokumentasi project.
Laporan ini merupakan initial check, sehingga hanya berisi hasil pengecekan yang sudah dilakukan. Pengujian lanjutan seperti unit testing atau feature testing dapat dilakukan pada tahap berikutnya.


1.Repository Check

Perintah yang dijalankan:

git status

Hasil:

On branch main
Your branch is up to date with origin/main.
nothing to commit, working tree clean

Penjelasan:
Repository sudah berada pada branch main dan sudah terhubung dengan origin/main. Tidak ada perubahan file yang belum disimpan. Dari hasil tersebut, kondisi repository dalam keadaan bersih dan siap digunakan untuk proses maintenance measurement.


2.Project Structure Check

Perintah yang dijalankan:
ls

Hasil struktur project:

    README.md
    app
    artisan
    bootstrap
    composer.json
    composer.lock
    config
    database
    package-lock.json
    package.json
    phpunit.xml
    public
    resources
    routes
    storage
    tests
    vite.config.js

Penjelasan:
Project ini memiliki struktur folder Laravel yang cukup lengkap. Folder penting seperti app, config, database, resources, routes, storage, dan tests sudah tersedia. Struktur seperti ini membantu proses maintenance karena setiap bagian project sudah dipisahkan sesuai fungsinya.


3.Environment Setup

Perintah yang dijalankan:

cp .env.example .env
php artisan key:generate

Hasil:

INFO Application key set successfully.

Penjelasan:
File .env berhasil dibuat dari file .env.example. Application key Laravel juga berhasil digenerate. File .env digunakan untuk menyimpan konfigurasi environment project seperti pengaturan database. Application key dibutuhkan agar aplikasi Laravel dapat berjalan dengan benar. Konfigurasi dasar project berhasil disiapkan.


4.Composer Dependency Check

Pada awalnya perintah Composer belum bisa dijalankan karena Composer belum terinstall di perangkat.

Masalah awal:
zsh: command not found: composer

Setelah Composer diinstall, dilakukan pengecekan versi dengan perintah:

composer --version

Hasil:

Composer version 2.9.8
PHP version 8.5.6

Kemudian dijalankan perintah:

composer install

Hasil:
Dependency backend Laravel berhasil diinstall. Namun terdapat warning pada file tests/ModelTest.php karena class test belum sesuai dengan standar PSR-4 autoloading.

Penjelasan:
Composer install berhasil dijalankan sehingga package PHP yang dibutuhkan oleh Laravel sudah tersedia di project. Warning pada tests/ModelTest.php tidak menghentikan proses instalasi, tetapi perlu diperhatikan karena berhubungan dengan kerapian struktur test. File testing sebaiknya diperiksa kembali agar sesuai dengan standar Laravel.


5. Composer Audit Check

Perintah yang dijalankan:

composer audit

Hasil:
Ditemukan 9 security vulnerability advisories yang memengaruhi 6 packages, yaitu league/commonmark, symfony/http-kernel, symfony/mailer, symfony/mime, symfony/routing, dan symfony/yaml.

Penjelasan:
Beberapa dependency backend Laravel memiliki masalah keamanan. Package yang terdampak sebagian besar berasal dari library Symfony dan league/commonmark. Meskipun instalasi berhasil, hasil audit ini menunjukkan bahwa masih ada package yang perlu diperiksa dan diperbarui agar keamanan project lebih terjaga.


6. NPM Dependency Check

Pada awalnya perintah npm belum bisa dijalankan karena npm belum terinstall di perangkat.

Masalah awal:

zsh: command not found: npm

Setelah Node.js diinstall, npm tersedia dan perintah berikut dijalankan:

npm install

Hasil:

added 89 packages, and audited 90 packages in 4s
5 vulnerabilities (2 moderate, 3 high)

Penjelasan:
Dependency frontend berhasil diinstall. Namun ditemukan 5 vulnerabilities yaitu 2 moderate dan 3 high. Hal ini menunjukkan bahwa beberapa package frontend masih perlu diperiksa dan diperbarui.


7. NPM Audit Check

Perintah yang dijalankan:

npm audit

Hasil:
Ditemukan 5 vulnerabilities yaitu 2 moderate dan 3 high. Package yang memiliki vulnerability adalah axios, follow-redirects, picomatch, postcss, dan vite.

Penjelasan:
NPM memberikan rekomendasi untuk menjalankan npm audit fix. Namun perintah tersebut sebaiknya dijalankan dengan hati-hati karena update dependency bisa memengaruhi bagian lain dari project. Dependency frontend masih perlu diperbaiki agar project lebih aman dan lebih mudah dimaintenance.


8. Running Application and Migration Check

Perintah yang dijalankan:

php artisan serve

Hasil:
Server berhasil berjalan di http://127.0.0.1:8000. Namun saat aplikasi dibuka di browser muncul Internal Server Error.

Error awal:

Database file at path database/database.sqlite does not exist.

Tindakan yang dilakukan:

touch database/database.sqlite
php artisan migrate

Hasil:
Beberapa migration berhasil dijalankan seperti create_users_table, create_facilities_table, create_reports_table, create_comments_table, dan beberapa migration lainnya. Namun migration gagal pada file 2026_05_07_133852_fix_status_column_in_reports_table.php dengan error berikut:

SQLSTATE HY000 General error near SHOW: syntax error.

Penjelasan:
Aplikasi sudah berhasil dijalankan melalui local server. Error pertama muncul karena file database SQLite belum tersedia. Setelah file database dibuat dan migration dijalankan, sebagian besar migration berhasil. Namun proses migration berhenti karena terdapat query SHOW COLUMNS yang biasa digunakan pada MySQL, sedangkan project ini menggunakan SQLite yang tidak mendukung query tersebut. Bagian ini menjadi temuan penting karena migration perlu diperbaiki agar kompatibel dengan database yang digunakan.


9. Documentation Check

Hasil:
File README.md masih menggunakan dokumentasi bawaan Laravel dan belum menjelaskan project kelompok secara khusus.

Penjelasan:
README sebaiknya berisi informasi penting tentang project seperti deskripsi, tujuan, teknologi yang digunakan, cara instalasi, cara menjalankan project, dan daftar anggota kelompok. Dokumentasi yang lengkap sangat membantu proses maintenance karena developer lain akan lebih mudah memahami isi project dan cara menjalankannya.


10. Conclusion
Berdasarkan maintenance measurement yang telah dilakukan, project Software-Engineering sudah dapat disiapkan secara lokal. Repository berada dalam kondisi bersih, struktur folder sudah mengikuti standar Laravel, file environment berhasil dibuat, application key berhasil digenerate, serta dependency backend dan frontend berhasil diinstall.
Namun masih terdapat beberapa hal yang perlu diperbaiki. Pada bagian Composer terdapat warning pada file tests/ModelTest.php. Pada bagian npm ditemukan 5 vulnerabilities yaitu 2 moderate dan 3 high. Pada bagian composer audit ditemukan 9 security vulnerability advisories yang memengaruhi 6 packages. Selain itu aplikasi belum dapat berjalan sempurna karena migration database gagal akibat penggunaan query SHOW COLUMNS yang tidak didukung oleh SQLite.
Dari hasil tersebut dapat disimpulkan bahwa project sudah memiliki struktur dasar yang cukup baik, tetapi masih perlu peningkatan pada bagian testing, dependency frontend, dependency backend, dokumentasi, dan migration database. Dengan memperbaiki bagian-bagian tersebut, project akan menjadi lebih mudah dimaintenance, lebih aman, dan lebih siap untuk dikembangkan kembali.



