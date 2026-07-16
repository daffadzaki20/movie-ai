# Movie Recommendation System

Sistem rekomendasi film berbasis AI yang menggabungkan web aplikasi (Laravel) dan mesin prediksi (Python).

## 🚀 Cara Menjalankan

### 1. Menyiapkan AI Backend (Python)
- Masuk ke folder AI: `cd AI`
- Install library yang dibutuhkan: `pip install -r requirements.txt`
- Jalankan server AI: `python src/app.py`

### 2. Menjalankan Website (Laravel)
- Buka terminal baru di folder utama.
- Jalankan aplikasi: `php artisan serve`
- Buka browser di: `http://127.0.0.1:8000`

## 📋 Persyaratan
- **PHP** (minimal 8.x) & **Composer**
- **Python** (minimal 3.x)
- **MySQL** (untuk database web)

*Catatan: Pastikan file .env sudah di-copy dari .env.example dan database sudah dimigrasi.*