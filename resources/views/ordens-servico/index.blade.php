@extends('layouts.app')

@section('title', 'Ordens de serviço')

@section('content')
    <div class="card">
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <a href="{{ route('ordens-servico.create') }}">Abrir nova OS</a>

        <form method="GET" action="{{ route('ordens-servico.index') }}">
            <label for="status">Filtrar por status</label>
            <select id="status" name="status" onchange="this.form.submit()">
                <option value="">Todos</option>
                @foreach (['Em andamento', 'Finalizada', 'Cancelada'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Abertura</th>
                    <th>Veículo</th>
                    <th>Mecânico</th>
                    <th>Status</th>
                    <th>Mão de obra</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ordens as $ordem)
                    <tr>
                        <td>{{ $ordem->id_ordem_servico }}</td>
                        <td>{{ $ordem->data_abertura->format('d/m/Y') }}</td>
                        <td>{{ $placas[$ordem->veiculo_id] ?? '—' }}</td>
                        <td>{{ $ordem->mecanico->nome ?? '—' }}</td>
                        <td>{{ $ordem->status }}</td>
                        <td>R$ {{ number_format($ordem->valor_mao_obra, 2, ',', '.') }}</td>
                        <td><a href="{{ route('ordens-servico.show', $ordem) }}">Ver</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Nenhuma ordem de serviço encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $ordens->links() }}
    </div>
@endsection