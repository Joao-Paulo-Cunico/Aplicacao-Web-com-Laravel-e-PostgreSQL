@extends('layouts.app')

@section('title', 'Pagamentos')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <h3 style="margin:0;">Pagamentos Cadastrados</h3>

        <a href="{{ route('pagamentos.create') }}"
           style="background:#064f80; color:white; padding:11px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
            + Novo Pagamento
        </a>
    </div>

    @if (session('success'))
        <div style="background:#e8f5e9; color:#256029; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#ffebee; color:#b71c1c; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x:auto;">

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

                        <td style="color:#256029; font-weight:bold;">
                            R$ {{ number_format((float) $pagamento->valor, 2, ',', '.') }}
                        </td>
                        
                        <td style="color:#256029; font-weight:bold;">
                            R$ {{ number_format((float) $pagamento->total_pago_os, 2, ',', '.') }}
                        </td>

                        <td>
                            {{ $pagamento->forma_pagamento }}
                        </td>

                        <td>
                            <span style="display:inline-block; background:#e2e8f0; color:#334155; padding:6px 10px; border-radius:5px; font-size:12px; font-weight:bold;">
                                {{ $pagamento->status }}
                            </span>
                        </td>
                        
                        <td>
                            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

                                <a href="{{ route('pagamentos.edit', $pagamento) }}"
                                   style="font-size:14px;">
                                    Editar
                                </a>

                                <form action="{{ route('pagamentos.destroy', $pagamento) }}"
                                      method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir este pagamento?')"
                                      style="margin:0;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="background:#b91c1c; padding:7px 12px;">
                                        Excluir
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="8" style="text-align:center; padding:25px;">
                            Nenhum pagamento cadastrado.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

    <!-- Paginação -->
    @php
        $pagamentos->withQueryString();
    @endphp

    <nav role="navigation" aria-label="Paginação de pagamentos"
         style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:20px; font-size:14px;">

        @if ($pagamentos->onFirstPage())
            <span aria-disabled="true"
                  style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px; font-weight:bold; white-space:nowrap;">
                « Primeira
            </span>
            <span aria-disabled="true"
                  style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px; font-weight:bold; white-space:nowrap;">
                ‹ Anterior
            </span>
        @else
            <a href="{{ $pagamentos->url(1) }}"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                « Primeira
            </a>
            <a href="{{ $pagamentos->previousPageUrl() }}" rel="prev"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                ‹ Anterior
            </a>
        @endif

        <span aria-live="polite" style="color:#64748b;">
            Página {{ $pagamentos->currentPage() }}
            de {{ $pagamentos->lastPage() }}
        </span>

        @if ($pagamentos->hasMorePages())
            <a href="{{ $pagamentos->nextPageUrl() }}" rel="next"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                Próxima ›
            </a>
            <a href="{{ $pagamentos->url($pagamentos->lastPage()) }}"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                Última »
            </a>
        @else
            <span aria-disabled="true"
                  style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px; font-weight:bold; white-space:nowrap;">
                Próxima ›
            </span>
            <span aria-disabled="true"
                  style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px; font-weight:bold; white-space:nowrap;">
                Última »
            </span>
        @endif

    </nav>

</div>

@endsection
