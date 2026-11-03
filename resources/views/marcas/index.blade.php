
@extends('layouts.app')

@section('content')
    <h1>Marcas de Veículos</h1>

    <p>Gerenciamento das marcas cadastradas no sistema.</p>

    <a href="{{ route('marcas.create') }}">
        Nova Marca
    </a>

    <hr>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
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
                        <a href="{{ route('marcas.show', $marca) }}">
                            Ver
                        </a>

                        <a href="{{ route('marcas.edit', $marca) }}">
                            Editar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        Nenhuma marca cadastrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $marcas->links() }}
    </div>
@endsection
