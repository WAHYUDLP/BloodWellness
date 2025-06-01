<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $fillable = [
        'name', 'image', 'prot_pct', 'carb_pct', 'fat_pct',
        'ingredients', 'steps'
    ];
}
