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
        // Gunakan Katch-McArdle jika body_fat diisi
        if (!empty($body_fat)) {
            $body_fat_percentage = $body_fat / 100; // konversi ke desimal
            $lbm = $weight * (1 - $body_fat_percentage); // massa tanpa lemak
            $bmr = 370 + (21.6 * $lbm);
        } else {
            // BMR Mifflin-St Jeor
            if ($gender === 'Pria') {
                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
            } else {
                $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
            }
        }

        // Faktor aktivitas (disesuaikan)
        $activity_factor = match ($activity) {
            'Sangat Rendah' => 1.2,
            'Ringan' => 1.3,
            'Sedang' => 1.45,
            'Tinggi' => 1.6,
            'Sangat Tinggi' => 1.75,
            default => 1
        };

        $tdee = $bmr * $activity_factor;

        // Kalori
        $kalori = [
            'hilangkan_lemak' => max(1200, round($tdee - 300)),
            'pertahankan' => round($tdee),
            'bangun_massa_otot' => round($tdee + 300),
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
