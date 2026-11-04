
@extends('layouts.app')

@section('content')
    <h1>Veículos Cadastrados</h1>

    <p>Gerenciamento dos veículos da oficina mecânica.</p>

    <a href="{{ route('veiculos.create') }}">
        Novo Veículo
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

                    <td>{{ $veiculo->placa }}</td>

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
                        {{ number_format($veiculo->quilometragem, 0, ',', '.') }} km
                    </td>

                    <td>
                        {{ $veiculo->ativo ? 'Ativo' : 'Inativo' }}
                    </td>

                    <td>
                        <a href="{{ route('veiculos.show', $veiculo) }}">
                            Ver
                        </a>

                        <a href="{{ route('veiculos.edit', $veiculo) }}">
                            Editar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">
                        Nenhum veículo cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $veiculos->links() }}
    </div>
@endsection
