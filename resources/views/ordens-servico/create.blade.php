@extends('layouts.app')

@section('title', 'Abrir ordem de serviço')

@section('content')
    <div class="card">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('ordens-servico.store') }}" method="POST">
            @csrf

            <div>
                <label for="veiculo_id">Veículo</label>
                <select id="veiculo_id" name="veiculo_id" required>
                    <option value="">Selecione...</option>
                    @foreach ($veiculos as $veiculo)
                        <option value="{{ $veiculo->id_veiculo }}" @selected(old('veiculo_id') == $veiculo->id_veiculo)>
                            {{ $veiculo->placa }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="mecanico_id">Mecânico</label>
                <select id="mecanico_id" name="mecanico_id" required>
                    <option value="">Selecione...</option>
                    @foreach ($mecanicos as $mecanico)
                        <option value="{{ $mecanico->id_mecanico }}" @selected(old('mecanico_id') == $mecanico->id_mecanico)>
                            {{ $mecanico->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="data_abertura">Data de abertura</label>
                <input type="date" id="data_abertura" name="data_abertura"
                       value="{{ old('data_abertura', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
            </div>

            <div>
                <label for="quilometragem_entrada">Quilometragem de entrada</label>
                <input type="number" id="quilometragem_entrada" name="quilometragem_entrada"
                       value="{{ old('quilometragem_entrada') }}" min="0" required>
            </div>

            <div>
                <label for="valor_mao_obra">Mão de obra (R$)</label>
                <input type="number" id="valor_mao_obra" name="valor_mao_obra"
                       value="{{ old('valor_mao_obra', '0.00') }}" step="0.01" min="0" required>
            </div>

            <div>
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" maxlength="300">{{ old('observacoes') }}</textarea>
            </div>

            <p>A OS será aberta com o status <strong>Em andamento</strong>.</p>

            <button type="submit">Abrir OS</button>
            <a href="{{ route('ordens-servico.index') }}">Cancelar</a>
        </form>
    </div>
@endsection