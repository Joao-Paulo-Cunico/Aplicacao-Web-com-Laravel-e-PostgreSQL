
@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h3 style="margin:0 0 8px;">Clientes Cadastrados</h3>
            <p style="margin:0; color:#64748b;">
                Gerenciamento dos clientes da oficina mecânica.
            </p>
        </div>

        <a href="{{ route('clientes.create') }}"
           style="background:#064f80; color:white; padding:11px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
            + Novo Cliente
        </a>
    </div>

    @if (session('success'))
        <div style="background:#e8f5e9; color:#256029; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#ffebee; color:#b71c1c; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->nome }}</td>
                        <td>{{ $cliente->cpf }}</td>
                        <td>{{ $cliente->telefone }}</td>
                        <td>{{ $cliente->email }}</td>

                        <td>
                            @if ($cliente->ativo)
                                <span style="background:#e8f5e9; color:#256029; padding:6px 10px; border-radius:5px; font-size:12px; font-weight:bold;">
                                    Ativo
                                </span>
                            @else
                                <span style="background:#ffebee; color:#b71c1c; padding:6px 10px; border-radius:5px; font-size:12px; font-weight:bold;">
                                    Inativo
                                </span>
                            @endif
                        </td>

                        <td>
                            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

                                <a href="{{ route('clientes.show', $cliente) }}">
                                    Ver
                                </a>

                                <a href="{{ route('clientes.edit', $cliente) }}">
                                    Editar
                                </a>

                                @if ($cliente->ativo)
                                    <form action="{{ route('clientes.destroy', $cliente) }}"
                                          method="POST"
                                          style="margin:0;"
                                          onsubmit="return confirm('Deseja realmente desativar este cliente?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                style="background:#b91c1c; padding:7px 12px;">
                                            Desativar
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:25px;">
                            Nenhum cliente cadastrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $clientes->links() }}
    </div>

</div>

@endsection
