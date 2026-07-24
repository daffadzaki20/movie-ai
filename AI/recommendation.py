import pandas as pd
import os
import difflib
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.feature_extraction.text import TfidfVectorizer

# Variabel Global untuk menyimpan model di memori
cf_similarity_df = None
cbf_similarity_df = None
all_titles = []
title_to_id = {}

def load_and_train_model():
    """Fungsi ini HANYA dipanggil SATU KALI saat aplikasi/script pertama kali jalan"""
    global cf_similarity_df, cbf_similarity_df, all_titles, title_to_id
    
    try:
        print("Memuat dataset dan memproses matrix (Hybrid Filtering)...")
        movies_path = os.path.join('dataset', 'movies_metadata.csv')
        ratings_path = os.path.join('dataset', 'ratings_small.csv')
        links_path = os.path.join('dataset', 'links_small.csv')
        
        # 1. Load data
        df_movies = pd.read_csv(movies_path, low_memory=False)
        df_movies['id'] = pd.to_numeric(df_movies['id'], errors='coerce')
        df_movies = df_movies.dropna(subset=['id', 'title'])
        df_movies['id'] = df_movies['id'].astype(int)
        df_movies = df_movies.drop_duplicates(subset=['title'])
        
        df_links = pd.read_csv(links_path)
        df_links = df_links.dropna(subset=['tmdbId'])
        df_links['tmdbId'] = df_links['tmdbId'].astype(int)
        
        # Filter movies to only those in links_small
        df_movies = df_movies[df_movies['id'].isin(df_links['tmdbId'])]
        
        # Simpan mapping Judul ke ID untuk Laravel
        title_to_id = pd.Series(df_movies.id.values, index=df_movies.title).to_dict()
        
        # --- CBF PREP ---
        df_movies['overview'] = df_movies['overview'].fillna('')
        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(df_movies['overview'])
        cbf_sim_matrix = cosine_similarity(tfidf_matrix, tfidf_matrix)
        cbf_similarity_df = pd.DataFrame(cbf_sim_matrix, index=df_movies['title'], columns=df_movies['title'])
        
        # --- CF PREP ---
        df_ratings = pd.read_csv(ratings_path)
        df_ratings = df_ratings.merge(df_links[['movieId', 'tmdbId']], on='movieId', how='inner')
        df_ratings = df_ratings.rename(columns={'tmdbId': 'id'})
        df_ratings = df_ratings.merge(df_movies[['id', 'title']], on='id', how='inner')
        
        user_movie_matrix = df_ratings.pivot_table(index='title', columns='userId', values='rating').fillna(0)
        cf_sim_matrix = cosine_similarity(user_movie_matrix)
        cf_similarity_df = pd.DataFrame(cf_sim_matrix, index=user_movie_matrix.index, columns=user_movie_matrix.index)
        
        all_titles = cbf_similarity_df.columns.tolist()
        print("Model berhasil dimuat ke memori!\n")
        
    except Exception as e:
        print(f"Terjadi error saat memuat model: {e}")

def get_movie_recommendations(movie_title, top_n=5):
    """Fungsi ini akan merespons dengan instan karena model sudah ada di memori"""
    if cbf_similarity_df is None or cf_similarity_df is None:
        print("Model belum dimuat! Panggil load_and_train_model() terlebih dahulu.")
        return
        
    close_matches = difflib.get_close_matches(movie_title, all_titles, n=1, cutoff=0.3)
    if not close_matches:
        print(f"Film '{movie_title}' tidak ditemukan atau belum cukup dirating.")
        return
        
    matched_title = close_matches[0]
    
    # Ambil skor CBF
    cbf_scores = cbf_similarity_df[matched_title]
    
    # Ambil skor CF, tangani film yang tidak punya rating
    if matched_title in cf_similarity_df.columns:
        cf_scores = cf_similarity_df[matched_title]
    else:
        cf_scores = pd.Series(0, index=cbf_similarity_df.columns)
        
    # Sejajarkan index CF dengan CBF, isi missing dengan 0
    cf_scores = cf_scores.reindex(cbf_scores.index).fillna(0)
    
    # Hybrid Score (50% CF + 50% CBF)
    hybrid_scores = (0.5 * cf_scores) + (0.5 * cbf_scores)
    
    # Hapus judul film itu sendiri dari daftar rekomendasi
    if matched_title in hybrid_scores:
        hybrid_scores = hybrid_scores.drop(matched_title)
        
    similar_movies = hybrid_scores.sort_values(ascending=False).head(top_n)
    
    print(f"Karena kamu suka '{matched_title}', AI merekomendasikan:")
    for i, (title, score) in enumerate(similar_movies.items()):
        movie_id = title_to_id.get(title, "Unknown")
        print(f"{i+1}. {title} (TMDB ID: {movie_id}) - Score: {score:.3f}")

# --- UJI COBA LANGSUNG DI SINI ---
if __name__ == '__main__':
    # Jalankan training satu kali saja
    load_and_train_model()
    
    # Kamu bisa memanggil fungsi rekomendasi berkali-kali dengan sangat cepat!
    get_movie_recommendations("The Dark Knight Rises", top_n=5)
    print("-" * 50)
    get_movie_recommendations("Toy Story", top_n=3)