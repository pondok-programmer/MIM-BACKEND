<?php

namespace App\Models;

use Hashids\Hashids;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'tgl_lahir',
        'tempat_lahir',
        'jenkel',
        'alamat',
        'no_telp',
        'email',
        'pendidikan',
        'pekerjaan',
        'range_gaji',
        'status',
        'jumlah_anak',
        'img',
        'role',
        'otp_code',
        'otp_expired',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getHashIdAttribute() {
        $hashids = new Hashids('your-secret-salt', 10); // panjang minimum 10
        return $hashids->encode($this->attributes['id']);
    }

    public function amalyaumi(){
        return $this->hasMany(AmalYaumi::class, 'user_id', 'id');
    }
}
