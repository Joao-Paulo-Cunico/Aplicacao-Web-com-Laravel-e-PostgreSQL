<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarcaController extends Controller
{
    public function index()
    {
        $marcas = Marca::orderBy('nome')->paginate(10);

        return view('marcas.index', compact('marcas'));
    }

    public function create()
    {
        return view('marcas.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
        ]);

        Marca::create($dados);

        return redirect()->route('marcas.index')
            ->with('success', 'Marca cadastrada com sucesso!');
    }

    public function show(Marca $marca)
    {
        return view('marcas.show', compact('marca'));
    }

    public function edit(Marca $marca)
    {
        return view('marcas.edit', compact('marca'));
    }

    public function update(Request $request, Marca $marca)
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
        ]);

        $marca->update($dados);

        return redirect()->route('marcas.index')
            ->with('success', 'Marca atualizada com sucesso!');
    }

    public function destroy(Marca $marca)
    {
        $marca->delete();

        return redirect()->route('marcas.index')
            ->with('success', 'Marca excluída com sucesso!');
    }
}
