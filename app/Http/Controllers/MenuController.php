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

        $refreshPhase = $request->query('refresh');
        $menus = [];

        foreach ($phases as $phase) {
            if ($refreshPhase === null || $refreshPhase === $phase) {
                $phaseMenus = DB::table('menu_items')
                    ->where('blood_group', $group)
                    ->where('phase', $phase)
                    ->inRandomOrder()
                    ->limit(3)
                    ->get();
                session()->put("menu.$phase", $phaseMenus); // update session
            } else {
                $phaseMenus = session("menu.$phase", collect());
            }

            $menus[$phase] = $phaseMenus;
        }

        session()->put('menus', $menus); // ini opsional, karena sudah disimpan per-phase

        return view('pages.menu', compact('group', 'calories', 'grams', 'phases', 'menus'));
    }
    // Method untuk reset menu, dipanggil dari tombol "Buat Ulang"
    public function reset()
    {
        // Hapus session menu supaya generate ulang di index nanti
        Session::forget('menus');
        Session::forget(['menu.Sarapan', 'menu.Makan Siang', 'menu.Camilan', 'menu.Makan Malam']);

        // Hapus session yang terkait dengan planner juga
        Session::forget('blood_group');
        Session::forget('calories');

        // Redirect ke halaman planner.create supaya user input ulang data
        return redirect()->route('planner.create');
    }
}
