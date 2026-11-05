@extends('layouts.app')

@section('title', 'Editar ordem de serviço #' . $ordem->id_ordem_servico)

@section('content')
    <div class="card">
        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        <p><strong>Veículo:</strong> {{ $placa ?? '—' }} (não pode ser alterado)</p>
        <p><strong>Abertura:</strong> {{ $ordem->data_abertura->format('d/m/Y') }} (não pode ser alterada)</p>

        <form action="{{ route('ordens-servico.update', $ordem) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <label for="mecanico_id">Mecânico</label>
                <select id="mecanico_id" name="mecanico_id" required>
                    @foreach ($mecanicos as $mecanico)
                        <option value="{{ $mecanico->id_mecanico }}"
                            @selected(old('mecanico_id', $ordem->mecanico_id) == $mecanico->id_mecanico)>
                            {{ $mecanico->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="quilometragem_entrada">Quilometragem de entrada</label>
                <input type="number" id="quilometragem_entrada" name="quilometragem_entrada"
                       value="{{ old('quilometragem_entrada', $ordem->quilometragem_entrada) }}" min="0" required>
            </div>

            <div>
                <label for="valor_mao_obra">Mão de obra (R$)</label>
                <input type="number" id="valor_mao_obra" name="valor_mao_obra"
                       value="{{ old('valor_mao_obra', $ordem->valor_mao_obra) }}" step="0.01" min="0" required>
            </div>

            <div>
                <label for="observacoes">Observações</label>
                <textarea id="observacoes" name="observacoes" maxlength="300">{{ old('observacoes', $ordem->observacoes) }}</textarea>
            </div>

            <button type="submit">Atualizar</button>
            <a href="{{ route('ordens-servico.show', $ordem) }}">Cancelar</a>
        </form>
    </div>
@endsection