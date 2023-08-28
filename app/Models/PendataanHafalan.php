<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendataanHafalan extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'juz',
        'surah',
        'ayat_awal',
        'ayat_akhir',
        'tanggal',
    ];
}
