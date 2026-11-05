<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrdemServicoRequest;
use App\Models\Mecanico;
use App\Models\OrdemServico;
use App\Models\Servico;
use App\Services\OrdemServicoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrdemServicoController extends Controller
{
    public function __construct(
        private OrdemServicoService $service
    ) {}

    public function index(Request $request): View
    {
        $pesquisa = trim((string) $request->query('pesquisa', ''));
        $status = (string) $request->query('status', '');

        $ordens = OrdemServico::with('mecanico')
            ->when($pesquisa !== '', function ($query) use ($pesquisa) {
                $query->where(function ($q) use ($pesquisa) {

                    if (ctype_digit($pesquisa)) {
                        $q->where(
                            'id_ordem_servico',
                            (int) $pesquisa
                        )->orWhere(
                            'veiculo_id',
                            (int) $pesquisa
                        );
                    }

                    $q->orWhereHas('mecanico', function ($m) use ($pesquisa) {
                        $m->where(
                            'nome',
                            'ilike',
                            '%' . $pesquisa . '%'
                        );
                    });

                    $q->orWhereIn('veiculo_id', function ($sub) use ($pesquisa) {
                        $sub->select('id_veiculo')
                            ->from('oficina_mecanica.veiculo')
                            ->where(
                                'placa',
                                'ilike',
                                '%' . $pesquisa . '%'
                            );
                    });
                });
            })
            ->when(
                in_array(
                    $status,
                    ['Em andamento', 'Finalizada', 'Cancelada'],
                    true
                ),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByDesc('data_abertura')
            ->paginate(30)
            ->withQueryString();

        $placas = DB::table('oficina_mecanica.veiculo')
            ->whereIn(
                'id_veiculo',
                $ordens->pluck('veiculo_id')
            )
            ->pluck('placa', 'id_veiculo');

        return view('ordens-servico.index', compact(
            'ordens',
            'placas',
            'pesquisa',
            'status'
        ));
    }

    public function create(): View
    {
        $mecanicos = Mecanico::where('ativo', true)
            ->orderBy('nome')
            ->get();

        $veiculos = DB::table('oficina_mecanica.veiculo')
            ->where('ativo', true)
            ->orderBy('placa')
            ->get(['id_veiculo', 'placa']);

        return view('ordens-servico.create', compact(
            'mecanicos',
            'veiculos'
        ));
    }

    public function store(
        OrdemServicoRequest $request
    ): RedirectResponse {
        $ordem = $this->service->abrir(
            $request->validated()
        );

        return redirect()
            ->route('ordens-servico.show', $ordem)
            ->with(
                'success',
                'Ordem de serviço aberta com sucesso.'
            );
    }

    public function show(
        OrdemServico $ordemServico
    ): View {
        $ordemServico->load([
            'mecanico',
            'servicos'
        ]);

        $placa = DB::table('oficina_mecanica.veiculo')
            ->where(
                'id_veiculo',
                $ordemServico->veiculo_id
            )
            ->value('placa');

        $pecas = DB::table(
            'oficina_mecanica.ordem_servico_peca as osp'
        )
            ->join(
                'oficina_mecanica.peca as p',
                'p.id_peca',
                '=',
                'osp.peca_id'
            )
            ->where(
                'osp.ordem_servico_id',
                $ordemServico->id_ordem_servico
            )
            ->get([
                'p.nome',
                'osp.quantidade',
                'osp.valor_unitario',
                'osp.desconto'
            ]);

        $totais = $this->service
            ->calcularTotais($ordemServico);

        $totalViaFuncao = null;

        try {
            $totalViaFuncao = $this->service
                ->calcularTotalViaFuncao($ordemServico);
        } catch (\Illuminate\Database\QueryException $e) {
            // Mantém a tela disponível caso a função SQL
            // ainda não esteja configurada no Neon.
        }

        $servicosDisponiveis = Servico::where('ativo', true)
            ->orderBy('nome')
            ->get();

        $pecasDisponiveis = DB::table(
            'oficina_mecanica.peca'
        )
            ->where('ativo', true)
            ->where('estoque', '>', 0)
            ->orderBy('nome')
            ->get([
                'id_peca',
                'nome',
                'preco_venda',
                'estoque'
            ]);

        return view('ordens-servico.show', [
            'ordem' => $ordemServico,
            'placa' => $placa,
            'pecas' => $pecas,
            'totais' => $totais,
            'totalViaFuncao' => $totalViaFuncao,
            'servicosDisponiveis' => $servicosDisponiveis,
            'pecasDisponiveis' => $pecasDisponiveis
        ]);
    }

    public function fechar(
        OrdemServico $ordemServico
    ): RedirectResponse {
        try {
            $this->service->fechar($ordemServico);

            return redirect()
                ->route(
                    'ordens-servico.show',
                    $ordemServico
                )
                ->with(
                    'success',
                    'Ordem de serviço finalizada com sucesso.'
                );

        } catch (ValidationException $exception) {
            return back()
                ->withErrors($exception->errors())
                ->with(
                    'error',
                    'Não foi possível finalizar a ordem de serviço.'
                );
        }
    }

    public function cancelar(
        OrdemServico $ordemServico
    ): RedirectResponse {
        try {
            $this->service->cancelar($ordemServico);

            return redirect()
                ->route(
                    'ordens-servico.show',
                    $ordemServico
                )
                ->with(
                    'success',
                    'Ordem de serviço cancelada com sucesso.'
                );

        } catch (ValidationException $exception) {
            return back()
                ->withErrors($exception->errors())
                ->with(
                    'error',
                    'Não foi possível cancelar a ordem de serviço.'
                );
        }
    }
}
