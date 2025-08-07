<?php

use App\Http\Controllers\EstatisticasController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('carregar-todas-estatisticas', [EstatisticasController::class, 'index']);
Route::get('carregar-estatisticas-ultimo-jogo', [EstatisticasController::class, 'obterDadosUltimaPartida']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
