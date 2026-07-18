from flask import Flask, request, jsonify
from flask_cors import CORS
import pickle
import pandas as pd
import os
# Trigger reload

app = Flask(__name__)
CORS(app)

# --- BAGIAN YANG DIUBAH ---
# Kita gunakan lokasi folder tempat api.py berada agar lebih stabil
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
# Pastikan file pkl ada di folder yang sama dengan api.py
model_path = os.path.join(BASE_DIR, 'movie_dict.pkl')
sim_path = os.path.join(BASE_DIR, 'similarity.pkl')

print(f"Mencoba memuat model dari: {BASE_DIR}")

# Load model
try:
    movies_dict = pickle.load(open(model_path, 'rb'))
    movies = pd.DataFrame(movies_dict)
    similarity = pickle.load(open(sim_path, 'rb'))
    print("Model berhasil dimuat!")
except Exception as e:
    print(f"Error memuat model: {e}")
# ---------------------------

@app.route('/api/recommend', methods=['GET'])
def get_recommendation():
    movie_title = request.args.get('movie')
    
    if not movie_title:
        return jsonify({'error': 'Parameter ?movie=judul_film wajib diisi!'}), 400
        
    try:
        movie_index = movies[movies['title'].str.lower() == movie_title.lower()].index[0]
        distances = similarity[movie_index]
        movies_list = sorted(list(enumerate(distances)), reverse=True, key=lambda x: x[1])[1:6]
        
        recommended_movies = []
        for i in movies_list:
            recommended_movies.append(movies.iloc[i[0]].title)
            
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

if __name__ == '__main__':
    app.run(debug=True, port=5000)