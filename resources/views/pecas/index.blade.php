
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Peças - Oficina Mecânica</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px #00000015;
        }

        h1 {
            color: #263445;
            margin-bottom: 5px;
        }

        p {
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-novo {
            background: #263445;
            margin-top: 15px;
        }

        .btn-editar {
            background: #1769aa;
        }

        .btn-excluir {
            background: #dc3545;
        }

        .acoes {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #263445;
            color: white;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .em-estoque {
            color: green;
            font-weight: bold;
        }

        .sem-estoque {
            color: red;
            font-weight: bold;
        }

        .mensagem-sucesso {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .mensagem-erro {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .paginacao {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 20px;
        }

        .paginacao a {
            color: #1769aa;
            text-decoration: none;
            font-weight: bold;
        }

        .tabela-container {
            overflow-x: auto;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Gerenciamento de Peças</h1>

    <p>Controle de peças e estoque da Oficina Mecânica.</p>

    <!-- Mensagem de sucesso -->
    @if (session('success'))
        <div class="mensagem-sucesso">
            {{ session('success') }}
        </div>
    @endif

    <!-- Mensagem de erro -->
    @if (session('error'))
        <div class="mensagem-erro">
            {{ session('error') }}
        </div>
    @endif

    <!-- Botão de cadastro -->
    <a href="{{ route('pecas.create') }}" class="btn btn-novo">
        + Nova Peça
    </a>

    <!-- Tabela de peças -->
    <div class="tabela-container">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço de Custo</th>
                    <th>Preço de Venda</th>
                    <th>Estoque</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($pecas as $peca)

                    <tr>
                        <td>{{ $peca->id_peca }}</td>

                        <td>{{ $peca->nome }}</td>

                        <td>
                            R$ {{ number_format((float) $peca->preco_custo, 2, ',', '.') }}
                        </td>

                        <td>
                            R$ {{ number_format((float) $peca->preco_venda, 2, ',', '.') }}
                        </td>

                        <td class="{{ $peca->estoque > 0 ? 'em-estoque' : 'sem-estoque' }}">
                            {{ $peca->estoque }}
                        </td>

                        <td>
                            {{ $peca->ativo ? 'Ativa' : 'Inativa' }}
                        </td>

                        <td>
                            <div class="acoes">

                                <!-- Botão Editar -->
                                <a href="{{ route('pecas.edit', $peca) }}"
                                   class="btn btn-editar">
                                    Editar
                                </a>

                                <!-- Botão Excluir -->
                                <form action="{{ route('pecas.destroy', $peca) }}"
                                      method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta peça?')"
                                      style="margin: 0;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-excluir">
                                        Excluir
                                    </button>

                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" style="text-align: center;">
                            Nenhuma peça cadastrada.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

    <!-- Paginação -->
    <div class="paginacao">

        @if ($pecas->previousPageUrl())
            <a href="{{ $pecas->previousPageUrl() }}">
                Anterior
            </a>
        @endif

        <span>
            Página {{ $pecas->currentPage() }}
            de {{ $pecas->lastPage() }}
        </span>

        @if ($pecas->nextPageUrl())
            <a href="{{ $pecas->nextPageUrl() }}">
                Próxima
            </a>
        @endif

    </div>

</div>

</body>
</html>
