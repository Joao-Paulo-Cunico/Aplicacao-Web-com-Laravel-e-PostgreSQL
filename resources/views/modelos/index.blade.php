
@extends('layouts.app')

@section('title', 'Modelos de Veículos')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h3 style="margin:0 0 8px;">Modelos de Veículos</h3>
            <p style="margin:0; color:#64748b;">
                Gerenciamento dos modelos cadastrados no sistema.
            </p>
        </div>

        <a href="{{ route('modelos.create') }}"
           style="background:#064f80; color:white; padding:11px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
            + Novo Modelo
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

    <!-- Pesquisa -->
    <form method="GET"
          action="{{ route('modelos.index') }}"
          style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; background:#f2f5f8; padding:18px; border-radius:8px; margin-bottom:20px;">

        <div style="flex:1; min-width:220px;">
            <label for="busca"
                   style="display:block; margin-bottom:7px; font-weight:bold; font-size:13px;">
                Pesquisar modelo ou marca
            </label>

            <input type="text"
                   id="busca"
                   name="busca"
                   value="{{ request('busca') }}"
                   placeholder="Ex.: Corolla, Civic, Toyota..."
                   style="width:100%; background:white;">
        </div>

        <button type="submit"
                style="background:#064f80; height:38px;">
            Pesquisar
        </button>

        <a href="{{ route('modelos.index') }}"
           style="background:#e2e8f0; color:#334155; padding:11px 15px; border-radius:5px; text-decoration:none; font-size:14px;">
            Limpar
        </a>

    </form>

    <!-- Quantidade de resultados -->
    <div style="margin-bottom:15px; color:#64748b; font-size:13px;">
        {{ $modelos->total() }}
        {{ $modelos->total() == 1 ? 'modelo encontrado' : 'modelos encontrados' }}
    </div>

    <!-- Tabela -->
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Ano de Início</th>
                    <th>Ano de Fim</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($modelos as $modelo)
                    <tr>
                        <td>{{ $modelo->id_modelo }}</td>

                        <td>{{ $modelo->nome }}</td>

                        <td>
                            {{ $modelo->marca?->nome ?? 'Sem marca' }}
                        </td>

                        <td>
                            {{ $modelo->ano_inicio?->format('Y') ?? '-' }}
                        </td>

                        <td>
                            {{ $modelo->ano_fim?->format('Y') ?? 'Em produção' }}
                        </td>

                        <td>
                            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

                                <a href="{{ route('modelos.show', $modelo) }}">
                                    Ver
                                </a>

                                <a href="{{ route('modelos.edit', $modelo) }}">
                                    Editar
                                </a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            style="text-align:center; padding:25px;">
                            Nenhum modelo encontrado para a pesquisa informada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div style="margin-top:20px;">
        {{ $modelos->links() }}
    </div>

</div>

@endsection
