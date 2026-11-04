
@extends('layouts.app')

@section('content')
    <h1>Detalhes do Modelo</h1>

    <p>Informações do modelo de veículo cadastrado.</p>

    <hr>

    <div style="margin-bottom: 20px;">

        <p>
            <strong>ID do Modelo:</strong>
            {{ $modelo->id_modelo }}
        </p>

        <p>
            <strong>Nome do Modelo:</strong>
            {{ $modelo->nome }}
        </p>

        <p>
            <strong>Marca:</strong>
            {{ $modelo->marca?->nome ?? 'Não informada' }}
        </p>

        <p>
            <strong>Início de Fabricação:</strong>
            {{ $modelo->ano_inicio?->format('d/m/Y') ?? '-' }}
        </p>

        <p>
            <strong>Fim de Fabricação:</strong>
            {{ $modelo->ano_fim?->format('d/m/Y') ?? 'Em produção' }}
        </p>

    </div>

    <a href="{{ route('modelos.edit', $modelo) }}"
       style="margin-right: 15px;">
        Editar Modelo
    </a>

    <a href="{{ route('modelos.index') }}">
        Voltar para Listagem
    </a>
@endsection
