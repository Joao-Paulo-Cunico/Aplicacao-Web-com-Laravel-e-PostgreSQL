<?php

namespace App\Http\Controllers;

use App\Http\Requests\MecanicoRequest;
use App\Models\Mecanico;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MecanicoController extends Controller
{
    public function index(Request $request): View
    {
        $pesquisa = trim((string) $request->query('pesquisa', ''));

        $mecanicos = Mecanico::query()
            ->when($pesquisa !== '', function ($query) use ($pesquisa) {
                $query->where(function ($q) use ($pesquisa) {
                    $q->where('nome', 'ilike', '%' . $pesquisa . '%')
                      ->orWhere('cpf', 'ilike', '%' . $pesquisa . '%')
                      ->orWhere('telefone', 'ilike', '%' . $pesquisa . '%')
                      ->orWhere('especilidade', 'ilike', '%' . $pesquisa . '%');
                });
            })
            ->orderBy('nome')
            ->paginate(30)
            ->withQueryString();

        return view('mecanicos.index', compact('mecanicos', 'pesquisa'));
    }

    public function create(): View
    {
        return view('mecanicos.create');
    }

    public function store(MecanicoRequest $request): RedirectResponse
    {
        $dados = $request->validated();
        $dados['ativo'] = $request->boolean('ativo');

        Mecanico::create($dados);

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecânico cadastrado com sucesso.');
    }

    public function show(Mecanico $mecanico): View
    {
        return view('mecanicos.show', compact('mecanico'));
    }

    public function edit(Mecanico $mecanico): View
    {
        return view('mecanicos.edit', compact('mecanico'));
    }

    public function update(MecanicoRequest $request, Mecanico $mecanico): RedirectResponse
    {
        $dados = $request->validated();
        $dados['ativo'] = $request->boolean('ativo');

        $mecanico->update($dados);

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecânico atualizado com sucesso.');
    }

    public function destroy(Mecanico $mecanico): RedirectResponse
    {
        try {
            $mecanico->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return redirect()->route('mecanicos.index')
                    ->with('error', 'Não foi possível excluir: o mecânico possui ordens de serviço.');
            }

            throw $e;
        }

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecânico excluído com sucesso.');
    }
}
