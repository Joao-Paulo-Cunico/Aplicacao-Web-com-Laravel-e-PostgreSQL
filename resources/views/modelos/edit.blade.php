
@extends('layouts.app')

@section('content')
    <h1>Editar Modelo</h1>

    <p>Altere os dados do modelo selecionado.</p>

    <form action="{{ route('modelos.update', $modelo) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="marca_id">Marca:</label>

            <select
                id="marca_id"
                name="marca_id"
                required
                style="padding: 10px; width: 100%; max-width: 400px;"
            >
                <option value="">Selecione uma marca</option>

                @foreach ($marcas as $marca)
                    <option
                        value="{{ $marca->id_marca }}"
                        {{ old('marca_id', $modelo->marca_id) == $marca->id_marca ? 'selected' : '' }}
                    >
                        {{ $marca->nome }}
                    </option>
                @endforeach
            </select>

            @error('marca_id')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="nome">Nome do Modelo:</label>

            <input
                type="text"
                id="nome"
                name="nome"
                value="{{ old('nome', $modelo->nome) }}"
                maxlength="75"
                required
                style="padding: 10px; width: 100%; max-width: 400px;"
            >

            @error('nome')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ano_inicio">Data de Início de Fabricação:</label>

            <input
                type="date"
                id="ano_inicio"
                name="ano_inicio"
                value="{{ old('ano_inicio', $modelo->ano_inicio?->format('Y-m-d')) }}"
                required
                style="padding: 10px;"
            >

            @error('ano_inicio')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ano_fim">Data de Fim de Fabricação (opcional):</label>

            <input
                type="date"
                id="ano_fim"
                name="ano_fim"
                value="{{ old('ano_fim', $modelo->ano_fim?->format('Y-m-d')) }}"
                style="padding: 10px;"
            >

            @error('ano_fim')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="padding: 10px 20px;">
                Salvar Alterações
            </button>

            <a href="{{ route('modelos.index') }}"
               style="margin-left: 15px;">
                Voltar
            </a>
        </div>
    </form>
@endsection
