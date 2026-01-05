<?php

use App\Http\Controllers\EstatisticasController;
use App\Http\Controllers\JogoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('painel', function () {
    return Inertia::render('auth/Panel');
})->middleware(['auth'])->name('panel');

Route::get('editar-partida/{id}', [EstatisticasController::class, 'obterDadosDaPartida'])
    ->middleware(['auth'])
    ->name('edit.match');

Route::get('carregar-todas-estatisticas', [EstatisticasController::class, 'index']);
Route::get('carregar-estatisticas-ultimo-jogo', [EstatisticasController::class, 'obterDadosUltimaPartida']);
Route::get('carregar-todas-estatisticas-do-ano/{ano}', [EstatisticasController::class, 'obterDadosPorAno']);
Route::get('carregar-estatisticas-ano/{ano}', [EstatisticasController::class, 'filtrarPorAno']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
