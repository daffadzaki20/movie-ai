from flask import Flask, request, jsonify
from flask_cors import CORS
import pickle
import pandas as pd
import os

app = Flask(__name__)
CORS(app) # Mengizinkan Laravel mengakses API ini nantinya

# 1. Tentukan lokasi folder model pkl kamu
current_dir = os.path.dirname(__file__)
model_dir = os.path.abspath(os.path.join(current_dir, '..', 'models'))

# 2. Load model AI yang sudah kita buat kemarin
movies_dict = pickle.load(open(os.path.join(model_dir, 'movie_dict.pkl'), 'rb'))
movies = pd.DataFrame(movies_dict)
similarity = pickle.load(open(os.path.join(model_dir, 'similarity.pkl'), 'rb'))

# 3. MEMBUAT JENDELA API (ENDPOINT)
# API ini bisa diakses lewat alamat: http://127.0.0.1:5000/api/recommend?movie=JudulFilm
@app.route('/api/recommend', methods=['GET'])
def get_recommendation():
    # Ambil parameter 'movie' yang dikirim oleh Laravel/User lewat URL
    movie_title = request.args.get('movie')
    
    if not movie_title:
        return jsonify({'error': 'Parameter ?movie=judul_film wajib diisi!'}), 400
        
    try:
        # Cari indeks film
        movie_index = movies[movies['title'].str.lower() == movie_title.lower()].index[0]
        distances = similarity[movie_index]
        
        # Ambil 5 film paling mirip
        movies_list = sorted(list(enumerate(distances)), reverse=True, key=lambda x: x[1])[1:6]
        
        recommended_movies = []
        for i in movies_list:
            recommended_movies.append(movies.iloc[i[0]].title)
            
        # Balas pesan dalam format JSON (format standar data API)
        return jsonify({
            'status': 'success',
            'searched_movie': movies.iloc[movie_index].title,
            'recommendations': recommended_movies
        })
        
    except IndexError:
        return jsonify({
            'status': 'error',
            'message': f"Film '{movie_title}' tidak ditemukan di database AI."
        }), 404

# 4. Jalankan server API Python di port 5000
if __name__ == '__main__':
    app.run(debug=True, port=5000)