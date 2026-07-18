<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Note: movies table has 'movie_id' as its unique key (from CSV), but 'id' is its primary key.
            // Our previous exploration of movies table:
            // $table->id();
            // $table->integer('movie_id')->unique();
            // We should reference 'movie_id' in movies table or 'id'. Let's reference 'id'. Wait, the API returns movie title.
            // When we fetch from TMDB, we might only have TMDB movie ID or title.
            // Let's use 'movie_id' as the column referencing the 'movies' table's 'id' column for simplicity.
            // Let's look at MovieRecommendationController: it does $movie = Movie::where('movie_id', $movie_id)->firstOrFail();
            // So we should probably store 'movie_id' from the movies table (which is the TMDB ID).
            // Actually, let's just make it constrained('movies', 'movie_id') if movie_id is unique, or just integer.
            $table->integer('movie_id');
            $table->foreign('movie_id')->references('movie_id')->on('movies')->cascadeOnDelete();
            $table->timestamps();

            // Prevent duplicate entries for the same user and movie
            $table->unique(['user_id', 'movie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_lists');
    }
};
