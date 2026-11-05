
@extends('layouts.app')

@section('title', 'Peças')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Gerenciamento de Peças</h2>
            <p style="margin:6px 0 0; color:#64748b;">
                Consulte e gerencie as peças e o estoque da oficina.
            </p>
        </div>

        <a href="{{ route('pecas.create') }}"
           style="background:#064F80; color:white; padding:12px 18px; border-radius:7px; text-decoration:none; font-weight:600;">
            + Nova peça
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

    <form method="GET"
          action="{{ route('pecas.index') }}"
          style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

        <input
            type="text"
            name="pesquisa"
            value="{{ $pesquisa ?? '' }}"
            placeholder="Pesquisar por nome ou descrição..."
            style="flex:1; min-width:200px; padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >

        <select name="situacao"
                style="padding:11px; border:1px solid #cbd5e1; border-radius:7px;">
            <option value="">Todas as situações</option>
            <option value="ativo" {{ ($situacao ?? '') === 'ativo' ? 'selected' : '' }}>
                Ativas
            </option>
            <option value="inativo" {{ ($situacao ?? '') === 'inativo' ? 'selected' : '' }}>
                Inativas
            </option>
        </select>

        <button type="submit"
                style="background:#FFDE00; color:#064F80; padding:11px 20px; border:0; border-radius:7px; font-weight:700; cursor:pointer;">
            Pesquisar
        </button>

        <a href="{{ route('pecas.index') }}"
           style="padding:11px 16px; border:1px solid #cbd5e1; border-radius:7px; text-decoration:none;">
            Limpar
        </a>

    </form>

    <div style="overflow-x:auto;">
        <table style="width:100%;">
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

                        <td style="font-weight:600; color:{{ $peca->estoque > 0 ? '#15803d' : '#b91c1c' }};">
                            {{ $peca->estoque }}
                        </td>

                        <td>
                            <span style="font-weight:600; color:{{ $peca->ativo ? '#15803d' : '#b91c1c' }};">
                                {{ $peca->ativo ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>

                        <td>
                            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

                                <a href="{{ route('pecas.edit', $peca) }}">
                                    Editar / Estoque
                                </a>

                                <form action="{{ route('pecas.destroy', $peca) }}"
                                      method="POST"
                                      style="display:inline; margin:0;"
                                      onsubmit="return confirm('Excluir esta peça?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            style="background:none; border:0; padding:0; color:#b91c1c; cursor:pointer;">
                                        Excluir
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:25px;">
                            Nenhuma peça encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <nav aria-label="Paginação de peças"
         style="display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:20px; font-size:14px;">

        @if ($pecas->onFirstPage())
            <span style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px;">
                Primeira
            </span>
            <span style="background:#e2e8f0; color:#64748b; padding:10px 14px; border-radius:6px;">
                Anterior
            </span>
        @else
            <a href="{{ $pecas->url(1) }}"
               style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">
                Primeira
            </a>
            <a href="{{ $pecas->previousPageUrl() }}"
               style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">
                Anterior
            </a>
        @endif

        <span style="color:#64748b;">
            Página {{ $pecas->currentPage() }} de {{ $pecas->lastPage() }}
        </span>

        @if ($pecas->hasMorePages())
            <a href="{{ $pecas->nextPageUrl() }}"
               style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">
                Próxima
            </a>
            <a href="{{ $pecas->url($pecas->lastPage()) }}"
               style="background:#064F80; color:white; padding:10px 14px; border-radius:6px; text-decoration:none;">
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
