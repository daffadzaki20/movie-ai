from flask import Flask, request, jsonify
import requests
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

app = Flask(__name__)

URL = "http://127.0.0.1:8000/api/movies"
df = None
cosine_sim = None

def init_data():
    global df, cosine_sim
    try:
        response = requests.get(URL)
        data = response.json()
        
      
        df = pd.DataFrame(data['data'])
        
        
        if 'overview' not in df.columns or 'title' not in df.columns:
            print(f"⚠️ Error: Kolom yang diterima: {df.columns.tolist()}")
            return False

        df['overview'] = df['overview'].fillna('')
        df['title_lower'] = df['title'].str.lower()
        
        
        tfidf = TfidfVectorizer(stop_words='english')
        tfidf_matrix = tfidf.fit_transform(df['overview'])
        cosine_sim = cosine_similarity(tfidf_matrix, tfidf_matrix)
        
        print("Sistem AI Siap!")
        return True
    except Exception as e:
        print(f"Gagal inisialisasi: {e}")
        return False


init_data()

@app.route('/api/recommend', methods=['GET'])
def recommend():
    movie_title = request.args.get('movie', '') 
    if df is None:
        return jsonify({'success': False, 'message': 'Data belum siap'}), 500
        
    if not movie_title:
        return jsonify({'success': False, 'message': 'Judul film kosong'}), 400
        
  
    filtered = df[df['title_lower'] == movie_title.lower()]
    if filtered.empty:
        return jsonify({'success': False, 'message': 'Film tidak ditemukan'}), 404
        
    idx = filtered.index[0]
    sim_scores = list(enumerate(cosine_sim[idx]))
    sim_scores = sorted(sim_scores, key=lambda x: x[1], reverse=True)[1:6]
    
    recs = [df.iloc[i[0]]['title'] for i in sim_scores]
    
    return jsonify({
        'success': True,
        'searched_movie': df.iloc[idx]['title'],
        'recommendations': recs
    })

if __name__ == '__main__':
    app.run(port=5000, debug=True)