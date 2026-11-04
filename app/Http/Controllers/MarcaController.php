<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class MarcaController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));

        $marcas = Marca::query();

        if ($busca !== '') {
            $marcas->where('nome', 'ILIKE', '%' . $busca . '%');
        }

        $marcas = $marcas
            ->orderBy('nome')
            ->orderBy('id_marca')
            ->paginate(30)
            ->withQueryString();

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
        // Impede exclusão de marcas com modelos vinculados.
        $possuiModelos = Modelo::where(
            'marca_id',
            $marca->id_marca
        )->exists();

        if ($possuiModelos) {
            return redirect()->route('marcas.index')
                ->with('error', 'Esta marca não pode ser excluída porque possui modelos vinculados.');
        }

        try {
            $marca->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return redirect()->route('marcas.index')
                    ->with('error', 'Esta marca possui registros vinculados e não pode ser excluída.');
            }

            throw $e;
        }

        return redirect()->route('marcas.index')
            ->with('success', 'Marca excluída com sucesso!');
    }
}
