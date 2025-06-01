<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\UserEmail; // Pastikan model UserEmail sudah ada

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Mohon maaf, Anda harus masuk terlebih dahulu untuk mengakses fitur ini.');
        }

        // Ambil email tambahan dari tabel manual
        $emailsTambahan = DB::table('users_email')->where('id_user', $user->id)->get();


        return view('pages.profil', compact('user', 'emailsTambahan'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path = $photo->store('public/profile_photos');
            $url = Storage::url($path);

            // Simpan URL ke database user
            $user = auth()->user();
            $user->photo_url = $url;
            $user->save();

            return response()->json([
                'success' => true,
                'new_url' => $url
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tidak ada file yang diunggah.'
        ]);
    }

    public function tambahEmail(Request $request)
    {
        try {
            $request->validate([
                'emailBaru' => 'required|email|unique:users_email,email',
            ]);

            UserEmail::create([
                'id_user' => auth()->id(),
                'email' => $request->emailBaru,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $user = auth()->user();

        // Cari email tambahan berdasarkan id dan user_id
        $emailTambahan = DB::table('users_email')
            ->where('id', $id)
            ->where('id_user', $user->id)
            ->first();

        if (!$emailTambahan) {
            return redirect()->route('profile.edit')->with('error', 'Email tidak ditemukan.');
        }

        // Hapus email tambahan
        DB::table('users_email')->where('id', $id)->delete();

        return redirect()->route('profile.edit')->with('success', 'Email tambahan berhasil dihapus.');
    }



    public function hapus(Request $request)
    {
        // Pastikan user sudah login dan ID cocok
        $user = Auth::user();

        if (!$user || $user->id != $request->input('user_id')) {
            return redirect()->back()->withErrors(['error' => 'User tidak valid atau tidak ditemukan.']);
        }

        // Logout dulu supaya sesi clear
        Auth::logout();

        // Hapus user
        $user->delete();

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('status', 'Akun Anda berhasil dihapus.');
    }
}
