<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArtikelDakwah extends Model
{
    use HasFactory;

    public $fillable = [
        'judul',
        'gambar',
        'deskripsi',
        'author',
    ];
}
