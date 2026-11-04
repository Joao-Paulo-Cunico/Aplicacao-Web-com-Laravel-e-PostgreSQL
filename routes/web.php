<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\OrdemServicoItemController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('mecanicos', MecanicoController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('ordens-servico', OrdemServicoController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->parameters(['ordens-servico' => 'ordemServico']);
Route::post('ordens-servico/{ordemServico}/servicos', [OrdemServicoItemController::class, 'storeServico'])
    ->name('ordens-servico.servicos.store');
Route::post('ordens-servico/{ordemServico}/pecas', [OrdemServicoItemController::class, 'storePeca'])
    ->name('ordens-servico.pecas.store');