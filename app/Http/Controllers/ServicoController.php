<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServicoController extends Controller
{
    public function index(): View
    {
        $servicos = Servico::orderBy('nome')->paginate(10);

        return view('servicos.index', compact('servicos'));
    }

    public function create(): View
    {
        return view('servicos.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->only(['nome', 'descricao', 'preco_base', 'tempo_estimado']);
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

    public function update(Request $request, Servico $servico): RedirectResponse
    {
        $dados = $request->only(['nome', 'descricao', 'preco_base', 'tempo_estimado']);
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
            // 23503 = violação de chave estrangeira no PostgreSQL
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