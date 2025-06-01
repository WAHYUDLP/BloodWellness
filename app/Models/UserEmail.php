<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $table = 'users_email';  // sesuaikan nama tabel

    protected $fillable = ['id_user', 'email'];
    public $timestamps = false;  // Nonaktifkan timestamp otomatis

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
