
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

    <!-- Pesquisa e filtros -->
    <form method="GET"
          action="{{ route('clientes.index') }}"
          style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; background:#f2f5f8; padding:18px; border-radius:8px; margin-bottom:20px;">

        <div style="flex:2; min-width:220px;">
            <label for="busca"
                   style="display:block; margin-bottom:7px; font-weight:bold; font-size:13px;">
                Pesquisar cliente
            </label>

            <input type="text"
                   id="busca"
                   name="busca"
                   value="{{ request('busca') }}"
                   placeholder="Digite o nome ou CPF..."
                   style="width:100%; background:white;">
        </div>

        <div style="flex:1; min-width:150px;">
            <label for="status"
                   style="display:block; margin-bottom:7px; font-weight:bold; font-size:13px;">
                Status
            </label>

            <select id="status"
                    name="status"
                    style="width:100%; background:white;">

                <option value="todos"
                    {{ request('status', 'todos') == 'todos' ? 'selected' : '' }}>
                    Todos
                </option>

                <option value="ativos"
                    {{ request('status') == 'ativos' ? 'selected' : '' }}>
                    Ativos
                </option>

                <option value="inativos"
                    {{ request('status') == 'inativos' ? 'selected' : '' }}>
                    Inativos
                </option>
            </select>
        </div>

        <button type="submit"
                style="background:#064f80; height:38px;">
            Pesquisar
        </button>

        <a href="{{ route('clientes.index') }}"
           style="background:#e2e8f0; color:#334155; padding:11px 15px; border-radius:5px; text-decoration:none; font-size:14px;">
            Limpar
        </a>
    </form>

    <!-- Quantidade de resultados -->
    <div style="margin-bottom:15px; color:#64748b; font-size:13px;">
        {{ $clientes->total() }}
        {{ $clientes->total() == 1 ? 'cliente encontrado' : 'clientes encontrados' }}
    </div>

    <!-- Tabela de clientes -->
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
                        <td colspan="6"
                            style="text-align:center; padding:25px;">
                            Nenhum cliente encontrado para os filtros informados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div style="margin-top:20px;">
        {{ $clientes->links() }}
    </div>

</div>

@endsection
