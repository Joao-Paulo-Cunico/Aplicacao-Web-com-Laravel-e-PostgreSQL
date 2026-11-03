<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::resource('clientes', ClienteController::class);
