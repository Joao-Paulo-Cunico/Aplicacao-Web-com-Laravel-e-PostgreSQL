@extends('layouts.app')

@section('title', 'Ordem de serviço #' . $ordem->id_ordem_servico)

@section('content')
    <div class="card">
        @if (session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if (session('error'))
            <p>{{ session('error') }}</p>
        @endif

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        <p><strong>Status:</strong> {{ $ordem->status }}</p>
        <p><strong>Veículo:</strong> {{ $placa ?? '—' }}</p>
        <p><strong>Mecânico:</strong> {{ $ordem->mecanico->nome ?? '—' }}</p>
        <p><strong>Abertura:</strong> {{ $ordem->data_abertura->format('d/m/Y') }}</p>
        <p><strong>Fechamento:</strong> {{ $ordem->data_fechamento?->format('d/m/Y') ?? '—' }}</p>
        <p><strong>Quilometragem de entrada:</strong> {{ number_format($ordem->quilometragem_entrada, 0, ',', '.') }} km</p>
        <p><strong>Observações:</strong> {{ $ordem->observacoes ?: '—' }}</p>

        @if ($ordem->status === \App\Models\OrdemServico::STATUS_EM_ANDAMENTO)
            <form action="{{ route('ordens-servico.fechar', $ordem) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Deseja finalizar esta ordem de serviço?')">Finalizar OS</button>
            </form>
            <form action="{{ route('ordens-servico.cancelar', $ordem) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Deseja cancelar esta ordem de serviço? As peças serão devolvidas ao estoque.')">Cancelar OS</button>
            </form>
        @endif

        <h3>Serviços</h3>
        <table>
            <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Qtd</th>
                    <th>Valor unit.</th>
                    <th>Desconto</th>
                    <th>Subtotal</th>
                </tr>
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
                    <tr>
                        <td colspan="5">Nenhum serviço nesta OS.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($ordem->status === \App\Models\OrdemServico::STATUS_EM_ANDAMENTO)
            <form action="{{ route('ordens-servico.servicos.store', $ordem) }}" method="POST">
                @csrf
                <select name="servico_id" required>
                    <option value="">Selecione um serviço...</option>
                    @foreach ($servicosDisponiveis as $s)
                        <option value="{{ $s->id_servico }}" @selected(old('servico_id') == $s->id_servico)>
                            {{ $s->nome }} (R$ {{ number_format($s->preco_base, 2, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <input type="number" name="quantidade" value="{{ old('quantidade', 1) }}" min="1" required>
                <input type="number" name="desconto" value="{{ old('desconto', '0.00') }}" step="0.01" min="0">
                <button type="submit">Adicionar serviço</button>
            </form>
        @endif

        <h3>Peças</h3>
        <table>
            <thead>
                <tr>
                    <th>Peça</th>
                    <th>Qtd</th>
                    <th>Valor unit.</th>
                    <th>Desconto</th>
                    <th>Subtotal</th>
                </tr>
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
                    <tr>
                        <td colspan="5">Nenhuma peça nesta OS.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($ordem->status === \App\Models\OrdemServico::STATUS_EM_ANDAMENTO)
            <form action="{{ route('ordens-servico.pecas.store', $ordem) }}" method="POST">
                @csrf
                <select name="peca_id" required>
                    <option value="">Selecione uma peça...</option>
                    @foreach ($pecasDisponiveis as $p)
                        <option value="{{ $p->id_peca }}" @selected(old('peca_id') == $p->id_peca)>
                            {{ $p->nome }} (R$ {{ number_format($p->preco_venda, 2, ',', '.') }} | estoque:
                            {{ $p->estoque }})
                        </option>
                    @endforeach
                </select>
                <input type="number" name="quantidade" value="{{ old('quantidade', 1) }}" min="1" required>
                <input type="number" name="desconto" value="{{ old('desconto', '0.00') }}" step="0.01" min="0">
                <button type="submit">Adicionar peça</button>
            </form>
        @endif

        <h3>Totais</h3>
        <p>Mão de obra: R$ {{ number_format($totais['mao_obra'], 2, ',', '.') }}</p>
        <p>Serviços: R$ {{ number_format($totais['servicos'], 2, ',', '.') }}</p>
        <p>Peças: R$ {{ number_format($totais['pecas'], 2, ',', '.') }}</p>
        <p><strong>Total: R$ {{ number_format($totais['total'], 2, ',', '.') }}</strong></p>
        <p><strong>Total (função SQL):</strong>
            @if ($totalViaFuncao !== null)
                R$ {{ number_format($totalViaFuncao, 2, ',', '.') }}
            @else
                indisponível — execute os scripts SQL no Neon.
            @endif
        </p>

        <a href="{{ route('ordens-servico.index') }}">Voltar</a>
    </div>
@endsection
