<?php

use Illuminate\Support\Facades\Route;

// Rota para testar se a API está funcionando
Route::get('/teste', function () {
    return response()->json([
        'mensagem' => 'API da Oficina Mecanica funcionando!',
        'status' => 'ok'
    ]);
});


    Route::get('/pecas', [\App\Http\Controllers\Api\PecaApiController::class, 'index']);

    Route::get('/pecas/{peca}', [\App\Http\Controllers\Api\PecaApiController::class, 'show'])
    ->whereNumber('peca');

    Route::post('/pecas', [\App\Http\Controllers\Api\PecaApiController::class, 'store']);

    Route::put('/pecas/{peca}', [\App\Http\Controllers\Api\PecaApiController::class, 'update'])
    ->whereNumber('peca');

    Route::delete('/pecas/{peca}', [\App\Http\Controllers\Api\PecaApiController::class, 'destroy'])
    ->whereNumber('peca');