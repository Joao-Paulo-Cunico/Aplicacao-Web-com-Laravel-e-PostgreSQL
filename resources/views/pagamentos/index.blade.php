
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pagamentos - Oficina Mecânica</title>

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

        .tabela-container {
            overflow-x: auto;
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

        .valor {
            color: #198754;
            font-weight: bold;
        }

        .status {
            display: inline-block;
            background: #e9ecef;
            padding: 6px 10px;
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
            font-weight: bold;
            text-decoration: none;
        }

        .paginacao a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">


        @if (session('success'))
            <div style="background:#d4edda; color:#155724; padding:12px; margin:15px 0; border-radius:5px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div style="background:#f8d7da; color:#721c24; padding:12px; margin:15px 0; border-radius:5px;">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('pagamentos.create') }}"
        style="display:inline-block; padding:12px 20px; background:#263445; color:white; text-decoration:none; border-radius:5px; margin-top:15px;">
            + Novo Pagamento
        </a>


    <div class="tabela-container">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ordem de Serviço</th>
                    <th>Data</th>
                    <th>Valor</th>
                    <th>Total Pago da OS</th>
                    <th>Forma de Pagamento</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($pagamentos as $pagamento)

                    <tr>
                        <td>{{ $pagamento->id_pagamento }}</td>

                        <td>
                            #{{ $pagamento->ordem_servico_id }}
                        </td>

                        <td>
                            {{ $pagamento->data_pagamento->format('d/m/Y') }}
                        </td>

                        <td class="valor">
                            R$ {{ number_format((float) $pagamento->valor, 2, ',', '.') }}
                        </td>
                        
                        <td class="valor">
                            R$ {{ number_format((float) $pagamento->total_pago_os, 2, ',', '.') }}
                        </td>

                        <td>
                            {{ $pagamento->forma_pagamento }}
                        </td>

                        <td>
                            <span class="status">
                                {{ $pagamento->status }}
                            </span>
                        </td>
                        
                        <td>
                            <div style="display:flex; gap:8px; align-items:center;">

                                <a href="{{ route('pagamentos.edit', $pagamento) }}"
                                style="background:#1769aa; color:white; padding:8px 12px; border-radius:5px; text-decoration:none;">
                                    Editar
                                </a>
                              <form action="{{ route('pagamentos.destroy', $pagamento) }}"
                                    method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este pagamento?')"
                                    style="margin:0;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="background:#dc3545; color:white; padding:8px 12px; border:0; border-radius:5px; cursor:pointer;">
                                        Excluir
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                       <td colspan="8" style="text-align:center;">
                            Nenhum pagamento cadastrado.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

    <div class="paginacao">

        @if ($pagamentos->previousPageUrl())
            <a href="{{ $pagamentos->previousPageUrl() }}">
                Anterior
            </a>
        @endif

        <span>
            Página {{ $pagamentos->currentPage() }}
            de {{ $pagamentos->lastPage() }}
        </span>

        @if ($pagamentos->nextPageUrl())
            <a href="{{ $pagamentos->nextPageUrl() }}">
                Próxima
            </a>
        @endif

    </div>

</div>

</body>
</html>
