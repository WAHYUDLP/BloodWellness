<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KalkulatorController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.kalkulator', [
            'session' => session()->all()
        ]);
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'gender' => 'required',
            'age' => 'required|numeric',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'activity' => 'required',
            'body_fat' => 'nullable'
        ]);

        $gender = $request->gender;
        $age = $request->age;
        $weight = $request->weight;
        $height = $request->height;
        $activity = $request->activity;

        // Rumus BMR (Harris-Benedict Formula)
        if ($gender === 'Pria') {
            $bmr = 66 + (13.7 * $weight) + (5 * $height) - (6.8 * $age);
        } else {
            $bmr = 655 + (9.6 * $weight) + (1.8 * $height) - (4.7 * $age);
        }

        // Aktivitas
        $activity_factor = match ($activity) {
            'Sangat Rendah' => 1.2,
            'Ringan' => 1.375,
            'Sedang' => 1.55,
            'Tinggi' => 1.725,
            'Sangat Tinggi' => 1.9,
            default => 1
        };

        $tdee = $bmr * $activity_factor;

        $kalori = [
            'hilangkan_lemak' => round($tdee - 500),
            'pertahankan' => round($tdee),
            'bangun_massa_otot' => round($tdee + 500),
        ];

        // Simpan ke session
        session([
            'gender' => $gender,
            'age' => $age,
            'weight' => $weight,
            'height' => $height,
            'body_fat' => $request->body_fat,
            'activity' => $activity,
            'kalori' => $kalori,
            'diet' => 'pertahankan', // default diet
            'calories' => $kalori['pertahankan'], // simpan angka kalori default di session
        ]);

        return redirect()->route('kalkulator');
    }

    public function pilihDiet(Request $request)
    {
        $request->validate([
            'diet' => 'required|in:hilangkan_lemak,pertahankan,bangun_massa_otot',
        ]);

        if (session()->has('kalori')) {
            session([
                'diet' => $request->diet,
                'calories' => session('kalori')[$request->diet] // update kalori sesuai diet yang dipilih
            ]);
        }

        return redirect()->route('kalkulator')->with('scrollToHasil', true);
    }


    public function reset()
    {
        session()->forget([
            'gender',
            'age',
            'weight',
            'height',
            'body_fat',
            'activity',
            'kalori',
            'diet'
        ]);

        return redirect()->route('kalkulator');
    }
}
