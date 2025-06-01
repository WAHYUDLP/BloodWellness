<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function show($id)
    {
        if (!ctype_digit($id)) {
            return redirect()->route('menu.index'); // misal halaman daftar menu
        }

        $menu = DB::table('menu_items')->where('id', $id)->first();

        if (!$menu) {
            return redirect()->route('menu.index');
        }

        // Persentase makro default
        $prot = (int) ($menu->prot_pct ?? 0);
        $carb = (int) ($menu->carb_pct ?? 0);
        $fat  = (int) ($menu->fat_pct ?? 0);

        return view('pages.recipe', compact('menu', 'prot', 'carb', 'fat'));
    }
}
