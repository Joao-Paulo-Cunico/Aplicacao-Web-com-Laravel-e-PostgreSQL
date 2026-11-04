<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\OrdemServicoItemController;

Route::get('/', function () {
    return view('welcome');
});

// João Paulo
Route::resource('mecanicos', MecanicoController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('ordens-servico', OrdemServicoController::class)
    ->only(['index', 'create', 'store', 'show'])
    ->parameters(['ordens-servico' => 'ordemServico']);
Route::post('ordens-servico/{ordemServico}/servicos', [OrdemServicoItemController::class, 'storeServico'])
    ->name('ordens-servico.servicos.store');
Route::post('ordens-servico/{ordemServico}/pecas', [OrdemServicoItemController::class, 'storePeca'])
    ->name('ordens-servico.pecas.store');
Route::post('ordens-servico/{ordemServico}/fechar', [OrdemServicoController::class, 'fechar'])
    ->name('ordens-servico.fechar');
Route::post('ordens-servico/{ordemServico}/cancelar', [OrdemServicoController::class, 'cancelar'])
    ->name('ordens-servico.cancelar');

// Luis Molon
Route::resource('pecas', \App\Http\Controllers\PecaController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

Route::post('/pecas/{peca}/repor-estoque',
    [\App\Http\Controllers\PecaController::class, 'reporEstoque']
)->name('pecas.repor-estoque');

Route::resource('pagamentos', \App\Http\Controllers\PagamentoController::class)
    ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
