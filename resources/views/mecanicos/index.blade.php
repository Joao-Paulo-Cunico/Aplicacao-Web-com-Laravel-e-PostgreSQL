
@extends('layouts.app')

@section('title', 'Mecânicos')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Gerenciamento de Mecânicos</h2>
            <p style="margin:6px 0 0; color:#64748b;">
                Consulte e gerencie os profissionais da oficina.
            </p>
        </div>

        <a href="{{ route('mecanicos.create') }}"
           style="background:#064F80; color:white; padding:12px 18px; border-radius:7px; text-decoration:none; font-weight:600;">
            + Novo mecânico
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
          action="{{ route('mecanicos.index') }}"
          style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

        <input
            type="text"
            name="pesquisa"
            value="{{ $pesquisa ?? '' }}"
            placeholder="Pesquisar por nome, CPF, telefone ou especialidade..."
            style="flex:1; min-width:200px; padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >

        <button type="submit"
                style="background:#FFDE00; color:#064F80; padding:11px 20px; border:0; border-radius:7px; font-weight:700; cursor:pointer;">
            Pesquisar
        </button>

        <a href="{{ route('mecanicos.index') }}"
           style="padding:11px 16px; border:1px solid #cbd5e1; border-radius:7px; text-decoration:none;">
            Limpar
        </a>

    </form>

    <div style="overflow-x:auto;">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th>Especialidade</th>
                    <th>Admissão</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($mecanicos as $mecanico)
                    <tr>
                        <td>{{ $mecanico->nome }}</td>
                        <td>{{ $mecanico->cpf ?: '—' }}</td>
                        <td>{{ $mecanico->telefone ?: '—' }}</td>
                        <td>{{ $mecanico->especilidade ?: '—' }}</td>
                        <td>
                            {{ $mecanico->data_admissao
                                ? $mecanico->data_admissao->format('d/m/Y')
                                : '—' }}
                        </td>
                        <td>
                            <span style="font-weight:600; color:{{ $mecanico->ativo ? '#15803d' : '#b91c1c' }};">
                                {{ $mecanico->ativo ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

                                <a href="{{ route('mecanicos.show', $mecanico) }}">
                                    Ver
                                </a>

                                <a href="{{ route('mecanicos.edit', $mecanico) }}">
                                    Editar
                                </a>

                                <form action="{{ route('mecanicos.destroy', $mecanico) }}"
                                      method="POST"
                                      style="display:inline;"
                                      onsubmit="return confirm('Excluir este mecânico?')">

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
                            Nenhum mecânico encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:20px;">
        {{ $mecanicos->links() }}
    </div>

</div>

@endsection
