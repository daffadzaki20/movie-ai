import pandas as pd
import numpy as np
import ast
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import pickle
import os

print("Membaca dataset...")
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
dataset_dir = os.path.abspath(os.path.join(BASE_DIR, '..', 'dataset'))

movies = pd.read_csv(os.path.join(dataset_dir, 'tmdb_5000_movies.csv'))
credits = pd.read_csv(os.path.join(dataset_dir, 'tmdb_5000_credits.csv'))

movies = movies.merge(credits, on='title')
movies = movies[['movie_id', 'title', 'overview', 'genres', 'keywords']]

def convert(text):
    L = []
    for i in ast.literal_eval(text):
        L.append(i['name']) 
    return L

movies.dropna(inplace=True)
movies['genres'] = movies['genres'].apply(convert)
movies['keywords'] = movies['keywords'].apply(convert)

movies['genres'] = movies['genres'].apply(lambda x: [i.replace(" ","") for i in x])
movies['keywords'] = movies['keywords'].apply(lambda x: [i.replace(" ","") for i in x])
movies['overview'] = movies['overview'].apply(lambda x: x.split())

movies['tags'] = movies['overview'] + movies['genres'] + movies['keywords']

new_df = movies[['movie_id', 'title', 'tags']].reset_index(drop=True)
new_df['tags'] = new_df['tags'].apply(lambda x: " ".join(x))
new_df['tags'] = new_df['tags'].apply(lambda x: x.lower())

print("Membuat matriks TF-IDF...")
cv = CountVectorizer(max_features=5000, stop_words='english')
vectors = cv.fit_transform(new_df['tags']).toarray()

print("Menghitung cosine similarity...")
similarity = cosine_similarity(vectors)

pickle.dump(new_df.to_dict(), open(os.path.join(BASE_DIR, 'movie_dict.pkl'), 'wb'))
pickle.dump(similarity, open(os.path.join(BASE_DIR, 'similarity.pkl'), 'wb'))
print("Mantap! movie_dict.pkl dan similarity.pkl berhasil dibuat di AI/src")
