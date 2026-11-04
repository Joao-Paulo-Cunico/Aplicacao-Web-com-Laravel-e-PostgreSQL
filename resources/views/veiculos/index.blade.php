
@extends('layouts.app')

@section('title', 'Veículos')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h3 style="margin:0 0 8px;">Veículos Cadastrados</h3>
            <p style="margin:0; color:#64748b;">
                Gerenciamento dos veículos da oficina mecânica.
            </p>
        </div>

        <a href="{{ route('veiculos.create') }}"
           style="background:#064f80; color:white; padding:11px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
            + Novo Veículo
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
          action="{{ route('veiculos.index') }}"
          style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; background:#f2f5f8; padding:18px; border-radius:8px; margin-bottom:20px;">

        <div style="flex:2; min-width:220px;">
            <label for="busca"
                   style="display:block; margin-bottom:7px; font-weight:bold; font-size:13px;">
                Pesquisar veículo
            </label>

            <input type="text"
                   id="busca"
                   name="busca"
                   value="{{ request('busca') }}"
                   placeholder="Placa, proprietário, modelo ou marca..."
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

        <a href="{{ route('veiculos.index') }}"
           style="background:#e2e8f0; color:#334155; padding:11px 15px; border-radius:5px; text-decoration:none; font-size:14px;">
            Limpar
        </a>
    </form>

    <!-- Quantidade de resultados -->
    <div style="margin-bottom:15px; color:#64748b; font-size:13px;">
        {{ $veiculos->total() }}
        {{ $veiculos->total() == 1 ? 'veículo encontrado' : 'veículos encontrados' }}
    </div>

    <!-- Tabela de veículos -->
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Proprietário</th>
                    <th>Ano</th>
                    <th>Cor</th>
                    <th>Quilometragem</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($veiculos as $veiculo)
                    <tr>
                        <td>{{ $veiculo->id_veiculo }}</td>

                        <td>
                            <strong>{{ $veiculo->placa }}</strong>
                        </td>

                        <td>
                            {{ $veiculo->modelo?->marca?->nome ?? '-' }}
                        </td>

                        <td>
                            {{ $veiculo->modelo?->nome ?? '-' }}
                        </td>

                        <td>
                            {{ $veiculo->cliente?->nome ?? '-' }}
                        </td>

                        <td>
                            {{ $veiculo->ano?->format('Y') ?? '-' }}
                        </td>

                        <td>{{ $veiculo->cor }}</td>

                        <td>
                            {{ number_format($veiculo->quilometragem ?? 0, 0, ',', '.') }} km
                        </td>

                        <td>
                            @if ($veiculo->ativo)
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

                                <a href="{{ route('veiculos.show', $veiculo) }}">
                                    Ver
                                </a>

                                <a href="{{ route('veiculos.edit', $veiculo) }}">
                                    Editar
                                </a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10"
                            style="text-align:center; padding:25px;">
                            Nenhum veículo encontrado para os filtros informados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div style="margin-top:20px;">
        {{ $veiculos->links() }}
    </div>

</div>

@endsection
