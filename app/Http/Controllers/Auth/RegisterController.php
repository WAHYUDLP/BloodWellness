<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register', [
            'old_name' => Session::get('_old_input.name'),
            'old_email' => Session::get('_old_input.email'),
            'error' => Session::get('error'),
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'email' => 'required|email|unique:usersaccount,email',
            'password'          => 'required|min:6',
            'confirm-password'  => 'required|same:password',
        ], [
            'required' => 'Semua field wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'min' => 'Password minimal 6 karakter.',
            'same' => 'Password dan konfirmasi tidak sama.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
                // ->with(key: 'error', $validator->errors()->first());
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        session([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil!');
    }
}
