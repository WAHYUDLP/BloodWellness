<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Menampilkan form edit profil
    public function edit()
    {
        $user = Auth::user();

        // Ambil email tambahan
        $emailsTambahan = DB::table('users_email')
            ->where('id_user', $user->id)
            ->get();

        return view('pages.editProfile', compact('user', 'emailsTambahan'));
    }

    // // Update profil utama
    public function update(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'jenis_kelamin' => 'required|string',
            'negara' => 'required|string',
            'bahasa' => 'nullable|string',
            'photo_url' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // validasi foto
        ]);

        $user = auth()->user();

        // // Jika ada upload foto
        // if ($request->hasFile('photo_url')) {
        //     // Simpan foto ke storage/app/public/profile_photos
        //     $path = $request->file('photo_url')->store('profile_photos', 'public');
        //     $user->photo_url = $path;
        // }

        // Update field lain
        $user->fill($validated);
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }



}
