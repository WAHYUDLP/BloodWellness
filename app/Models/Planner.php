<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Planner extends Model
{
    protected $table = 'planners'; // atau 'planner' tergantung nama tabel di database kamu

    protected $fillable = [
        'user_id', 'tanggal', 'catatan', // sesuaikan kolom di tabel kamu
    ];

    // Kalau kamu punya relasi, misal ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
