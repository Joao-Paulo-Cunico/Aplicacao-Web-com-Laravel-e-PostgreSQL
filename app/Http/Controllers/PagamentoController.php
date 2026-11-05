<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function index(Request $request)
    {
        $pesquisa = trim((string) $request->query('pesquisa', ''));
        $status = (string) $request->query('status', '');

        $pagamentos = Pagamento::select('pagamento.*')
            ->selectRaw(
                'oficina_mecanica.fn_total_pago_ordem(ordem_servico_id) AS total_pago_os'
            )
            ->when($pesquisa !== '', function ($query) use ($pesquisa) {

                if (ctype_digit($pesquisa)) {
                    $query->where(function ($q) use ($pesquisa) {
                        $q->where(
                            'id_pagamento',
                            (int) $pesquisa
                        )->orWhere(
                            'ordem_servico_id',
                            (int) $pesquisa
                        );
                    });
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->when(
                in_array(
                    $status,
                    ['PAGO', 'PENDENTE', 'CANCELADO'],
                    true
                ),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByDesc('id_pagamento')
            ->paginate(30)
            ->withQueryString();

        return view('pagamentos.index', compact(
            'pagamentos',
            'pesquisa',
            'status'
        ));
    }

    public function create()
    {
        return view('pagamentos.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'ordem_servico_id' =>
                'required|integer|exists:ordem_servico,id_ordem_servico',

            'data_pagamento' =>
                'required|date',

            'valor' =>
                'required|numeric|gt:0',

            'forma_pagamento' =>
                'required|in:PIX,DINHEIRO,CREDITO,DEBITO,TRANSFERENCIA',

            'status' =>
                'required|in:PAGO,PENDENTE,CANCELADO',
        ]);

        Pagamento::create($dados);

        return redirect()
            ->route('pagamentos.index')
            ->with(
                'success',
                'Pagamento cadastrado com sucesso!'
            );
    }

    public function show(Pagamento $pagamento)
    {
        //
    }

    public function edit(Pagamento $pagamento)
    {
        return view('pagamentos.edit', compact('pagamento'));
    }

    public function update(
        Request $request,
        Pagamento $pagamento
    ) {
        $dados = $request->validate([
            'ordem_servico_id' =>
                'required|integer|exists:ordem_servico,id_ordem_servico',

            'data_pagamento' =>
                'required|date',

            'valor' =>
                'required|numeric|gt:0|max:99999999.99',

            'forma_pagamento' =>
                'required|in:PIX,DINHEIRO,CREDITO,DEBITO,TRANSFERENCIA',

            'status' =>
                'required|in:PAGO,PENDENTE,CANCELADO',
        ]);

        $pagamento->update($dados);

        return redirect()
            ->route('pagamentos.index')
            ->with(
                'success',
                'Pagamento atualizado com sucesso!'
            );
    }

    public function destroy(Pagamento $pagamento)
    {
        $pagamento->delete();

        return redirect()
            ->route('pagamentos.index')
            ->with(
                'success',
                'Pagamento excluído com sucesso!'
            );
    }
}
