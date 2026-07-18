import pickle
import pandas as pd
import numpy as np
import time

print("Memuat model...")
movie_dict = pickle.load(open('AI/src/movie_dict.pkl', 'rb'))
movies = pd.DataFrame(movie_dict)
similarity = pickle.load(open('AI/src/similarity.pkl', 'rb'))
print("Selesai memuat model!\n")

def test_recommend(movie_title):
    try:
        movie_index = movies[movies['title'].str.lower() == movie_title.lower()].index[0]
        distances = similarity[movie_index]
        movies_list = sorted(list(enumerate(distances)), reverse=True, key=lambda x: x[1])[1:6]
        
        print(f"Top 6 pencarian untuk '{movie_title}':")
        for i, item in enumerate(movies_list):
            idx = item[0]
            score = item[1]
            title = movies.iloc[idx].title
            if i == 0:
                print(f" [No.1] {title} (Score: {score:.2f}) <- Ini harusnya film asli")
            else:
                print(f"  - {title} (Score: {score:.2f})")
        print("-" * 40)
    except Exception as e:
        print(f"Error pada film {movie_title}: {e}")

# Kita coba dengan berbagai genre!
test_recommend('Avatar') # Sci-Fi / Fantasy
test_recommend('Spectre') # Action / Spy
test_recommend('Titanic') # Romance / Drama
test_recommend('Spider-Man') # Superhero
test_recommend('The Matrix') # Sci-Fi / Action
