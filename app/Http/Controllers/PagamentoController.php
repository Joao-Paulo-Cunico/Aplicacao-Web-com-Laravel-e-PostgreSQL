<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
 $pagamentos = Pagamento::select('pagamento.*')
        ->selectRaw('oficina_mecanica.fn_total_pago_ordem(ordem_servico_id) AS total_pago_os')
        ->orderByDesc('id_pagamento')
        ->paginate(15);

    return view('pagamentos.index', compact('pagamentos'));
}

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('pagamentos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'ordem_servico_id' => 'required|integer|exists:ordem_servico,id_ordem_servico',
            'data_pagamento' => 'required|date',
            'valor' => 'required|numeric|gt:0',
            'forma_pagamento' => 'required|in:PIX,DINHEIRO,CREDITO,DEBITO,TRANSFERENCIA',
            'status' => 'required|in:PAGO,PENDENTE,CANCELADO',
        ]);

        Pagamento::create($dados);

        return redirect()
            ->route('pagamentos.index')
            ->with('success', 'Pagamento cadastrado com sucesso!');
    }
    public function show(Pagamento $pagamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
 
    public function edit(Pagamento $pagamento)
    {
        return view('pagamentos.edit', compact('pagamento'));
    }


    public function update(Request $request, Pagamento $pagamento)
    {
        $dados = $request->validate([
            'ordem_servico_id' => 'required|integer|exists:ordem_servico,id_ordem_servico',
            'data_pagamento' => 'required|date',
            'valor' => 'required|numeric|gt:0|max:99999999.99',
            'forma_pagamento' => 'required|in:PIX,DINHEIRO,CREDITO,DEBITO,TRANSFERENCIA',
            'status' => 'required|in:PAGO,PENDENTE,CANCELADO',
        ]);

        $pagamento->update($dados);

        return redirect()
            ->route('pagamentos.index')
            ->with('success', 'Pagamento atualizado com sucesso!');
    }

 

    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();

        return redirect()
            ->route('pagamentos.index')
            ->with('success', 'Pagamento excluido com sucesso!');
    }

}
