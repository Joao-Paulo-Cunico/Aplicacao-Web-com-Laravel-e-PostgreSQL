<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\ServicoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('mecanicos', MecanicoController::class);

Route::resource('servicos', ServicoController::class);