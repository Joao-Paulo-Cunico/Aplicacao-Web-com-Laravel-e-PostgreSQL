
@extends('layouts.app')

@section('content')
    <h1>Detalhes do Veículo</h1>

    <p>Informações completas do veículo cadastrado.</p>

    <hr>

    <div style="margin-bottom: 20px;">

        <p>
            <strong>ID do Veículo:</strong>
            {{ $veiculo->id_veiculo }}
        </p>

        <p>
            <strong>Placa:</strong>
            {{ $veiculo->placa }}
        </p>

        <p>
            <strong>Proprietário:</strong>
            {{ $veiculo->cliente?->nome ?? 'Não informado' }}
        </p>

        <p>
            <strong>Marca:</strong>
            {{ $veiculo->modelo?->marca?->nome ?? 'Não informada' }}
        </p>

        <p>
            <strong>Modelo:</strong>
            {{ $veiculo->modelo?->nome ?? 'Não informado' }}
        </p>

        <p>
            <strong>Ano do Veículo:</strong>
            {{ $veiculo->ano?->format('Y') ?? '-' }}
        </p>

        <p>
            <strong>Cor:</strong>
            {{ $veiculo->cor }}
        </p>

        <p>
            <strong>Quilometragem:</strong>
            {{ number_format($veiculo->quilometragem, 0, ',', '.') }} km
        </p>

        <p>
            <strong>Data de Cadastro:</strong>
            {{ $veiculo->data_cadastro?->format('d/m/Y') ?? '-' }}
        </p>

        <p>
            <strong>Status:</strong>
            {{ $veiculo->ativo ? 'Ativo' : 'Inativo' }}
        </p>

    </div>

    <hr>

    <div style="margin-top: 20px;">

        <a href="{{ route('veiculos.edit', $veiculo) }}"
           style="margin-right: 15px;">
            Editar Veículo
        </a>

        <a href="{{ route('veiculos.index') }}">
            Voltar para Listagem
        </a>

    </div>
@endsection

