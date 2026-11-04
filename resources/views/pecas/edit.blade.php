@extends('layouts.app')

@section('title', 'Editar Peça')

@section('content')

<div class="card" style="max-width:750px;">

    <h3 style="margin:0 0 20px;">Editar Peça #{{ $peca->id_peca }}</h3>

    <form action="{{ route('pecas.update', $peca) }}" method="POST">
        @csrf
        @method('PUT')

        <label style="display:block; margin-top:15px; font-weight:bold;">Nome da Peça</label>
        <input style="display:block; width:100%; margin-top:5px;" type="text" name="nome"
               value="{{ old('nome', $peca->nome) }}"
               maxlength="150" required>
        @error('nome')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Descrição</label>
        <textarea style="display:block; width:100%; margin-top:5px;" name="descricao" maxlength="500" required>{{ old('descricao', $peca->descricao) }}</textarea>
        @error('descricao')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Preço de Custo (R$)</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number" name="preco_custo"
               step="0.01" min="0"
               value="{{ old('preco_custo', $peca->preco_custo) }}"
               required>
        @error('preco_custo')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Preço de Venda (R$)</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number" name="preco_venda"
               step="0.01" min="0"
               value="{{ old('preco_venda', $peca->preco_venda) }}"
               required>
        @error('preco_venda')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Quantidade em Estoque</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number" name="estoque"
               min="0" step="1"
               value="{{ old('estoque', $peca->estoque) }}"
               required>
        @error('estoque')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <label style="display:block; margin-top:15px; font-weight:bold;">Situação da Peça</label>
        <select style="display:block; width:100%; margin-top:5px;" name="ativo" required>
            <option value="1" @selected(old('ativo', (int) $peca->ativo) == '1')>
                Ativa
            </option>
            <option value="0" @selected(old('ativo', (int) $peca->ativo) == '0')>
                Inativa
            </option>
        </select>
        @error('ativo')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:20px;">
            <button type="submit" style="padding:12px 20px; border-radius:6px;">
                Salvar Alterações
            </button>

            <a href="{{ route('pecas.index') }}"
               style="background:#e5e7eb; color:#333; padding:12px 20px; border-radius:6px; text-decoration:none; font-size:14px;">
                Voltar
            </a>
        </div>
    </form>

</div>

<section class="card" aria-labelledby="repor-estoque-titulo"
         style="max-width:750px; margin-top:25px; border-top:4px solid #ffde00;">

    <h3 id="repor-estoque-titulo" style="margin:0 0 15px;">Repor Estoque</h3>

    <p style="background:#f2f5f8; padding:15px; border-radius:6px; margin:0 0 20px;">
        Estoque atual: <strong style="color:#064f80;">{{ $peca->estoque }}</strong>
    </p>

    @if (session('success'))
        <div role="status" style="background:#e8f5e9; color:#256029; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pecas.repor-estoque', $peca) }}" method="POST">
        @csrf

        <label for="quantidade" style="display:block; font-weight:bold;">Quantidade a repor</label>
        <input type="number" id="quantidade" name="quantidade"
               min="1" max="10000" step="1"
               value="{{ old('quantidade', 1) }}" required
               style="display:block; width:100%; margin-top:5px;">
        @error('quantidade')
            <span role="alert" style="display:block; color:#b71c1c; font-size:13px; margin-top:5px;">{{ $message }}</span>
        @enderror

        <div style="margin-top:20px;">
            <button type="submit" style="padding:12px 20px; border-radius:6px;">
                + Repor Estoque
            </button>
        </div>
    </form>

</section>

@endsection
