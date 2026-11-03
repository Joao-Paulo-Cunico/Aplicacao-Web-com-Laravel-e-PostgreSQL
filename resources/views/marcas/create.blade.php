
@extends('layouts.app')

@section('content')
    <h1>Cadastrar Nova Marca</h1>

    <p>Preencha os dados para cadastrar uma marca de veículo.</p>

    <form action="{{ route('marcas.store') }}" method="POST">
        @csrf

        <div style="margin-bottom: 15px;">
            <label for="nome">Nome da Marca:</label>

            <input
                type="text"
                id="nome"
                name="nome"
                value="{{ old('nome') }}"
                maxlength="150"
                required
                style="padding: 10px; width: 100%; max-width: 400px;"
                placeholder="Ex.: Toyota"
            >

            @error('nome')
                <p style="color: red;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" style="padding: 10px 20px;">
            Salvar Marca
        </button>

        <a href="{{ route('marcas.index') }}"
           style="margin-left: 15px;">
            Voltar
        </a>
    </form>
@endsection
