import streamlit as st
import pickle
import pandas as pd
import os

# Menggunakan os.path.dirname(__file__) agar path selalu akurat berdasarkan lokasi file app.py
current_dir = os.path.dirname(__file__)
model_dir = os.path.abspath(os.path.join(current_dir, '..', 'models'))

# Load data dictionary film
movies_dict = pickle.load(open(os.path.join(model_dir, 'movie_dict.pkl'), 'rb'))
movies = pd.DataFrame(movies_dict)

# Load matriks similarity
similarity = pickle.load(open(os.path.join(model_dir, 'similarity.pkl'), 'rb'))

# Fungsi rekomendasi
def recommend(movie_title):
    movie_index = movies[movies['title'] == movie_title].index[0]
    distances = similarity[movie_index]
    movies_list = sorted(list(enumerate(distances)), reverse=True, key=lambda x: x[1])[1:6]
    
    recommended_movies = []
    for i in movies_list:
        recommended_movies.append(movies.iloc[i[0]].title)
    return recommended_movies

# --- TAMPILAN WEB STREAMLIT ---
st.title('Sistem Rekomendasi Film 🎬')

# Buat dropdown pilihan film
selected_movie_name = st.selectbox(
    'Pilih film yang kamu suka:',
    movies['title'].values
)

# Tombol untuk memicu rekomendasi
if st.button('Cari Rekomendasi'):
    recommendations = recommend(selected_movie_name)
    
    st.write("### Rekomendasi film untuk kamu:")
    for idx, movie in enumerate(recommendations, 1):
        st.write(f"**{idx}.** {movie}")