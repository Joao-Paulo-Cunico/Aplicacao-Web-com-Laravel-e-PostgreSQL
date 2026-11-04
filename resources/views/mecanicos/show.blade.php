@extends('layouts.app')

@section('title', 'Detalhes do mecânico')

@section('content')
    <div class="card">
        <p><strong>Nome:</strong> {{ $mecanico->nome }}</p>
        <p><strong>CPF:</strong> {{ $mecanico->cpf }}</p>
        <p><strong>Telefone:</strong> {{ $mecanico->telefone }}</p>
        <p><strong>Especialidade:</strong> {{ $mecanico->especilidade }}</p>
        <p><strong>Admissão:</strong> {{ $mecanico->data_admissao->format('d/m/Y') }}</p>
        <p><strong>Ativo:</strong> {{ $mecanico->ativo ? 'Sim' : 'Não' }}</p>

        <a href="{{ route('mecanicos.edit', $mecanico) }}">Editar</a>
        <a href="{{ route('mecanicos.index') }}">Voltar</a>
    </div>
@endsection