<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServicoRequest;
use App\Models\Servico;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicoController extends Controller
{
    public function index(Request $request): View
    {
        $pesquisa = trim((string) $request->query('pesquisa', ''));

        $servicos = Servico::query()
            ->when($pesquisa !== '', function ($query) use ($pesquisa) {
                $query->where(function ($q) use ($pesquisa) {
                    $q->where('nome', 'ilike', '%' . $pesquisa . '%')
                      ->orWhere('descricao', 'ilike', '%' . $pesquisa . '%');
                });
            })
            ->orderBy('nome')
            ->paginate(30)
            ->withQueryString();

        return view('servicos.index', compact('servicos', 'pesquisa'));
    }

    public function create(): View
    {
        return view('servicos.create');
    }

    public function store(ServicoRequest $request): RedirectResponse
    {
        $dados = $request->validated();
        $dados['ativo'] = $request->boolean('ativo');

        Servico::create($dados);

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço cadastrado com sucesso.');
    }

    public function show(Servico $servico): View
    {
        return view('servicos.show', compact('servico'));
    }

    public function edit(Servico $servico): View
    {
        return view('servicos.edit', compact('servico'));
    }

    public function update(ServicoRequest $request, Servico $servico): RedirectResponse
    {
        $dados = $request->validated();
        $dados['ativo'] = $request->boolean('ativo');

        $servico->update($dados);

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço atualizado com sucesso.');
    }

    public function destroy(Servico $servico): RedirectResponse
    {
        try {
            $servico->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return redirect()->route('servicos.index')
                    ->with('error', 'Não foi possível excluir: o serviço já foi usado em ordens de serviço.');
            }

            throw $e;
        }

        return redirect()->route('servicos.index')
            ->with('success', 'Serviço excluído com sucesso.');
    }
}
