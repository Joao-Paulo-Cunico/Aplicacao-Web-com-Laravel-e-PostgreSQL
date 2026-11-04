@extends('layouts.app')

@section('title', 'Cadastrar Peça')

@section('content')

<div class="card" style="max-width:750px;">

    <h3 style="margin:0 0 20px;">Cadastrar Nova Peça</h3>

    <form action="{{ route('pecas.store') }}" method="POST">
        @csrf

        <label style="display:block; margin-top:15px; font-weight:bold;">Nome da Peça</label>
        <input style="display:block; width:100%; margin-top:5px;" type="text" name="nome"
               value="{{ old('nome') }}" required>
        @error('nome')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Descrição</label>
        <textarea style="display:block; width:100%; margin-top:5px;" name="descricao" required>{{ old('descricao') }}</textarea>
        @error('descricao')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Preço de Custo (R$)</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number" name="preco_custo"
               step="0.01" min="0"
               value="{{ old('preco_custo') }}" required>
        @error('preco_custo')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Preço de Venda (R$)</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number" name="preco_venda"
               step="0.01" min="0"
               value="{{ old('preco_venda') }}" required>
        @error('preco_venda')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Quantidade em Estoque</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number" name="estoque"
               min="0" step="1"
               value="{{ old('estoque', 0) }}" required>
        @error('estoque')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:20px;">
            <button type="submit" style="padding:12px 20px; border-radius:6px;">
                Salvar Peça
            </button>

            <a href="{{ route('pecas.index') }}"
               style="background:#e5e7eb; color:#333; padding:12px 20px; border-radius:6px; text-decoration:none; font-size:14px;">
                Voltar
            </a>
        </div>

    </form>
</div>

@endsection
