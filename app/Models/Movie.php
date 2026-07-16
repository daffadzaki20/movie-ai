<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // Mengizinkan semua kolom diisi secara massal dari file CSV
    protected $guarded = []; 
}