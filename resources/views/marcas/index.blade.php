
@extends('layouts.app')

@section('title', 'Marcas de Veículos')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h3 style="margin:0 0 8px;">Marcas de Veículos</h3>
            <p style="margin:0; color:#64748b;">
                Gerenciamento das marcas cadastradas no sistema.
            </p>
        </div>

        <a href="{{ route('marcas.create') }}"
           style="background:#064f80; color:white; padding:11px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
            + Nova Marca
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
          action="{{ route('marcas.index') }}"
          style="display:flex; gap:12px; align-items:end; flex-wrap:wrap; background:#f2f5f8; padding:18px; border-radius:8px; margin-bottom:20px;">

        <div style="flex:1; min-width:220px;">
            <label for="busca"
                   style="display:block; margin-bottom:7px; font-weight:bold; font-size:13px;">
                Pesquisar marca
            </label>

            <input type="text"
                   id="busca"
                   name="busca"
                   value="{{ request('busca') }}"
                   placeholder="Digite o nome da marca..."
                   style="width:100%; background:white;">
        </div>

        <button type="submit"
                style="background:#064f80; height:38px;">
            Pesquisar
        </button>

        <a href="{{ route('marcas.index') }}"
           style="background:#e2e8f0; color:#334155; padding:11px 15px; border-radius:5px; text-decoration:none; font-size:14px;">
            Limpar
        </a>

    </form>

    <!-- Quantidade de resultados -->
    <div style="margin-bottom:15px; color:#64748b; font-size:13px;">
        {{ $marcas->total() }}
        {{ $marcas->total() == 1 ? 'marca encontrada' : 'marcas encontradas' }}
    </div>

    <!-- Tabela -->
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Marca</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($marcas as $marca)
                    <tr>
                        <td>{{ $marca->id_marca }}</td>

                        <td>{{ $marca->nome }}</td>

                        <td>
                            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

                                <a href="{{ route('marcas.show', $marca) }}">
                                    Ver
                                </a>

                                <a href="{{ route('marcas.edit', $marca) }}">
                                    Editar
                                </a>

                                <form action="{{ route('marcas.destroy', $marca) }}"
                                      method="POST"
                                      style="margin:0;"
                                      onsubmit="return confirm('Deseja realmente excluir esta marca?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="background:#b91c1c; padding:7px 12px;">
                                        Excluir
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center; padding:25px;">
                            Nenhuma marca encontrada para a pesquisa informada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div style="margin-top:20px;">
        {{ $marcas->links() }}
    </div>

</div>

@endsection
