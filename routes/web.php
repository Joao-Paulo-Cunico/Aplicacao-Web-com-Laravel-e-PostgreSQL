<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pecas', \App\Http\Controllers\PecaController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

Route::resource('pagamentos', \App\Http\Controllers\PagamentoController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);