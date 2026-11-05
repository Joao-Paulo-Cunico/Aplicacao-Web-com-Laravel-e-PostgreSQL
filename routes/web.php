<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\OrdemServicoController;
use App\Http\Controllers\OrdemServicoItemController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\PecaController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\VeiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('dashboard.index'))->name('dashboard');

// Léo Burei
Route::resource('clientes', ClienteController::class);
Route::resource('marcas', MarcaController::class);
Route::resource('modelos', ModeloController::class);
Route::resource('veiculos', VeiculoController::class);
Route::patch('clientes/{cliente}/reativar', [ClienteController::class, 'reativar'])->name('clientes.reativar');
Route::patch('veiculos/{veiculo}/reativar', [VeiculoController::class, 'reativar'])->name('veiculos.reativar');

// João Paulo
Route::resource('mecanicos', MecanicoController::class);
Route::resource('servicos', ServicoController::class);
Route::resource('ordens-servico', OrdemServicoController::class)
    ->parameters(['ordens-servico' => 'ordemServico']);
Route::post('ordens-servico/{ordemServico}/servicos', [OrdemServicoItemController::class, 'storeServico'])->name('ordens-servico.servicos.store');
Route::post('ordens-servico/{ordemServico}/pecas', [OrdemServicoItemController::class, 'storePeca'])->name('ordens-servico.pecas.store');
Route::post('ordens-servico/{ordemServico}/fechar', [OrdemServicoController::class, 'fechar'])->name('ordens-servico.fechar');
Route::post('ordens-servico/{ordemServico}/cancelar', [OrdemServicoController::class, 'cancelar'])->name('ordens-servico.cancelar');

// Luis Molon
Route::resource('pecas', PecaController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
Route::post('pecas/{peca}/repor-estoque', [PecaController::class, 'reporEstoque'])->name('pecas.repor-estoque');
Route::resource('pagamentos', PagamentoController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
