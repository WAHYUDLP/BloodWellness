<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes'; // pastikan nama tabelnya benar
    protected $fillable = [
        'title',
        'ingredients',
        'instructions',
        'planner_id', // relasi ke planner jika ada
    ];

    public function planner()
    {
        return $this->belongsTo(Planner::class);
    }
}
