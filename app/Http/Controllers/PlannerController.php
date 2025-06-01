<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PlannerController extends Controller
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
    $menus = session('menus', []); // ambil dari session, kalau belum ada, pakai []

    $refresh = $request->query('refresh'); // bisa 'all' atau nama phase

    foreach ($phases as $phase) {
        if (!isset($menus[$phase]) || $refresh === $phase || $refresh === 'all') {
            $menus[$phase] = DB::table('menu_items')
                ->where('blood_group', $group)
                ->where('phase', $phase)
                ->inRandomOrder()
                ->limit(3)
                ->get();
        }
    }

    session(['menus' => $menus]); // simpan ke session

    return view('pages.planner', compact('group', 'calories', 'grams', 'phases', 'menus'));
}


    public function store(Request $request)
    {
        // Simpan data dari form planner ke session
        session([
            'blood_group' => $request->input('blood_group'),
            'calories' => $request->input('calories'),
        ]);

        // Redirect ke halaman menu
        return redirect()->route('menu.index')->with('scrollToMenu', true);

        // return redirect()->route('menu.index');
    }
}
