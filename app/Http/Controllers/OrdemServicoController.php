<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdemServicoRequest;
use App\Models\Mecanico;
use App\Models\OrdemServico;
use App\Services\OrdemServicoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Servico;

class OrdemServicoController extends Controller
{
    public function __construct(private OrdemServicoService $service) {}

    public function index(Request $request): View
    {
        $ordens = OrdemServico::with('mecanico')
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByDesc('data_abertura')
            ->paginate(15)
            ->withQueryString();

        // Placas dos veículos da página (tenho que trocar por relacionamento quando o Model Veiculo chegar)
        $placas = DB::table('oficina_mecanica.veiculo')
            ->whereIn('id_veiculo', $ordens->pluck('veiculo_id'))
            ->pluck('placa', 'id_veiculo');

        return view('ordens-servico.index', compact('ordens', 'placas'));
    }

    public function create(): View
    {
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();

        $veiculos = DB::table('oficina_mecanica.veiculo')
            ->where('ativo', true)
            ->orderBy('placa')
            ->get(['id_veiculo', 'placa']);

        return view('ordens-servico.create', compact('mecanicos', 'veiculos'));
    }

    public function store(OrdemServicoRequest $request): RedirectResponse
    {
        $ordem = $this->service->abrir($request->validated());

        return redirect()->route('ordens-servico.show', $ordem)
            ->with('success', 'Ordem de serviço aberta com sucesso.');
    }

    public function show(OrdemServico $ordemServico): View
    {
        $ordemServico->load(['mecanico', 'servicos']);

        $placa = DB::table('oficina_mecanica.veiculo')
            ->where('id_veiculo', $ordemServico->veiculo_id)
            ->value('placa');

        $pecas = DB::table('oficina_mecanica.ordem_servico_peca as osp')
            ->join('oficina_mecanica.peca as p', 'p.id_peca', '=', 'osp.peca_id')
            ->where('osp.ordem_servico_id', $ordemServico->id_ordem_servico)
            ->get(['p.nome', 'osp.quantidade', 'osp.valor_unitario', 'osp.desconto']);

        $totais = $this->service->calcularTotais($ordemServico);

        $servicosDisponiveis = Servico::where('ativo', true)->orderBy('nome')->get();

        $pecasDisponiveis = DB::table('oficina_mecanica.peca')
            ->where('ativo', true)
            ->where('estoque', '>', 0)
            ->orderBy('nome')
            ->get(['id_peca', 'nome', 'preco_venda', 'estoque']);

        return view('ordens-servico.show', [
            'ordem' => $ordemServico,
            'placa' => $placa,
            'pecas' => $pecas,
            'totais' => $totais,
            'servicosDisponiveis' => $servicosDisponiveis,
            'pecasDisponiveis' => $pecasDisponiveis,
        ]);
    }
}
