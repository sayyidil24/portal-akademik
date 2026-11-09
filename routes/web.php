<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Auth\DemoLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman portal (Tugas 4)
Route::get('/', [PageController::class, 'beranda'])->name('beranda');
Route::get('/beranda', [PageController::class, 'beranda'])->name('beranda.alias');
Route::get('/profil-mahasiswa', [PageController::class, 'profil'])->name('profil');
Route::get('/ide-agent', [PageController::class, 'ideAgent'])->name('ide-agent');
Route::post('/ide-agent', [PageController::class, 'kirimIde'])->name('ide-agent.kirim');

// Dashboard mahasiswa (Tugas 6): hanya untuk user yang sudah login
Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Panel admin AdminLTE (Tugas 10): hanya peran Admin atau Staf
Route::middleware(['auth', 'role:Admin|Staf'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
        Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');
    });

// Login instan akun uji (hanya aktif saat APP_ENV=local)
Route::post('/demo-login/{akun}', DemoLoginController::class)
    ->middleware('guest')
    ->name('demo-login');

// Rute profil akun bawaan Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute login, register, dan logout bawaan Breeze
require __DIR__.'/auth.php';
