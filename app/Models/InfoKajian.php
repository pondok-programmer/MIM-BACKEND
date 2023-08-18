<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoKajian extends Model
{
    use HasFactory;
    
    public $fillable =[
        'judul',
        'gambar',
        'waktu',
        'tanggal',
        'link'
    ];
}
