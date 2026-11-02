<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MecanicoController extends Controller
{
    public function index(): View
    {
        $mecanicos = Mecanico::orderBy('nome')->paginate(10);

        return view('mecanicos.index', compact('mecanicos'));
    }

    public function create(): View
    {
        return view('mecanicos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->only(['nome', 'cpf', 'telefone', 'especilidade', 'data_admissao']);
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

    public function update(Request $request, Mecanico $mecanico): RedirectResponse
    {
        $dados = $request->only(['nome', 'cpf', 'telefone', 'especilidade', 'data_admissao']);
        $dados['ativo'] = $request->boolean('ativo');

        $mecanico->update($dados);

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecânico atualizado com sucesso.');
    }

    public function destroy(Mecanico $mecanico): RedirectResponse
    {
        $mecanico->delete();

        return redirect()->route('mecanicos.index')
            ->with('success', 'Mecânico excluído com sucesso.');
    }
}