
@extends('layouts.app')

@section('content')
    <h1>Editar Veículo</h1>

    <p>Atualize as informações do veículo cadastrado.</p>

    <hr>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 20px;">
            <strong>Verifique os erros:</strong>

            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('veiculos.update', $veiculo) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 15px;">
            <label for="cliente_id">Proprietário:</label>

            <select name="cliente_id" id="cliente_id" required>
                <option value="">Selecione um cliente</option>

                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id_cliente }}"
                        {{ old('cliente_id', $veiculo->cliente_id) == $cliente->id_cliente ? 'selected' : '' }}>
                        {{ $cliente->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="modelo_id">Modelo do Veículo:</label>

            <select name="modelo_id" id="modelo_id" required>
                <option value="">Selecione um modelo</option>

                @foreach ($modelos as $modelo)
                    <option value="{{ $modelo->id_modelo }}"
                        {{ old('modelo_id', $veiculo->modelo_id) == $modelo->id_modelo ? 'selected' : '' }}>
                        {{ $modelo->marca?->nome ?? 'Sem marca' }}
                        -
                        {{ $modelo->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="placa">Placa:</label>

            <input
                type="text"
                name="placa"
                id="placa"
                value="{{ old('placa', $veiculo->placa) }}"
                maxlength="7"
                placeholder="ABC1D23"
                style="text-transform: uppercase;"
                required
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="ano">Ano do Veículo:</label>

            <input
                type="date"
                name="ano"
                id="ano"
                value="{{ old('ano', $veiculo->ano?->format('Y-m-d')) }}"
                required
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="cor">Cor:</label>

            <input
                type="text"
                name="cor"
                id="cor"
                value="{{ old('cor', $veiculo->cor) }}"
                maxlength="20"
                placeholder="Ex: Prata"
                required
            >
        </div>

        <div style="margin-bottom: 15px;">
            <label for="quilometragem">Quilometragem:</label>

            <input
                type="number"
                name="quilometragem"
                id="quilometragem"
                value="{{ old('quilometragem', $veiculo->quilometragem) }}"
                min="0"
                step="1"
                required
            >
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">
                Salvar Alterações
            </button>

            <a href="{{ route('veiculos.index') }}"
               style="margin-left: 15px;">
                Cancelar
            </a>
        </div>
    </form>
@endsection
