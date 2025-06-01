<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usersaccount'; // <-- ini yang penting

    protected $fillable = [
        'name',
        'email',
        'password',
        'jenis_kelamin',
        'negara',
        'bahasa',
        'photo_url'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // kalau pakai Laravel 8 ke atas, bisa tambahkan casting tanggal:
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Nonaktifkan timestamps (created_at & updated_at)
    public $timestamps = false;

    public function emails()
    {
        return $this->hasMany(UserEmail::class);
    }
}
