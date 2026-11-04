
@extends('layouts.app')

@section('content')
    <h1>Detalhes da Marca</h1>

    <p>Informações da marca cadastrada no sistema.</p>

    <hr>

    <div style="margin-bottom: 20px;">
        <p>
            <strong>ID da Marca:</strong>
            {{ $marca->id_marca }}
        </p>

        <p>
            <strong>Nome da Marca:</strong>
            {{ $marca->nome }}
        </p>
    </div>

    <a href="{{ route('marcas.edit', $marca) }}"
       style="margin-right: 15px;">
        Editar Marca
    </a>

    <a href="{{ route('marcas.index') }}">
        Voltar para Listagem
    </a>
@endsection
