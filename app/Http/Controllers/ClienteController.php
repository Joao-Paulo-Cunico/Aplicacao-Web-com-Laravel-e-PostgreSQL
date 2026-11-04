<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim((string) $request->query('busca', ''));
        $status = $request->query('status', 'todos');

        $clientes = Cliente::query();

        // Pesquisa por nome ou CPF.
        if ($busca !== '') {
            $cpf = preg_replace('/\D/', '', $busca);

            $clientes->where(function ($query) use ($busca, $cpf) {
                $query->where('nome', 'ILIKE', '%' . $busca . '%');

                if ($cpf !== '') {
                    $query->orWhere('cpf', 'LIKE', '%' . $cpf . '%');
                }
            });
        }

        // Filtro por situação do cliente.
        if ($status === 'ativos') {
            $clientes->where('ativo', true);
        } elseif ($status === 'inativos') {
            $clientes->where('ativo', false);
        }

        $clientes = $clientes
            ->orderBy('nome')
            ->orderBy('id_cliente')
            ->paginate(30)
            ->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->input('cpf')),
            'telefone' => preg_replace('/\D/', '', (string) $request->input('telefone')),
        ]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:350'],
            'cpf' => [
                'required',
                'digits:11',
                Rule::unique('oficina_mecanica.cliente', 'cpf'),
            ],
            'telefone' => ['required', 'digits_between:10,11'],
            'email' => ['required', 'email', 'max:300'],
        ]);

        $dados['data_cadastro'] = now()->toDateString();
        $dados['create_at'] = now()->toDateString();
        $dados['updated_at'] = now()->toDateString();
        $dados['ativo'] = true;

        Cliente::create($dados);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->merge([
            'cpf' => preg_replace('/\D/', '', (string) $request->input('cpf')),
            'telefone' => preg_replace('/\D/', '', (string) $request->input('telefone')),
        ]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:350'],
            'cpf' => [
                'required',
                'digits:11',
                Rule::unique('oficina_mecanica.cliente', 'cpf')
                    ->ignore($cliente->id_cliente, 'id_cliente'),
            ],
            'telefone' => ['required', 'digits_between:10,11'],
            'email' => ['required', 'email', 'max:300'],
        ]);

        $dados['updated_at'] = now()->toDateString();

        $cliente->update($dados);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        // Desativa o cliente sem apagar seu histórico.
        $cliente->update([
            'ativo' => false,
            'updated_at' => now()->toDateString(),
        ]);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente desativado com sucesso!');
    }
}
