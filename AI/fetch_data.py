import requests
import pandas as pd

# 1. Tentukan URL API Laravel kamu
URL = "http://localhost:8000/api/movies"

print("Sedang mengambil data dari API Laravel...")

try:
    # 2. Lakukan request ke API Laravel
    response = requests.get(URL)
    
    # Pastikan request sukses (status code 200)
    if response.status_code == 200:
        json_data = response.json()
        
        # 3. Ambil array data film yang ada di dalam key 'data'
        movies_list = json_data['data']
        
        # 4. Ubah menjadi DataFrame Pandas agar siap diproses AI
        df = pd.DataFrame(movies_list)
        
        print("\n✅ Sukses mengambil data!")
        print(f"Total film yang ditarik: {len(df)} data.")
        
        # Tampilkan 5 data teratas untuk memastikan kolomnya pas
        print("\nBerikut 5 data film teratas:")
        print(df[['movie_id', 'title', 'overview']].head())
        
        # DataFrame 'df' ini sekarang siap kamu masukkan ke fungsi TF-IDF / Cosine Similarity kamu!
        
    else:
        print(f"❌ Gagal mengambil data. Status Code: {response.status_code}")

except requests.exceptions.ConnectionError:
    print("❌ Koneksi gagal! Pastikan server Laravel kamu sudah berjalan (php artisan serve).")