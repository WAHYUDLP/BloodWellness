<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('blood_group') || !Session::has('calories')) {
            return redirect()->route('planner.create');
        }

        $group = Session::get('blood_group');
        $calories = (int) Session::get('calories');

        $macrosMap = [
            'O' => ['carb' => 25, 'fat' => 25, 'prot' => 50],
            'A' => ['carb' => 50, 'fat' => 25, 'prot' => 25],
            'B' => ['carb' => 37.5, 'fat' => 25, 'prot' => 37.5],
            'AB' => ['carb' => 40, 'fat' => 25, 'prot' => 35],
        ];

        $grams = [
            'carb' => round(($calories * $macrosMap[$group]['carb'] / 100) / 4),
            'fat' => round(($calories * $macrosMap[$group]['fat'] / 100) / 9),
            'prot' => round(($calories * $macrosMap[$group]['prot'] / 100) / 4),
        ];

        $phases = ['Sarapan', 'Makan Siang', 'Camilan', 'Makan Malam'];

        $menus = Session::get('menus', []);

        $refreshPhase = $request->query('refresh');
        foreach ($phases as $phase) {
            if ($refreshPhase === null || $refreshPhase === $phase) {
                $menus = DB::table('menu_items')
                    ->where('blood_group', $group)
                    ->where('phase', $phase)
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                session()->put("menu.$phase", $menus); // hanya fase tertentu yang berubah
            }
        }

        Session::put('menus', $menus); // simpan/update ke session

        return view('pages.menu', compact('group', 'calories', 'grams', 'phases', 'menus'));
    }

}