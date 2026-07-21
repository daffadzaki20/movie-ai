from flask import Flask, request, jsonify
import pandas as pd
import os
import difflib
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

app = Flask(__name__)

df = None
cosine_sim = None

def init_data():
    global df, cosine_sim
    try:
        csv_path = os.path.join('dataset', 'tmdb_5000_movies.csv')
        df = pd.read_csv(csv_path)
        
        if 'overview' not in df.columns or 'title' not in df.columns:
            print(f"⚠️ Error: Kolom yang diterima: {df.columns.tolist()}")
            return False

        df['overview'] = df['overview'].fillna('')
        df['title_lower'] = df['title'].str.lower()
        
        # PENTING: Reset index agar posisi baris sinkron dengan matrix cosine similarity
        df = df.reset_index(drop=True)
        
        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(df['overview'])
        cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)
        
        print("✅ Sistem AI Siap!")
        return True
    except Exception as e:
        print(f"⚠️ Gagal inisialisasi: {e}")
        return False

init_data()

@app.route('/api/recommend', methods=['GET'])
def recommend():
    movie_title = request.args.get('movie', '') 
    if df is None or cosine_sim is None:
        return jsonify({'success': False, 'message': 'Data belum siap'}), 500
        
    if not movie_title:
        return jsonify({'success': False, 'message': 'Judul film kosong'}), 400
        
    all_titles = df['title'].tolist()
    close_matches = difflib.get_close_matches(movie_title, all_titles, n=1, cutoff=0.3)
    
    if not close_matches:
        return jsonify({'success': False, 'message': 'Film tidak ditemukan'}), 404
        
    matched_title = close_matches[0]
    
    # Mengambil indeks baris yang valid setelah di-reset
    matched_rows = df[df['title'] == matched_title]
    if matched_rows.empty:
        return jsonify({'success': False, 'message': 'Film tidak ditemukan'}), 404
        
    idx = matched_rows.index[0]
    
    sim_scores = list(enumerate(cosine_sim[idx]))
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)[1:6]
    
    recs = [df.iloc[i[0]]['title'] for i in sim_scores]
    
    return jsonify({
        'success': True,
        'searched_movie': matched_title,
        'recommendations': recs
    })

if __name__ == '__main__':
    app.run(port=5000, debug=True)