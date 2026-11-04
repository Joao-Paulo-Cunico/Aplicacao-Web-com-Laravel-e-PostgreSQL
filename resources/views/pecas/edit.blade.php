
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Peça - Oficina Mecânica</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
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

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, textarea, select {
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
            color: red;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">

    <h1>Editar Peça #{{ $peca->id_peca }}</h1>

    <form action="{{ route('pecas.update', $peca) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nome da Peça</label>
        <input type="text" name="nome"
               value="{{ old('nome', $peca->nome) }}"
               maxlength="150" required>
        @error('nome')
            <span class="erro">{{ $message }}</span>
        @enderror

        <label>Descrição</label>
        <textarea name="descricao" maxlength="500" required>{{ old('descricao', $peca->descricao) }}</textarea>
        @error('descricao')
            <span class="erro">{{ $message }}</span>
        @enderror

        <label>Preço de Custo (R$)</label>
        <input type="number" name="preco_custo"
               step="0.01" min="0"
               value="{{ old('preco_custo', $peca->preco_custo) }}"
               required>
        @error('preco_custo')
            <span class="erro">{{ $message }}</span>
        @enderror

        <label>Preço de Venda (R$)</label>
        <input type="number" name="preco_venda"
               step="0.01" min="0"
               value="{{ old('preco_venda', $peca->preco_venda) }}"
               required>
        @error('preco_venda')
            <span class="erro">{{ $message }}</span>
        @enderror

        <label>Quantidade em Estoque</label>
        <input type="number" name="estoque"
               min="0" step="1"
               value="{{ old('estoque', $peca->estoque) }}"
               required>
        @error('estoque')
            <span class="erro">{{ $message }}</span>
        @enderror

        <label>Situação da Peça</label>
        <select name="ativo" required>
            <option value="1" @selected(old('ativo', (int) $peca->ativo) == '1')>
                Ativa
            </option>
            <option value="0" @selected(old('ativo', (int) $peca->ativo) == '0')>
                Inativa
            </option>
        </select>
        @error('ativo')
            <span class="erro">{{ $message }}</span>
        @enderror

        <button type="submit" class="btn">
            Salvar Alterações
        </button>

        <a href="{{ route('pecas.index') }}" class="btn voltar">
            Voltar
        </a>
    </form>

</div>

</body>
</html>
