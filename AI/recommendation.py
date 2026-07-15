import requests
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

def get_movie_recommendations(movie_title, top_n=5):
    # 1. Ambil data dari API Laravel
    URL = "http://localhost:8000/api/movies"
    try:
        response = requests.get(URL)
        if response.status_code != 200:
            print("Gagal mengambil data dari API.")
            return
        
        movies_list = response.json()['data']
        df = pd.DataFrame(movies_list)
        
        # Jaga-jaga jika ada overview yang kosong
        df['overview'] = df['overview'].fillna('')
        
        # 2. Proses TF-IDF (Mengubah teks sinopsis menjadi angka/vektor)
        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(df['overview'])
        
        # 3. Hitung Cosine Similarity (Kedekatan/kemiripan antar film)
        cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)
        
        # 4. Cari indeks film berdasarkan judul yang diinput
        # Menggunakan str.lower() agar pencarian tidak sensitif huruf besar/kecil
        df['title_lower'] = df['title'].str.lower()
        
        if movie_title.lower() not in df['title_lower'].values:
            print(f"\n❌ Film '{movie_title}' tidak ditemukan di database.")
            return

        idx = df[df['title_lower'] == movie_title.lower()].index[0]
        
        # 5. Ambil skor kemiripan untuk semua film dengan film ini
        sim_scores = list(enumerate(cosine_sim[idx]))
        
        # Urutkan film berdasarkan skor kemiripan tertinggi (terkecuali film itu sendiri)
        sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
        sim_scores = sim_scores[1:top_n+1]
        
        # 6. Ambil indeks film hasil rekomendasi
        movie_indices = [i[0] for i in sim_scores]
        
        # Tampilkan hasil
        print(f"\n🎬 Karena kamu suka '{df['title'].iloc[idx]}', AI merekomendasikan film ini:")
        for i, index in enumerate(movie_indices):
            print(f"{i+1}. {df['title'].iloc[index]} (Score Kemiripan: {sim_scores[i][1]:.3f})")
            
    except Exception as e:
        print(f"Terjadi error: {e}")

# --- UJI COBA LANGSUNG DI SINI ---
# Silakan ganti judul film ini dengan judul lain seperti 'Spectre' atau 'The Dark Knight Rises'
get_movie_recommendations("The Dark Knight Rises", top_n=5)