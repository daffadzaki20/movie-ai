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
    Schema::create('movies', function (Blueprint $table) {
        $table->id();
        $table->integer('movie_id')->unique(); // ID unik dari dataset/CSV film
        $table->text('title');               // Judul film
        $table->text('overview')->nullable();  // Sinopsis film
        $table->string('genres')->nullable();  // Genre film (e.g., "Action, Comedy")
        $table->float('popularity')->default(0); // Tingkat popularitas
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
