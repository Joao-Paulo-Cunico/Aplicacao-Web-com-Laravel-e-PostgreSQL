
@extends('layouts.app')

@section('title', 'Pagamentos')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">
                Gerenciamento de Pagamentos
            </h2>

            <p style="margin:6px 0 0; color:#64748b;">
                Consulte e gerencie os pagamentos da oficina.
            </p>
        </div>

        <a href="{{ route('pagamentos.create') }}"
           style="background:#064F80; color:white; padding:12px 18px; border-radius:7px; text-decoration:none; font-weight:600;">
            + Novo pagamento
        </a>
    </div>

    @if (session('success'))
        <div style="background:#e8f5e9; color:#256029; padding:14px; border-radius:7px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#ffebee; color:#b71c1c; padding:14px; border-radius:7px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <form
        method="GET"
        action="{{ route('pagamentos.index') }}"
        style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;"
    >

        <input
            type="text"
            name="pesquisa"
            value="{{ $pesquisa ?? '' }}"
            placeholder="Pesquisar por nº do pagamento ou OS..."
            style="flex:1; min-width:200px; padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >

        <select
            name="status"
            style="padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >
            <option value="">Todos os status</option>

            @foreach (['PAGO', 'PENDENTE', 'CANCELADO'] as $opcao)
                <option
                    value="{{ $opcao }}"
                    @selected(($status ?? '') === $opcao)
                >
                    {{ $opcao }}
                </option>
            @endforeach
        </select>

        <button
            type="submit"
            style="background:#FFDE00; color:#064F80; padding:11px 20px; border:0; border-radius:7px; font-weight:700; cursor:pointer;"
        >
            Pesquisar
        </button>

        <a
            href="{{ route('pagamentos.index') }}"
            style="padding:11px 16px; border:1px solid #cbd5e1; border-radius:7px; text-decoration:none;"
        >
            Limpar
        </a>

    </form>

    <div style="overflow-x:auto;">

        <table style="width:100%;">
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
                        <td>
                            {{ $pagamento->id_pagamento }}
                        </td>

                        <td>
                            #{{ $pagamento->ordem_servico_id }}
                        </td>

                        <td>
                            {{ $pagamento->data_pagamento
                                ? $pagamento->data_pagamento->format('d/m/Y')
                                : '—' }}
                        </td>

                        <td style="font-weight:600; color:#15803d;">
                            R$ {{ number_format(
                                (float) $pagamento->valor,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td style="font-weight:600; color:#15803d;">
                            R$ {{ number_format(
                                (float) $pagamento->total_pago_os,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>
                            {{ $pagamento->forma_pagamento }}
                        </td>

                        <td>
                            @php
                                $corStatus = match ($pagamento->status) {
                                    'PAGO' => '#15803d',
                                    'CANCELADO' => '#b91c1c',
                                    default => '#b45309',
                                };
                            @endphp

                            <span style="font-weight:600; color:{{ $corStatus }};">
                                {{ $pagamento->status }}
                            </span>
                        </td>

                        <td>
                            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

                                <a href="{{ route('pagamentos.edit', $pagamento) }}">
                                    Editar
                                </a>

                                <form
                                    action="{{ route('pagamentos.destroy', $pagamento) }}"
                                    method="POST"
                                    style="display:inline; margin:0;"
                                    onsubmit="return confirm('Excluir este pagamento?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        style="background:none; border:0; padding:0; color:#b91c1c; cursor:pointer;"
                                    >
                                        Excluir
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td
                            colspan="8"
                            style="text-align:center; padding:25px;"
                        >
                            Nenhum pagamento encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <nav
        aria-label="Paginação de pagamentos"
        style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:20px; font-size:14px;"
    >

        @if ($pagamentos->onFirstPage())

            <span style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px;">
                Primeira
            </span>

            <span style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px;">
                Anterior
            </span>

        @else

            <a
                href="{{ $pagamentos->url(1) }}"
                style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;"
            >
                Primeira
            </a>

            <a
                href="{{ $pagamentos->previousPageUrl() }}"
                style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;"
            >
                Anterior
            </a>

        @endif

        <span style="color:#64748b;">
            Página {{ $pagamentos->currentPage() }}
            de {{ $pagamentos->lastPage() }}
        </span>

        @if ($pagamentos->hasMorePages())

            <a
                href="{{ $pagamentos->nextPageUrl() }}"
                style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;"
            >
                Próxima
            </a>

            <a
                href="{{ $pagamentos->url($pagamentos->lastPage()) }}"
                style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;"
            >
                Última
            </a>

        @else

            <span style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px;">
                Próxima
            </span>

            <span style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px;">
                Última
            </span>

        @endif

    </nav>

</div>

@endsection
