
@extends('layouts.app')

@section('title', 'Serviços')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Gerenciamento de Serviços</h2>
            <p style="margin:6px 0 0; color:#64748b;">
                Consulte e gerencie os serviços da oficina.
            </p>
        </div>

        <a href="{{ route('servicos.create') }}"
           style="background:#064F80; color:white; padding:12px 18px; border-radius:7px; text-decoration:none; font-weight:600;">
            + Novo serviço
        </a>
    </div>

    @if (session('success'))
        <p style="color:#15803d; margin-bottom:15px;">
            {{ session('success') }}
        </p>
    @endif

    @if (session('error'))
        <p style="color:#b91c1c; margin-bottom:15px;">
            {{ session('error') }}
        </p>
    @endif

    <form method="GET"
          action="{{ route('servicos.index') }}"
          style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

        <input
            type="text"
            name="pesquisa"
            value="{{ $pesquisa ?? '' }}"
            placeholder="Pesquisar por nome ou descrição..."
            style="flex:1; min-width:200px; padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >

        <button type="submit"
                style="background:#FFDE00; color:#064F80; padding:11px 20px; border:0; border-radius:7px; font-weight:700; cursor:pointer;">
            Pesquisar
        </button>

        <a href="{{ route('servicos.index') }}"
           style="padding:11px 16px; border:1px solid #cbd5e1; border-radius:7px; text-decoration:none;">
            Limpar
        </a>

    </form>

    <div style="overflow-x:auto;">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço base</th>
                    <th>Tempo estimado</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($servicos as $servico)
                    <tr>
                        <td>{{ $servico->nome }}</td>
                        <td>{{ $servico->descricao ?: '—' }}</td>
                        <td>R$ {{ number_format($servico->preco_base, 2, ',', '.') }}</td>
                        <td>{{ $servico->tempo_estimado ?? '—' }}</td>
                        <td>
                            <span style="font-weight:600; color:{{ $servico->ativo ? '#15803d' : '#b91c1c' }};">
                                {{ $servico->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                                <a href="{{ route('servicos.show', $servico) }}">Ver</a>

                                <a href="{{ route('servicos.edit', $servico) }}">Editar</a>

                                <form action="{{ route('servicos.destroy', $servico) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Excluir este serviço?')">
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
                        <td colspan="6" style="text-align:center; padding:25px;">
                            Nenhum serviço encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $servicos->links() }}
    </div>

</div>

@endsection
