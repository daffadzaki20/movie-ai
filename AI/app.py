from flask import Flask, request, jsonify
import pandas as pd
import os
import difflib
from sklearn.metrics.pairwise import cosine_similarity
from sklearn.feature_extraction.text import TfidfVectorizer

app = Flask(__name__)

# Global variables for Hybrid Filtering
cf_similarity_df = None
cbf_similarity_df = None
all_titles = []
title_to_id = {}

def init_data():
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
        
        # Membuat mapping dictionary: {'Toy Story': 862, ...}
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
        
        print("Sistem AI (Hybrid Filtering) Siap!")
        return True
    except Exception as e:
        print(f"Gagal inisialisasi: {e}")
        return False

init_data()

@app.route('/api/recommend', methods=['GET'])
def recommend():
    movie_title = request.args.get('movie', '') 
    if cbf_similarity_df is None or cf_similarity_df is None:
        return jsonify({'success': False, 'message': 'Data belum siap'}), 500
        
    if not movie_title:
        return jsonify({'success': False, 'message': 'Judul film kosong'}), 400
        
    close_matches = difflib.get_close_matches(movie_title, all_titles, n=1, cutoff=0.3)
    
    if not close_matches:
        return jsonify({'success': False, 'message': 'Film tidak ditemukan atau belum cukup dirating'}), 404
        
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
    
    # Ambil Top 5
    similar_movies = hybrid_scores.sort_values(ascending=False).head(5)
    
    recs = []
    for title in similar_movies.index:
        recs.append({
            'title': title,
            'tmdbId': int(title_to_id.get(title, 0)),
            'score': float(similar_movies[title])
        })
    
    return jsonify({
        'success': True,
        'searched_movie': matched_title,
        'recommendations': recs
    })

if __name__ == '__main__':
    app.run(port=5000, debug=True)