<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BacaanSholat extends Model
{
    use HasFactory;

    public $fillable = [
        'arab',
        'latin',
        'terjemahan',
        'voice_arab',
        'voice_terjemahan',
    ];
}
