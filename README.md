# Movie Recommendation System (MovieAI)

Sistem rekomendasi film berbasis AI yang menggabungkan web aplikasi (Laravel) dan mesin prediksi (Python). Sistem ini menggunakan metode *Content-Based Filtering* (TF-IDF & Cosine Similarity) untuk mencocokkan kemiripan sinopsis film dan memberikan rekomendasi cerdas secara interaktif.

---

## 🚀 Cara Menjalankan (Panduan Lengkap)

Proyek ini terdiri dari dua bagian utama yang harus berjalan bersamaan: **Aplikasi Web (Laravel)** dan **Mesin AI (Python)**. Ikuti langkah-langkah berikut secara berurutan.

### Bagian 1: Persiapan Aplikasi Web (Laravel)
Pastikan Anda membuka terminal di root direktori proyek (`movie-ai`).

1. **Persiapkan Environment Variable (`.env`)**
   Jika Anda baru saja melakukan *clone*, file `.env` mungkin belum ada.
   - Gandakan (copy) file `.env.example` menjadi `.env`.
   - Buka terminal dan jalankan perintah:
     ```bash
     php artisan key:generate
     ```
   - *(Opsional tapi Penting)*: Tambahkan kunci API TMDb agar gambar poster film muncul. Buka file `.env`, lalu tambahkan baris berikut di paling bawah:
     ```env
     TMDB_API_KEY=kunci_api_tmdb_anda_disini
     ```

2. **Install Dependensi PHP & Frontend**
   Jalankan perintah ini di terminal untuk mengunduh semua pustaka yang dibutuhkan:
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Setup Database (SQLite) & Seeding Data**
   Proyek ini sudah dikonfigurasi untuk menggunakan SQLite (`DB_CONNECTION=sqlite`). Lakukan migrasi database dan masukkan dataset film dengan perintah berikut:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *(Proses seeding mungkin membutuhkan waktu beberapa detik karena sistem harus membaca dataset CSV)*.

4. **Jalankan Server Laravel**
   Walaupun Anda menggunakan aplikasi seperti Laragon, **sangat disarankan** menjalankan server bawaan Laravel agar port berjalan secara spesifik di `8000`, karena server Python dikonfigurasi untuk memanggil port tersebut.
   ```bash
   php artisan serve
   ```
   *(Biarkan terminal ini tetap menyala).*

---

### Bagian 2: Persiapan Mesin AI (Python)
Buka **terminal baru** (jangan tutup terminal Laravel yang sedang menjalankan perintah `php artisan serve`).

1. **Masuk ke folder AI**
   ```bash
   cd AI
   ```

2. **Buat & Aktifkan Virtual Environment (Disarankan)**
   Langkah ini bertujuan agar library Python proyek ini tidak bentrok dengan library di komputer Anda.
   ```bash
   python -m venv venv
   ```
   Lalu aktifkan (khusus Windows / terminal Laragon):
   ```bash
   .\venv\Scripts\activate
   ```
   *(Untuk Mac/Linux: `source venv/bin/activate`)*

3. **Install Dependensi Python**
   ```bash
   pip install -r requirements.txt
   ```

4. **Jalankan Server AI (Flask)**
   ```bash
   python app.py
   ```
   *(Peringatan: Pastikan server Laravel di port 8000 sudah berjalan sebelum Anda menjalankan langkah ini, karena skrip Python akan mencoba mengambil data film dari Laravel saat pertama kali dinyalakan).*

---

## 📋 Persyaratan Sistem
- **PHP** (minimal 8.x) & **Composer**
- **Node.js** & **NPM** (untuk TailwindCSS v4)
- **Python** (minimal 3.x)
- **Koneksi Internet** (untuk mengunduh dependensi dan mengambil poster film dari TMDb API)