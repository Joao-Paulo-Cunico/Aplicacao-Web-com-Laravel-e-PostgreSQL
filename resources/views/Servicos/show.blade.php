@extends('layouts.app')

@section('title', 'Detalhes do serviço')

@section('content')
    <div class="card">
        <p><strong>Nome:</strong> {{ $servico->nome }}</p>
        <p><strong>Descrição:</strong> {{ $servico->descricao }}</p>
        <p><strong>Preço base:</strong> R$ {{ number_format($servico->preco_base, 2, ',', '.') }}</p>
        <p><strong>Tempo estimado:</strong> {{ $servico->tempo_estimado }}</p>
        <p><strong>Ativo:</strong> {{ $servico->ativo ? 'Sim' : 'Não' }}</p>

        <a href="{{ route('servicos.edit', $servico) }}">Editar</a>
        <a href="{{ route('servicos.index') }}">Voltar</a>
    </div>
@endsection