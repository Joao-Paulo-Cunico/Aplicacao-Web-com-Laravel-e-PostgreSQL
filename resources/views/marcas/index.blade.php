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
                            Nenhuma marca cadastrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $marcas->links() }}
    </div>

</div>

@endsection
