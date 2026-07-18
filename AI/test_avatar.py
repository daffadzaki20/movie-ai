import pickle
import pandas as pd
import numpy as np

# Load model
movie_dict = pickle.load(open('AI/src/movie_dict.pkl', 'rb'))
movies = pd.DataFrame(movie_dict)
similarity = pickle.load(open('AI/src/similarity.pkl', 'rb'))

def test_recommend(movie_title):
    movie_index = movies[movies['title'].str.lower() == movie_title.lower()].index[0]
    distances = similarity[movie_index]
    movies_list = sorted(list(enumerate(distances)), reverse=True, key=lambda x: x[1])[0:6]
    
    print(f"Top 6 matches for '{movie_title}':")
    for i in movies_list:
        idx = i[0]
        score = i[1]
        print(f" - {movies.iloc[idx].title} (Score: {score})")

test_recommend('Avatar')
test_recommend('Batman')
