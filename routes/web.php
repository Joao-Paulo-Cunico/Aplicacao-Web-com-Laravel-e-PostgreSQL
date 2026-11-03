<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MecanicoController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('mecanicos', MecanicoController::class);