@extends('layouts.app')

@section('title', 'Peças')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h3 style="margin:0 0 8px;">Gerenciamento de Peças</h3>
            <p style="margin:0; color:#64748b;">Controle de peças e estoque da Oficina Mecânica.</p>
        </div>

        <!-- Botão de cadastro -->
        <a href="{{ route('pecas.create') }}"
           style="background:#064f80; color:white; padding:11px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
            + Nova Peça
        </a>
    </div>

    <!-- Mensagem de sucesso -->
    @if (session('success'))
        <div style="background:#e8f5e9; color:#256029; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Mensagem de erro -->
    @if (session('error'))
        <div style="background:#ffebee; color:#b71c1c; padding:14px; border-radius:6px; margin-bottom:20px;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabela de peças -->
    <div style="overflow-x:auto;">

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

                        <td style="color:{{ $peca->estoque > 0 ? '#256029' : '#b71c1c' }}; font-weight:bold;">
                            {{ $peca->estoque }}
                        </td>

                        <td>
                            {{ $peca->ativo ? 'Ativa' : 'Inativa' }}
                        </td>

                        <td>
                            <div style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">

                                <!-- Botão Editar -->
                                <a href="{{ route('pecas.edit', $peca) }}"
                                   style="font-size:14px;">
                                    Editar
                                </a>

                                <!-- Botão Excluir -->
                                <form action="{{ route('pecas.destroy', $peca) }}"
                                      method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta peça?')"
                                      style="margin: 0;">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" style="background:#b91c1c; padding:7px 12px;">
                                        Excluir
                                    </button>

                                </form>

                            </div>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="7" style="text-align:center; padding:25px;">
                            Nenhuma peça cadastrada.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

    <!-- Paginação -->
    @php
        $pecas->withQueryString();
    @endphp

    <nav role="navigation" aria-label="Paginação de peças"
         style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:20px; font-size:14px;">

        @if ($pecas->onFirstPage())
            <span aria-disabled="true"
                  style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px; font-weight:bold; white-space:nowrap;">
                « Primeira
            </span>
            <span aria-disabled="true"
                  style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px; font-weight:bold; white-space:nowrap;">
                ‹ Anterior
            </span>
        @else
            <a href="{{ $pecas->url(1) }}"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                « Primeira
            </a>
            <a href="{{ $pecas->previousPageUrl() }}" rel="prev"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                ‹ Anterior
            </a>
        @endif

        <span aria-live="polite" style="color:#64748b;">
            Página {{ $pecas->currentPage() }}
            de {{ $pecas->lastPage() }}
        </span>

        @if ($pecas->hasMorePages())
            <a href="{{ $pecas->nextPageUrl() }}" rel="next"
               style="background:#064f80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none; font-weight:bold; white-space:nowrap;">
                Próxima ›
            </a>
            <a href="{{ $pecas->url($pecas->lastPage()) }}"
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
