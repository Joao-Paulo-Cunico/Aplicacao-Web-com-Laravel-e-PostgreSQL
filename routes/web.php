<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('pecas', \App\Http\Controllers\PecaController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
