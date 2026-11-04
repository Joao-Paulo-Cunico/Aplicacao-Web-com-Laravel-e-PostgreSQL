<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\OrdemServicoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('mecanicos', MecanicoController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('ordens-servico', OrdemServicoController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->parameters(['ordens-servico' => 'ordemServico']);