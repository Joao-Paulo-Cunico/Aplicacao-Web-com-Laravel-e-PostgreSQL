
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Pagamento - Oficina Mecânica</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #00000015;
        }

        h1 {
            color: #263445;
        }

        p {
            color: #666;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin-top: 20px;
            background: #263445;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .voltar {
            background: #777;
        }

        .erro {
            color: #dc3545;
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Editar Pagamento #{{ $pagamento->id_pagamento }}</h1>

    <p>Altere as informações do pagamento abaixo.</p>

    <form action="{{ route('pagamentos.update', $pagamento) }}" method="POST">

        @csrf
        @method('PUT')

        <!-- Ordem de Servico -->
        <label>Número da Ordem de Serviço</label>
        <input type="number"
               name="ordem_servico_id"
               min="1"
               value="{{ old('ordem_servico_id', $pagamento->ordem_servico_id) }}"
               required>

        @error('ordem_servico_id')
            <span class="erro">{{ $message }}</span>
        @enderror

        <!-- Data -->
        <label>Data do Pagamento</label>
        <input type="date"
               name="data_pagamento"
               value="{{ old('data_pagamento', $pagamento->data_pagamento->format('Y-m-d')) }}"
               required>

        @error('data_pagamento')
            <span class="erro">{{ $message }}</span>
        @enderror

        <!-- Valor -->
        <label>Valor do Pagamento (R$)</label>
        <input type="number"
               name="valor"
               step="0.01"
               min="0.01"
               max="99999999.99"
               value="{{ old('valor', $pagamento->valor) }}"
               required>

        @error('valor')
            <span class="erro">{{ $message }}</span>
        @enderror

        <!-- Forma de Pagamento -->
        <label>Forma de Pagamento</label>
        <select name="forma_pagamento" required>
            <option value="">Selecione</option>

            @foreach (['PIX', 'DINHEIRO', 'CREDITO', 'DEBITO', 'TRANSFERENCIA'] as $forma)
                <option value="{{ $forma }}"
                    @selected(old('forma_pagamento', $pagamento->forma_pagamento) === $forma)>
                    {{ $forma }}
                </option>
            @endforeach
        </select>

        @error('forma_pagamento')
            <span class="erro">{{ $message }}</span>
        @enderror

        <!-- Status -->
        <label>Status do Pagamento</label>
        <select name="status" required>
            <option value="">Selecione</option>

            @foreach (['PAGO', 'PENDENTE', 'CANCELADO'] as $status)
                <option value="{{ $status }}"
                    @selected(old('status', $pagamento->status) === $status)>
                    {{ $status }}
                </option>
            @endforeach
        </select>

        @error('status')
            <span class="erro">{{ $message }}</span>
        @enderror

        <!-- Botoes -->
        <button type="submit" class="btn">
            Salvar Alterações
        </button>

        <a href="{{ route('pagamentos.index') }}"
           class="btn voltar">
            Voltar
        </a>

    </form>

</div>

</body>
</html>
