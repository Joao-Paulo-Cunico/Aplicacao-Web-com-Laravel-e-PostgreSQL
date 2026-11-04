<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\VeiculoController;

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::resource('clientes', ClienteController::class);

Route::resource('marcas', MarcaController::class);

Route::resource('modelos', ModeloController::class);

Route::resource('veiculos', VeiculoController::class);

Route::patch('/clientes/{cliente}/reativar', [ClienteController::class, 'reativar'])
    ->name('clientes.reativar');

Route::patch('/veiculos/{veiculo}/reativar', [VeiculoController::class, 'reativar'])
    ->name('veiculos.reativar');