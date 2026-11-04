
@extends('layouts.app')

@section('content')
    <h1>Modelos de Veículos</h1>

    <p>Gerenciamento dos modelos cadastrados no sistema.</p>

    <a href="{{ route('modelos.create') }}">
        Novo Modelo
    </a>

    <hr>

    @if (session('success'))
        <p style="color: green;">
            {{ session('success') }}
        </p>
    @endif

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
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
                        <a href="{{ route('modelos.show', $modelo) }}">
                            Ver
                        </a>

                        <a href="{{ route('modelos.edit', $modelo) }}">
                            Editar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">
                        Nenhum modelo cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $modelos->links() }}
    </div>
@endsection
