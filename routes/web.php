<?php

use App\Http\Controllers\EstatisticasController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('painel', function () {
    return Inertia::render('auth/Panel');
})->middleware(['auth', 'verified'])->name('panel');

Route::get('carregar-todas-estatisticas', [EstatisticasController::class, 'index']);
Route::get('carregar-estatisticas-ultimo-jogo', [EstatisticasController::class, 'obterDadosUltimaPartida']);
Route::get('carregar-todas-estatisticas-do-ano/{ano}', [EstatisticasController::class, 'obterDadosPorAno']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
