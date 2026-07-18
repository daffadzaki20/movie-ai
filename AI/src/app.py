from flask import Flask, request, jsonify
import pickle
import pandas as pd
import os
from flask_cors import CORS # Tambahkan ini jika perlu

app = Flask(__name__)
CORS(app) # Agar Laravel aman saat memanggil API ini

# --- Setup Path & Load Model (sama seperti punya Anda) ---
current_dir = os.path.dirname(__file__)
model_dir = os.path.abspath(os.path.join(current_dir, '..', 'models'))

movies_dict = pickle.load(open(os.path.join(model_dir, 'movie_dict.pkl'), 'rb'))
movies = pd.DataFrame(movies_dict)
similarity = pickle.load(open(os.path.join(model_dir, 'similarity.pkl'), 'rb'))

def recommend(movie_title):
    movie_index = movies[movies['title'] == movie_title].index[0]
    distances = similarity[movie_index]
    movies_list = sorted(list(enumerate(distances)), reverse=True, key=lambda x: x[1])[1:6]
    
    recommended_movies = []
    for i in movies_list:
        recommended_movies.append(movies.iloc[i[0]].title)
    return recommended_movies

# --- INI BAGIAN PENTING: API ENDPOINT ---
@app.route('/api/recommend', methods=['GET'])
def get_recommendation():
    movie_title = request.args.get('movie')
    
    if not movie_title:
        return jsonify({'error': 'Judul film tidak ada'}), 400
    
    try:
        recommendations = recommend(movie_title)
        return jsonify({
            'searched_movie': movie_title,
            'recommendations': recommendations
        })
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(port=5000)