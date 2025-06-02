<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // import model User supaya relasi jalan

class UserEmail extends Model
{
    use HasFactory;  // supaya bisa pakai factory

    protected $table = 'users_email';  // sesuaikan nama tabel

    protected $fillable = ['id_user', 'email'];

    public $timestamps = false;  // Nonaktifkan timestamp otomatis

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
