@extends('layouts.app')

@section('title', 'Novo serviço')

@section('content')
    <div class="card">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('servicos.store') }}" method="POST">
            @csrf

            <div>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome') }}" maxlength="100" required>
            </div>

            <div>
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" maxlength="500" required>{{ old('descricao') }}</textarea>
            </div>

            <div>
                <label for="preco_base">Preço base (R$)</label>
                <input type="number" id="preco_base" name="preco_base" value="{{ old('preco_base') }}" step="0.01" min="0" required>
            </div>

            <div>
                <label for="tempo_estimado">Tempo estimado</label>
                <input type="number" id="tempo_estimado" name="tempo_estimado" value="{{ old('tempo_estimado') }}" min="1" required>
            </div>

            <div>
                <label>
                    <input type="checkbox" name="ativo" value="1" @checked($errors->any() ? old('ativo') : true)>
                    Ativo
                </label>
            </div>

            <button type="submit">Salvar</button>
            <a href="{{ route('servicos.index') }}">Cancelar</a>
        </form>
    </div>
@endsection