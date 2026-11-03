@extends('layouts.app')

@section('title', 'Serviços')

@section('content')
    <div class="card">
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif

        <a href="{{ route('servicos.create') }}">Novo serviço</a>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço base</th>
                    <th>Tempo estimado</th>
                    <th>Ativo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($servicos as $servico)
                    <tr>
                        <td>{{ $servico->nome }}</td>
                        <td>{{ $servico->descricao }}</td>
                        <td>R$ {{ number_format($servico->preco_base, 2, ',', '.') }}</td>
                        <td>{{ $servico->tempo_estimado }}</td>
                        <td>{{ $servico->ativo ? 'Sim' : 'Não' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhum serviço cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $servicos->links() }}
    </div>
@endsection