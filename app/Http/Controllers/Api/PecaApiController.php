<?php

namespace App\Http\Controllers\Api;

use App\Models\Peca;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PecaResource;
use App\Services\PecaService;

class PecaApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
public function index(PecaService $pecaService)
{
    $pecas = $pecaService->listar();

    return PecaResource::collection($pecas);
}


    /**
     * Store a newly created resource in storage.
     */
    
public function store(Request $request, PecaService $pecaService)
{
    $dados = $request->validate([
        'nome' => 'required|string|max:150',
        'descricao' => 'required|string|max:500',
        'preco_custo' => 'required|numeric|between:0,99999999.99',
        'preco_venda' => 'required|numeric|between:0,99999999.99',
        'estoque' => 'required|integer|min:0',
        'ativo' => 'sometimes|boolean',
    ]);

    $dados['ativo'] = $dados['ativo'] ?? true;

    $peca = $pecaService->cadastrar($dados);

    return (new PecaResource($peca))
        ->response()
        ->setStatusCode(201);
}

    /**
     * Display the specified resource.
     */
   
public function show(int $peca, PecaService $pecaService)
{
    $pecaEncontrada = $pecaService->buscar($peca);

    return new PecaResource($pecaEncontrada);
}

    /**
     * Update the specified resource in storage.
     */
    
public function update(Request $request, int $peca, PecaService $pecaService)
{
    $pecaEncontrada = $pecaService->buscar($peca);

    $dados = $request->validate([
        'nome' => 'sometimes|required|string|max:150',
        'descricao' => 'sometimes|required|string|max:500',
        'preco_custo' => 'sometimes|required|numeric|between:0,99999999.99',
        'preco_venda' => 'sometimes|required|numeric|between:0,99999999.99',
        'estoque' => 'sometimes|required|integer|min:0',
        'ativo' => 'sometimes|required|boolean',
    ]);

    $pecaAtualizada = $pecaService->atualizar($pecaEncontrada, $dados);

    return new PecaResource($pecaAtualizada);
}


    /**
     * Remove the specified resource from storage.
     */
    
public function destroy(int $peca, PecaService $pecaService)
{
    $pecaEncontrada = $pecaService->buscar($peca);

    $pecaService->excluir($pecaEncontrada);

    return response()->noContent();
}

}
