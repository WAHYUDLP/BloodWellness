<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Mail\OTPVerificationMail;

class LupaPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.lupa_password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // Generate OTP 6 digit
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5);

        // Hapus OTP lama jika ada
        DB::table('password_resets')->where('email', $email)->delete();

        // Simpan OTP baru
        DB::table('password_resets')->insert([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            // 'updated_at' => Carbon::now()
        ]);

        // Kirim email OTP dengan Laravel Mail
        // Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
        //     $message->to($email)
        //         ->subject('Kode OTP Reset Password - BloodWellness')
        //         ->from('projecttiffilkom@gmail.com', 'BloodWellness');
        // });

        Mail::to(users: $email)->send(new OTPVerificationMail($otp));

        // Simpan email di session untuk langkah berikutnya
        session(['otp_email' => $email]);

        return redirect()->route('verifikasi_otp.form')->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan periksa email Anda untuk melanjutkan.');
    }

    public function showVerifyForm()
    {
        return view('auth.verifikasi_otp');
    }
    public function verifyOtp(Request $request)
    {
        $email = session('otp_email');
        $inputOtp = implode('', $request->input('otp'));

        if (!$email || !$inputOtp) {
            return back()->with('error', 'OTP tidak valid.');
        }

        $user = DB::table('password_resets')->where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'User tidak ditemukan.');
        }

        $expiresAt = Carbon::parse($user->expires_at);

        if ($user->otp != $inputOtp || Carbon::now()->gt($expiresAt)) {
            return back()->with('error', 'OTP salah atau sudah kedaluwarsa.');
        }

        session(['reset_email' => $email]);
        session(['otp_verified' => true]);
        return redirect()->route('reset_password.form'); // Ganti dengan route ke form reset password
    }

    public function showResetForm(Request $request)
    {
        // Pastikan user sudah verifikasi OTP dan ada session reset_email
        if (!$request->session()->has('otp_verified') || !$request->session()->get('otp_verified')) {
            return redirect()->route('verifikasi_otp.form')->with('error', 'Anda harus verifikasi OTP terlebih dahulu.');
        }

        return view('auth.reset_password'); // view tanpa layout
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);


        $email = session('reset_email');
        $otpVerified = session('otp_verified');

        if (!$email || !$otpVerified) {
            return redirect()->route('reset_password.form')->withErrors('Anda harus memverifikasi OTP terlebih dahulu.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('reset_password.form')->withErrors('User tidak ditemukan.');
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // Hapus OTP setelah reset sukses
        DB::table('password_resets')->where('email', $email)->delete();

        // Bersihkan session
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan masuk.');
    }
    public function resend(Request $request)
    {
        $email = session('otp_email');

        if (!$email) {
            return redirect()->route('lupa-password.form')->with('error', 'Email tidak ditemukan dalam sesi.');
        }

        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(5); // OTP berlaku 5 menit

        // Simpan ke session (opsional)
        session(['otp_code' => $otp]);

        // Simpan ke database
        DB::table('password_resets')
            ->updateOrInsert(
                ['email' => $email],
                [
                    'otp' => $otp,
                    'expires_at' => $expiresAt,
                    'created_at' => Carbon::now()
                ]
            );

        // Kirim ulang email OTP
        //  Mail::to(users: $email)->send(new OTPVerificationMail($otp));
        Mail::to($email)->send(new OTPVerificationMail($otp));

        // Mail::to($email)->send(new \App\Mail\OTPVerificationMail($otp));

        return back()->with('success', 'Kode OTP telah dikirim ulang ke email Anda.');
    }
}
