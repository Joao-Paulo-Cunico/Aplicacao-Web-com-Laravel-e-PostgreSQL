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

class OrdemServicoController extends Controller
{
    public function __construct(private OrdemServicoService $service)
    {
    }

    public function index(Request $request): View
    {
        $ordens = OrdemServico::with('mecanico')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('data_abertura')
            ->paginate(15)
            ->withQueryString();

        return view('ordens-servico.index', compact('ordens'));
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

        return view('ordens-servico.show', ['ordem' => $ordemServico]);
    }
}