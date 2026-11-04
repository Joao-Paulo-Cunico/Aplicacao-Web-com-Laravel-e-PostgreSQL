<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModeloController extends Controller
{
    public function index()
    {
        $modelos = Modelo::with('marca')
            ->orderBy('nome')
            ->paginate(10);

        return view('modelos.index', compact('modelos'));
    }

    public function create()
    {
        $marcas = Marca::orderBy('nome')->get();

        return view('modelos.create', compact('marcas'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'marca_id' => [
                'required',
                'integer',
                Rule::exists('oficina_mecanica.marca', 'id_marca'),
            ],
            'nome' => ['required', 'string', 'max:75'],
            'ano_inicio' => ['required', 'date'],
            'ano_fim' => ['nullable', 'date', 'after_or_equal:ano_inicio'],
        ]);

        Modelo::create($dados);

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo cadastrado com sucesso!');
    }

    public function show(Modelo $modelo)
    {
        $modelo->load('marca');

        return view('modelos.show', compact('modelo'));
    }

    public function edit(Modelo $modelo)
    {
        $marcas = Marca::orderBy('nome')->get();

        return view('modelos.edit', compact('modelo', 'marcas'));
    }

    public function update(Request $request, Modelo $modelo)
    {
        $dados = $request->validate([
            'marca_id' => [
                'required',
                'integer',
                Rule::exists('oficina_mecanica.marca', 'id_marca'),
            ],
            'nome' => ['required', 'string', 'max:75'],
            'ano_inicio' => ['required', 'date'],
            'ano_fim' => ['nullable', 'date', 'after_or_equal:ano_inicio'],
        ]);

        $modelo->update($dados);

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo atualizado com sucesso!');
    }

    public function destroy(Modelo $modelo)
    {
        $modelo->delete();

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo excluído com sucesso!');
    }
}
