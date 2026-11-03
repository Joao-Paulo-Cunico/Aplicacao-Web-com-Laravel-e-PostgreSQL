<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MarcaController;

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::resource('clientes', ClienteController::class);

Route::resource('marcas', MarcaController::class);
