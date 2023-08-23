<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmalYaumi extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'hari',
        'subuh',
        'zuhur',
        'ashar',
        'maghrib',
        'isya',
        'tahajud',
        'dhuha',
        'witir',
        'puasa',
        'dzikir',
        'kajian_subuh',
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
