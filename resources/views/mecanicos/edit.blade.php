@extends('layouts.app')

@section('title', 'Editar mecânico')

@section('content')
    <div class="card">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('mecanicos.update', $mecanico) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome', $mecanico->nome) }}" maxlength="300" required>
            </div>

            <div>
                <label for="cpf">CPF (somente números)</label>
                <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $mecanico->cpf) }}" maxlength="11" required>
            </div>

            <div>
                <label for="telefone">Telefone (somente números)</label>
                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $mecanico->telefone) }}" maxlength="11" required>
            </div>

            <div>
                <label for="especilidade">Especialidade</label>
                <input type="text" id="especilidade" name="especilidade" value="{{ old('especilidade', $mecanico->especilidade) }}" maxlength="150" required>
            </div>

            <div>
                <label for="data_admissao">Data de admissão</label>
                <input type="date" id="data_admissao" name="data_admissao"
                       value="{{ old('data_admissao', $mecanico->data_admissao->format('Y-m-d')) }}" required>
            </div>

            <div>
                <label>
                    <input type="checkbox" name="ativo" value="1" @checked($errors->any() ? old('ativo') : $mecanico->ativo)>
                    Ativo
                </label>
            </div>

            <button type="submit">Atualizar</button>
            <a href="{{ route('mecanicos.index') }}">Cancelar</a>
        </form>
    </div>
@endsection