<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Models\Marca;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class ModeloController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));

        $modelos = Modelo::with('marca');

        // Pesquisa pelo nome do modelo ou da marca.
        if ($busca !== '') {
            $modelos->where(function ($query) use ($busca) {
                $query->where('nome', 'ILIKE', '%' . $busca . '%')
                    ->orWhereHas('marca', function ($marcaQuery) use ($busca) {
                        $marcaQuery->where('nome', 'ILIKE', '%' . $busca . '%');
                    });
            });
        }

        $modelos = $modelos
            ->orderBy('nome')
            ->orderBy('id_modelo')
            ->paginate(30)
            ->withQueryString();

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
            'ano_fim' => [
                'nullable',
                'date',
                'after_or_equal:ano_inicio',
            ],
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
            'ano_fim' => [
                'nullable',
                'date',
                'after_or_equal:ano_inicio',
            ],
        ]);

        $modelo->update($dados);

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo atualizado com sucesso!');
    }

    public function destroy(Modelo $modelo)
    {
        // Verifica se existem veículos vinculados ao modelo.
        $possuiVeiculos = Veiculo::where(
            'modelo_id',
            $modelo->id_modelo
        )->exists();

        if ($possuiVeiculos) {
            return redirect()->route('modelos.index')
                ->with(
                    'error',
                    'Este modelo não pode ser excluído porque possui veículos vinculados.'
                );
        }

        // Proteção adicional contra violações de chave estrangeira.
        try {
            $modelo->delete();
        } catch (QueryException $e) {
            if ($e->getCode() === '23503') {
                return redirect()->route('modelos.index')
                    ->with(
                        'error',
                        'Este modelo possui registros vinculados e não pode ser excluído.'
                    );
            }

            throw $e;
        }

        return redirect()->route('modelos.index')
            ->with('success', 'Modelo excluído com sucesso!');
    }
}
