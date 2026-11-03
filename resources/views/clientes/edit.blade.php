
@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<div style="background: white; padding: 25px; border-radius: 8px; max-width: 750px;">

    <h2 style="margin-bottom: 20px;">Editar cliente</h2>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            <strong>Verifique os campos:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome"
                   value="{{ old('nome', $cliente->nome) }}"
                   required maxlength="255"
                   style="display: block; width: 100%; box-sizing: border-box; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf"
                   value="{{ old('cpf', $cliente->cpf) }}"
                   required maxlength="14"
                   style="display: block; width: 100%; box-sizing: border-box; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone"
                   value="{{ old('telefone', $cliente->telefone) }}"
                   maxlength="20"
                   style="display: block; width: 100%; box-sizing: border-box; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $cliente->email) }}"
                   maxlength="255"
                   style="display: block; width: 100%; box-sizing: border-box; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px;">
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit"
                    style="background: #2563eb; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer;">
                Salvar alterações
            </button>

            <a href="{{ route('clientes.index') }}"
               style="background: #e5e7eb; color: #333; padding: 12px 20px; border-radius: 6px; text-decoration: none;">
                Cancelar
            </a>
        </div>

    </form>
</div>

@endsection
