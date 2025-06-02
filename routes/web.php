<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KalkulatorController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LupaPasswordController;

// -----------------------------------
// Authentication Routes
// -----------------------------------
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// -----------------------------------
// Public Pages
// -----------------------------------
Route::get('/beranda', [BerandaController::class, 'index'])->name('beranda');

// -----------------------------------
// Kalkulator Diet Routes
// -----------------------------------
Route::get('/kalkulator', [KalkulatorController::class, 'index'])->name('kalkulator');
Route::post('/kalkulator/hitung', [KalkulatorController::class, 'hitung'])->name('kalkulator.hitung');
Route::post('/kalkulator/reset', [KalkulatorController::class, 'reset'])->name('kalkulator.reset');
Route::post('/pilih-diet', [KalkulatorController::class, 'pilihDiet'])->name('pilih.diet');

// -----------------------------------
// Planner Routes
// -----------------------------------
// Halaman form input golongan darah & kalori
Route::get('/planner/create', function () {
    return view('pages.planner');
})->name('planner.create');

// Menyimpan data planner ke session dan redirect ke menu
Route::post('/planner', [PlannerController::class, 'store'])->name('planner.store');

// Halaman planner utama yang menampilkan menu sesuai session
Route::get('/planner', [PlannerController::class, 'index'])->middleware('auth')->name('planner');

// -----------------------------------
// Menu & Recipe Routes
// -----------------------------------
// Daftar menu
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

// Detail resep/menu
Route::get('/recipe/{id}', [RecipeController::class, 'show'])->name('recipe.show');


use App\Http\Controllers\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
});
// -----------------------------------

// Profile Routes

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');
});

Route::get('/', [BerandaController::class, 'index'])->name('beranda');


Route::post('/upload-photo', [ProfileController::class, 'upload'])->name('upload.photo');


Route::post('/email/tambah', [ProfileController::class, 'tambahEmail'])->middleware('auth');
Route::post('/akun.hapus', [ProfileController::class, 'hapus'])->name('akun.hapus');




Route::get('/lupa-password', [LupaPasswordController::class, 'showForm'])->name('lupa-password.form');
Route::post('/lupa-password/send-otp', [LupaPasswordController::class, 'sendOtp'])->name('lupa-password.sendOtp');

Route::get('/verifikasi-otp', [LupaPasswordController::class, 'showVerifyForm'])->name('verifikasi_otp.form');
Route::post('/verifikasi-otp', [LupaPasswordController::class, 'verifyOtp'])->name('verifikasi_otp.process');

// Route::get('/reset-password', [LupaPasswordController::class, 'showResetForm'])->name('reset_password.form');
Route::post('/reset-password', [LupaPasswordController::class, 'resetPassword'])->name('reset_password.submit');


Route::get('/lupa-password', [LupaPasswordController::class, 'showForm'])->name('lupa-password.form');
Route::get('/kirim-ulang-otp', [LupaPasswordController::class, 'resend'])->name('otp.resend');
// Route::delete('/profile/email/{id}/delete', [ProfileController::class, 'deleteEmail'])->name('profile.email.delete');
Route::delete('profile/email/{id}', [ProfileController::class, 'destroy'])->name('profile.email.delete');
Route::get('/reset-password', [LupaPasswordController::class, 'showResetForm'])
    ->name('reset_password.form')
    ->middleware('guest');  // biasanya pakai middleware guest
Route::get('/menu/reset', [MenuController::class, 'reset'])->name('menu.reset');
