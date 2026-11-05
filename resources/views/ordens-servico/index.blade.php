
@extends('layouts.app')

@section('title', 'Ordens de Serviço')

@section('content')

<div class="card">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">
                Gerenciamento de Ordens de Serviço
            </h2>

            <p style="margin:6px 0 0; color:#64748b;">
                Consulte e acompanhe os atendimentos da oficina.
            </p>
        </div>

        <a href="{{ route('ordens-servico.create') }}"
           style="background:#064F80; color:white; padding:12px 18px; border-radius:7px; text-decoration:none; font-weight:600;">
            + Nova ordem de serviço
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
          action="{{ route('ordens-servico.index') }}"
          style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px;">

        <input
            type="text"
            name="pesquisa"
            value="{{ $pesquisa ?? '' }}"
            placeholder="Pesquisar por OS, placa ou mecânico..."
            style="flex:1; min-width:200px; padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >

        <select
            name="status"
            style="padding:11px; border:1px solid #cbd5e1; border-radius:7px;"
        >
            <option value="">Todos os status</option>

            @foreach (['Em andamento', 'Finalizada', 'Cancelada'] as $opcao)
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
            href="{{ route('ordens-servico.index') }}"
            style="padding:11px 16px; border:1px solid #cbd5e1; border-radius:7px; text-decoration:none;"
        >
            Limpar
        </a>

    </form>

    <div style="overflow-x:auto;">

        <table style="width:100%;">
            <thead>
                <tr>
                    <th>Nº OS</th>
                    <th>Abertura</th>
                    <th>Veículo</th>
                    <th>Mecânico</th>
                    <th>Status</th>
                    <th>Mão de obra</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($ordens as $ordem)

                    <tr>
                        <td>
                            #{{ $ordem->id_ordem_servico }}
                        </td>

                        <td>
                            {{ $ordem->data_abertura
                                ? $ordem->data_abertura->format('d/m/Y')
                                : '—' }}
                        </td>

                        <td>
                            {{ $placas[$ordem->veiculo_id] ?? '—' }}
                        </td>

                        <td>
                            {{ $ordem->mecanico->nome ?? '—' }}
                        </td>

                        <td>
                            @php
                                $corStatus = match ($ordem->status) {
                                    'Finalizada' => '#15803d',
                                    'Cancelada' => '#b91c1c',
                                    default => '#b45309',
                                };
                            @endphp

                            <span style="font-weight:600; color:{{ $corStatus }};">
                                {{ $ordem->status }}
                            </span>
                        </td>

                        <td>
                            R$ {{ number_format(
                                (float) $ordem->valor_mao_obra,
                                2,
                                ',',
                                '.'
                            ) }}
                        </td>

                        <td>
                            <a href="{{ route('ordens-servico.show', $ordem) }}">
                                Ver detalhes
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:25px;">
                            Nenhuma ordem de serviço encontrada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div style="margin-top:20px;">
        {{ $ordens->links() }}
    </div>

</div>

@endsection
