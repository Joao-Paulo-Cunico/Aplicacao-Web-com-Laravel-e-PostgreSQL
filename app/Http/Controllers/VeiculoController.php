<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Cliente;
use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VeiculoController extends Controller
{
    public function index()
    {
        $veiculos = Veiculo::with([
            'cliente',
            'modelo.marca'
        ])
        ->orderBy('id_veiculo', 'desc')
        ->paginate(30);

        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();

        $modelos = Modelo::with('marca')
            ->orderBy('nome')
            ->get();

        return view('veiculos.create', compact(
            'clientes',
            'modelos'
        ));
    }

    public function store(Request $request)
    {
        $request->merge([
            'placa' => strtoupper(
                preg_replace(
                    '/[^A-Za-z0-9]/',
                    '',
                    (string) $request->input('placa')
                )
            ),
        ]);

        $dados = $request->validate([
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('oficina_mecanica.cliente', 'id_cliente'),
            ],
            'modelo_id' => [
                'required',
                'integer',
                Rule::exists('oficina_mecanica.modelo', 'id_modelo'),
            ],
            'placa' => [
                'required',
                'regex:/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/',
                Rule::unique('oficina_mecanica.veiculo', 'placa'),
            ],
            'ano' => ['required', 'date'],
            'cor' => ['required', 'string', 'max:20'],
            'quilometragem' => ['required', 'integer', 'min:0'],
        ]);

        $dados['data_cadastro'] = now()->toDateString();
        $dados['ativo'] = true;

        Veiculo::create($dados);

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo cadastrado com sucesso!');
    }

    public function show(Veiculo $veiculo)
    {
        $veiculo->load('cliente', 'modelo.marca');

        return view('veiculos.show', compact('veiculo'));
    }

    public function edit(Veiculo $veiculo)
    {
        $clientes = Cliente::orderBy('nome')->get();

        $modelos = Modelo::with('marca')
            ->orderBy('nome')
            ->get();

        return view('veiculos.edit', compact(
            'veiculo',
            'clientes',
            'modelos'
        ));
    }

    public function update(Request $request, Veiculo $veiculo)
    {
        $request->merge([
            'placa' => strtoupper(
                preg_replace(
                    '/[^A-Za-z0-9]/',
                    '',
                    (string) $request->input('placa')
                )
            ),
        ]);

        $dados = $request->validate([
            'cliente_id' => [
                'required',
                'integer',
                Rule::exists('oficina_mecanica.cliente', 'id_cliente'),
            ],
            'modelo_id' => [
                'required',
                'integer',
                Rule::exists('oficina_mecanica.modelo', 'id_modelo'),
            ],
            'placa' => [
                'required',
                'regex:/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/',
                Rule::unique('oficina_mecanica.veiculo', 'placa')
                    ->ignore($veiculo->id_veiculo, 'id_veiculo'),
            ],
            'ano' => ['required', 'date'],
            'cor' => ['required', 'string', 'max:20'],
            'quilometragem' => ['required', 'integer', 'min:0'],
        ]);

        $veiculo->update($dados);

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo atualizado com sucesso!');
    }

    public function destroy(Veiculo $veiculo)
    {
        // Desativa o veículo sem apagar seu histórico.
        $veiculo->update([
            'ativo' => false,
        ]);

        return redirect()->route('veiculos.index')
            ->with('success', 'Veículo desativado com sucesso!');
    }
}
