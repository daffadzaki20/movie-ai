# MovieAI - AI Movie Recommender

Anggota Kelompok:
1. Muhammad Daffa Dzaki Pratama 
2. Bagas Putra baharuddin
3. Andreas Stephen
MovieAI adalah platform rekomendasi film cerdas yang membantu pengguna menemukan film favorit berdasarkan preferensi personal mereka. Sistem ini memanfaatkan kecerdasan buatan untuk memberikan hasil pencarian film yang akurat dan relevan.

## 🚀 Fitur Utama
- **Sistem Rekomendasi AI**: Temukan film berdasarkan selera unik pengguna.
- **Modern UI/UX**: Tampilan elegan dengan desain Glassmorphism dan Dark Mode.
- **User Authentication**: Sistem login dan register yang aman untuk mempersonalisasi pengalaman.
- **Responsive Design**: Tampilan yang menyesuaikan dengan perangkat mobile maupun desktop.

## 🛠 Tech Stack
- **Framework**: Laravel
- **Styling**: Tailwind CSS
- **Database**: MySQL

## ⚙️ Cara Menjalankan Sistem
Pastikan kamu sudah menginstall PHP, Composer, dan MySQL di komputer kamu.

1. Clone repository ini ke folder lokal kamu.
2. Install dependensi: `composer install`
3. Konfigurasi Environment: 
   - Salin file contoh: `cp .env.example .env`
   - Generate key: `php artisan key:generate`
   - Atur database kamu di file `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD).
4. Migrasi Database: `php artisan migrate`
5. Jalankan Server: `php artisan serve`
6. Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

---
*Dibuat dengan semangat untuk mempermudah cara kita memilih film!*