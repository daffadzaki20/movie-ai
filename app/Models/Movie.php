<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $movie_id
 * @property string $genres
 */
class Movie extends Model
{
    // Mengizinkan semua kolom diisi secara massal dari file CSV
    protected $guarded = []; 

    public function listedByUsers()
    {
        return $this->belongsToMany(User::class, 'my_lists', 'movie_id', 'user_id', 'movie_id', 'id')->withTimestamps();
    }
}