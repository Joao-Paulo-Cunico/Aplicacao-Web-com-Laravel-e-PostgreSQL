
@extends('layouts.app')

@section('title', 'Detalhes do Cliente')

@section('content')

<div style="background: white; padding: 25px; border-radius: 8px; max-width: 750px;">

    <h2 style="margin-bottom: 25px;">Detalhes do cliente</h2>

    <div style="margin-bottom: 15px;">
        <strong>Código:</strong>
        {{ $cliente->id_cliente }}
    </div>

    <div style="margin-bottom: 15px;">
        <strong>Nome:</strong>
        {{ $cliente->nome }}
    </div>

    <div style="margin-bottom: 15px;">
        <strong>CPF:</strong>
        {{ $cliente->cpf }}
    </div>

    <div style="margin-bottom: 15px;">
        <strong>Telefone:</strong>
        {{ $cliente->telefone ?: 'Não informado' }}
    </div>

    <div style="margin-bottom: 15px;">
        <strong>E-mail:</strong>
        {{ $cliente->email ?: 'Não informado' }}
    </div>

    <div style="margin-bottom: 15px;">
        <strong>Situação:</strong>
        {{ $cliente->ativo ? 'Ativo' : 'Inativo' }}
    </div>

    <div style="display: flex; gap: 10px; margin-top: 25px;">
        <a href="{{ route('clientes.edit', $cliente) }}"
           style="background: #2563eb; color: white; padding: 12px 20px; border-radius: 6px; text-decoration: none;">
            Editar cliente
        </a>

        <a href="{{ route('clientes.index') }}"
           style="background: #e5e7eb; color: #333; padding: 12px 20px; border-radius: 6px; text-decoration: none;">
            Voltar
        </a>
    </div>

</div>

@endsection
