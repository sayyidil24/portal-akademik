<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Semua rute diarahkan ke satu PageController (ketentuan slide 40)
Route::get('/', [PageController::class, 'beranda'])->name('beranda');
Route::get('/beranda', [PageController::class, 'beranda'])->name('beranda.alias'); // untuk /beranda?user=Andi
Route::get('/profil-mahasiswa', [PageController::class, 'profil'])->name('profil');
Route::get('/ide-agent', [PageController::class, 'ideAgent'])->name('ide-agent');
Route::post('/ide-agent', [PageController::class, 'kirimIde'])->name('ide-agent.kirim');
