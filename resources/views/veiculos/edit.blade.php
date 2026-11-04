
@extends('layouts.app')

@section('title', 'Editar Veículo')

@section('content')

<div class="card">

    <div style="margin-bottom:25px;">
        <h2 style="margin:0 0 8px;">Editar Veículo</h2>

        <p style="color:#64748b; margin:0;">
            Atualize as informações e gerencie a situação do veículo.
        </p>
    </div>

    @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:6px; margin-bottom:20px;">
            <strong>Verifique os campos:</strong>
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background:#dcfce7; color:#166534; padding:15px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="margin-bottom:25px;">
        <strong>Situação:</strong>

        @if ($veiculo->ativo)
            <span style="background:#dcfce7; color:#166534; padding:5px 12px; border-radius:20px; font-size:13px;">
                Ativo
            </span>
        @else
            <span style="background:#fee2e2; color:#991b1b; padding:5px 12px; border-radius:20px; font-size:13px;">
                Inativo
            </span>
        @endif
    </div>

    <!-- FORMULÁRIO DE EDIÇÃO -->
    <form action="{{ route('veiculos.update', $veiculo) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">

            <div>
                <label for="cliente_id" style="display:block; margin-bottom:8px; font-weight:bold;">
                    Proprietário
                </label>

                <select name="cliente_id" id="cliente_id" style="width:100%;" required>
                    <option value="">Selecione um cliente</option>

                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}"
                            {{ old('cliente_id', $veiculo->cliente_id) == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="modelo_id" style="display:block; margin-bottom:8px; font-weight:bold;">
                    Modelo do Veículo
                </label>

                <select name="modelo_id" id="modelo_id" style="width:100%;" required>
                    <option value="">Selecione um modelo</option>

                    @foreach ($modelos as $modelo)
                        <option value="{{ $modelo->id_modelo }}"
                            {{ old('modelo_id', $veiculo->modelo_id) == $modelo->id_modelo ? 'selected' : '' }}>
                            {{ $modelo->marca?->nome ?? 'Sem marca' }} - {{ $modelo->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="placa" style="display:block; margin-bottom:8px; font-weight:bold;">
                    Placa
                </label>

                <input type="text"
                       name="placa"
                       id="placa"
                       value="{{ old('placa', $veiculo->placa) }}"
                       maxlength="7"
                       placeholder="ABC1D23"
                       style="width:100%; text-transform:uppercase;"
                       required>
            </div>

            <div>
                <label for="ano" style="display:block; margin-bottom:8px; font-weight:bold;">
                    Ano do Veículo
                </label>

                <input type="date"
                       name="ano"
                       id="ano"
                       value="{{ old('ano', $veiculo->ano?->format('Y-m-d')) }}"
                       style="width:100%;"
                       required>
            </div>

            <div>
                <label for="cor" style="display:block; margin-bottom:8px; font-weight:bold;">
                    Cor
                </label>

                <input type="text"
                       name="cor"
                       id="cor"
                       value="{{ old('cor', $veiculo->cor) }}"
                       maxlength="20"
                       placeholder="Ex.: Prata"
                       style="width:100%;"
                       required>
            </div>

            <div>
                <label for="quilometragem" style="display:block; margin-bottom:8px; font-weight:bold;">
                    Quilometragem
                </label>

                <input type="number"
                       name="quilometragem"
                       id="quilometragem"
                       value="{{ old('quilometragem', $veiculo->quilometragem) }}"
                       min="0"
                       step="1"
                       style="width:100%;"
                       required>
            </div>

        </div>

        <div style="display:flex; align-items:center; gap:15px; flex-wrap:wrap; margin-top:30px;">

            <button type="submit"
                    style="background:#064f80; color:white; padding:12px 20px; border:none; border-radius:6px; cursor:pointer;">
                Salvar Alterações
            </button>

            <a href="{{ route('veiculos.index') }}"
               style="background:#e2e8f0; color:#334155; padding:12px 20px; border-radius:6px; text-decoration:none;">
                Cancelar
            </a>

        </div>
    </form>

    <!-- GERENCIAMENTO DO VEÍCULO -->
    <div style="margin-top:35px; padding-top:25px; border-top:1px solid #e2e8f0;">

        @if ($veiculo->ativo)

            <div style="background:#fff7f7; border:1px solid #fecaca; padding:20px; border-radius:8px;">

                <h3 style="color:#991b1b; margin:0 0 10px;">
                    Desativar Veículo
                </h3>

                <p style="color:#64748b; margin-bottom:18px;">
                    Ao desativar este veículo, seu cadastro ficará inativo,
                    mas os dados e o histórico serão preservados.
                </p>

                <form action="{{ route('veiculos.destroy', $veiculo) }}"
                      method="POST"
                      onsubmit="return confirm('Deseja realmente desativar este veículo?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            style="background:#b91c1c; color:white; padding:11px 18px; border:none; border-radius:6px; cursor:pointer;">
                        Desativar Veículo
                    </button>

                </form>
            </div>

        @else

            <div style="background:#f0fdf4; border:1px solid #bbf7d0; padding:20px; border-radius:8px;">

                <h3 style="color:#166534; margin:0 0 10px;">
                    Reativar Veículo
                </h3>

                <p style="color:#64748b; margin-bottom:18px;">
                    Este veículo está inativo. Você pode reativá-lo
                    sem precisar realizar um novo cadastro.
                </p>

                <form action="{{ route('veiculos.reativar', $veiculo) }}"
                      method="POST"
                      onsubmit="return confirm('Deseja reativar este veículo?');">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            style="background:#15803d; color:white; padding:11px 18px; border:none; border-radius:6px; cursor:pointer;">
                        Reativar Veículo
                    </button>

                </form>
            </div>

        @endif

    </div>

</div>

@endsection
