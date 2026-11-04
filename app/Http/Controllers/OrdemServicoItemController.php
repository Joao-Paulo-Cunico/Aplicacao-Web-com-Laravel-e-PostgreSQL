<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrdemServicoItemController extends Controller
{
    public function __construct(private OrdemServicoService $service) {}

    public function storeServico(Request $request, OrdemServico $ordemServico): RedirectResponse
    {
        $dados = $request->validate([
            'servico_id' => ['required', 'integer'],
            'quantidade' => ['required', 'integer', 'min:1', 'max:9999'],
            'desconto' => ['nullable', 'numeric', 'min:0'],
        ], [
            'servico_id.required' => 'Selecione o serviço.',
            'quantidade.required' => 'Informe a quantidade.',
            'quantidade.min' => 'A quantidade deve ser de pelo menos 1.',
        ]);

        $this->service->adicionarServico(
            $ordemServico,
            (int) $dados['servico_id'],
            (int) $dados['quantidade'],
            (float) ($dados['desconto'] ?? 0)
        );

        return redirect()->route('ordens-servico.show', $ordemServico)
            ->with('success', 'Serviço incluído na ordem de serviço.');
    }

    public function storePeca(Request $request, OrdemServico $ordemServico): RedirectResponse
    {
        $dados = $request->validate([
            'peca_id' => ['required', 'integer'],
            'quantidade' => ['required', 'integer', 'min:1', 'max:9999'],
            'desconto' => ['nullable', 'numeric', 'min:0'],
        ], [
            'peca_id.required' => 'Selecione a peça.',
            'quantidade.required' => 'Informe a quantidade.',
            'quantidade.min' => 'A quantidade deve ser de pelo menos 1.',
        ]);

        $this->service->adicionarPeca(
            $ordemServico,
            (int) $dados['peca_id'],
            (int) $dados['quantidade'],
            (float) ($dados['desconto'] ?? 0)
        );

        return redirect()->route('ordens-servico.show', $ordemServico)
            ->with('success', 'Peça incluída na ordem de serviço.');
    }
}
