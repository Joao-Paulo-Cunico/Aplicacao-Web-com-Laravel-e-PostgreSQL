@extends('layouts.app')

@section('title', 'Cadastrar Pagamento')

@section('content')

<div class="card" style="max-width:750px;">

    <h3 style="margin:0 0 20px;">Cadastrar Pagamento</h3>

    <p style="color:#64748b; margin:0 0 25px;">Preencha os dados do pagamento da ordem de serviço.</p>

    <form action="{{ route('pagamentos.store') }}" method="POST">

        @csrf

        <!-- Ordem de Serviço -->
        <label style="display:block; margin-top:15px; font-weight:bold;">Numero da Ordem de Serviço</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number"
               name="ordem_servico_id"
               min="1"
               value="{{ old('ordem_servico_id') }}"
               required>

        @error('ordem_servico_id')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <!-- Data -->
        <label style="display:block; margin-top:15px; font-weight:bold;">Data do Pagamento</label>
        <input style="display:block; width:100%; margin-top:5px;" type="date"
               name="data_pagamento"
               value="{{ old('data_pagamento', date('Y-m-d')) }}"
               required>

        @error('data_pagamento')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <!-- Valor -->
        <label style="display:block; margin-top:15px; font-weight:bold;">Valor do Pagamento (R$)</label>
        <input style="display:block; width:100%; margin-top:5px;" type="number"
               name="valor"
               step="0.01"
               min="0.01"
               max="99999999.99"
               value="{{ old('valor') }}"
               required>

        @error('valor')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <!-- Forma de Pagamento -->
        <label style="display:block; margin-top:15px; font-weight:bold;">Forma de Pagamento</label>
        <select style="display:block; width:100%; margin-top:5px;" name="forma_pagamento" required>
            <option value="">Selecione</option>

            @foreach (['PIX', 'DINHEIRO', 'CREDITO', 'DEBITO', 'TRANSFERENCIA'] as $forma)
                <option value="{{ $forma }}"
                    @selected(old('forma_pagamento') === $forma)>
                    {{ $forma }}
                </option>
            @endforeach
        </select>

        @error('forma_pagamento')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <!-- Status -->
        <label style="display:block; margin-top:15px; font-weight:bold;">Status do Pagamento</label>
        <select style="display:block; width:100%; margin-top:5px;" name="status" required>
            <option value="">Selecione</option>

            @foreach (['PAGO', 'PENDENTE', 'CANCELADO'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status') === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        @error('status')
            <span style="color:#b71c1c; font-size:13px;">{{ $message }}</span>
        @enderror

        <!-- Botoes -->
        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:20px;">
            <button type="submit" style="padding:12px 20px; border-radius:6px;">
                Salvar Pagamento
            </button>

            <a href="{{ route('pagamentos.index') }}"
               style="background:#e5e7eb; color:#333; padding:12px 20px; border-radius:6px; text-decoration:none; font-size:14px;">
                Voltar
            </a>
        </div>

    </form>

</div>

@endsection
