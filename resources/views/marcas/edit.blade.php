
@extends('layouts.app')

@section('content')
    <h1>Editar Marca</h1>

    <p>Altere os dados da marca selecionada.</p>

    <form action="{{ route('marcas.update', $marca) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="nome">Nome da Marca:</label>

            <input
                type="text"
                id="nome"
                name="nome"
                value="{{ old('nome', $marca->nome) }}"
                maxlength="150"
                required
                style="padding: 10px; width: 100%; max-width: 400px;"
            >

            @error('nome')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" style="padding: 10px 20px;">
            Salvar Alterações
        </button>

        <a href="{{ route('marcas.index') }}"
           style="margin-left: 15px;">
            Voltar
        </a>
    </form>
@endsection
