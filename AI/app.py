from flask import Flask, request, jsonify
import requests
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

app = Flask(__name__)

# Kita load data dan siapkan matriks TF-IDF saat aplikasi Python pertama kali dinyalakan
URL = "http://localhost:8000/api/movies"
try:
    response = requests.get(URL)
    movies_list = response.json()['data']
    df = pd.DataFrame(movies_list)
    df['overview'] = df['overview'].fillna('')
    df['title_lower'] = df['title'].str.lower()
    
    # Hitung TF-IDF & Cosine Similarity sekali saja di awal biar cepat
    tfidf = TfidfVectorizer(stop_words='english')
    tfidf_matrix = tfidf.fit_transform(df['overview'])
    cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)
    print("✅ Sistem AI Siap & Data Film Berhasil Dimuat!")
except Exception as e:
    print(f"⚠️ Gagal inisialisasi data: {e}")

@app.route('/api/recommend', methods=['GET'])
def recommend():
    # Mengambil parameter ?title=Judul Film dari URL
    movie_title = request.args.get('title', '')
    
    if not movie_title:
        return jsonify({'success': False, 'message': 'Parameter title tidak boleh kosong'}), 400
        
    if movie_title.lower() not in df['title_lower'].values:
        return jsonify({'success': False, 'message': f"Film '{movie_title}' tidak ditemukan"}), 404
        
    # Cari rekomendasi
    idx = df[df['title_lower'] == movie_title.lower()].index[0]
    sim_scores = list(enumerate(cosine_sim[idx]))
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)
    sim_scores = sim_scores[1:6] # Ambil 5 film teratas
    
    recommendations = []
    for i in sim_scores:
        item = df.iloc[i[0]]
        recommendations.append({
            'movie_id': int(item['movie_id']),
            'title': item['title'],
            'overview': item['overview'],
            'similarity_score': float(i[1])
        })
        
    return jsonify({
        'success': True,
        'searched_movie': df['title'].iloc[idx],
        'recommendations': recommendations
    })

if __name__ == '__main__':
    # Jalankan server Python di port 5000
    app.run(host='127.0.0.1', port=5000, debug=True)