Cara menjalankan aplikasi:

Clone repositori ini ke komputer kalian.

Buka terminal di folder proyek, lalu jalankan: composer install

Buat file .env dengan menyalin dari .env.example (cp .env.example .env).

Pastikan database 'movie_recommendation' sudah dibuat di Laragon/MySQL kalian.

Jalankan migrasi database: php artisan migrate

Generate app key: php artisan key:generate

Install library frontend: npm install && npm run build

Jalankan aplikasi: php artisan serve

Akses di browser melalui alamat: http://127.0.0.1:8000