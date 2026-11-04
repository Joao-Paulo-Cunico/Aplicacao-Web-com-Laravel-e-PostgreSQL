@extends('layouts.app')

@section('title', 'Ordem de serviço #' . $ordem->id_ordem_servico)

@section('content')
    <div class="card">
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        <p><strong>Status:</strong> {{ $ordem->status }}</p>
        <p><strong>Veículo:</strong> {{ $placa ?? '—' }}</p>
        <p><strong>Mecânico:</strong> {{ $ordem->mecanico->nome ?? '—' }}</p>
        <p><strong>Abertura:</strong> {{ $ordem->data_abertura->format('d/m/Y') }}</p>
        <p><strong>Fechamento:</strong> {{ $ordem->data_fechamento?->format('d/m/Y') ?? '—' }}</p>
        <p><strong>Quilometragem de entrada:</strong> {{ number_format($ordem->quilometragem_entrada, 0, ',', '.') }} km</p>
        <p><strong>Observações:</strong> {{ $ordem->observacoes ?: '—' }}</p>

        <h3>Serviços</h3>
        <table>
            <thead>
                <tr><th>Serviço</th><th>Qtd</th><th>Valor unit.</th><th>Desconto</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @forelse ($ordem->servicos as $servico)
                    @php
                        $p = $servico->pivot;
                        $subtotal = $p->quantidade * $p->valor_unitario - ($p->desconto ?? 0);
                    @endphp
                    <tr>
                        <td>{{ $servico->nome }}</td>
                        <td>{{ $p->quantidade }}</td>
                        <td>R$ {{ number_format($p->valor_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($p->desconto ?? 0, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Nenhum serviço nesta OS.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h3>Peças</h3>
        <table>
            <thead>
                <tr><th>Peça</th><th>Qtd</th><th>Valor unit.</th><th>Desconto</th><th>Subtotal</th></tr>
            </thead>
            <tbody>
                @forelse ($pecas as $peca)
                    @php $subtotal = $peca->quantidade * $peca->valor_unitario - ($peca->desconto ?? 0); @endphp
                    <tr>
                        <td>{{ $peca->nome }}</td>
                        <td>{{ $peca->quantidade }}</td>
                        <td>R$ {{ number_format($peca->valor_unitario, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($peca->desconto ?? 0, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Nenhuma peça nesta OS.</td></tr>
                @endforelse
            </tbody>
        </table>

        <h3>Totais</h3>
        <p>Mão de obra: R$ {{ number_format($totais['mao_obra'], 2, ',', '.') }}</p>
        <p>Serviços: R$ {{ number_format($totais['servicos'], 2, ',', '.') }}</p>
        <p>Peças: R$ {{ number_format($totais['pecas'], 2, ',', '.') }}</p>
        <p><strong>Total: R$ {{ number_format($totais['total'], 2, ',', '.') }}</strong></p>

        <a href="{{ route('ordens-servico.index') }}">Voltar</a>
    </div>
@endsection