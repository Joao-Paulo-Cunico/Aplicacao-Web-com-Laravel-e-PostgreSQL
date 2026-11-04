
@extends('layouts.app')

@section('title', 'Editar Veículo')

@section('content')

<div class="card">

    <div style="margin-bottom:25px;">
        <h2 style="margin:0 0 8px;">
            Editar Veículo
        </h2>

        <p style="color:#64748b; margin:0;">
            Atualize as informações do veículo cadastrado.
        </p>
    </div>

    @if ($errors->any())
        <div style="background:#ffebee; color:#b71c1c; padding:15px; border-radius:6px; margin-bottom:20px;">
            <strong>Verifique os erros:</strong>

            <ul style="margin-top:10px;">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div style="background:#e8f5e9; color:#256029; padding:15px; border-radius:6px; margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORMULÁRIO DE EDIÇÃO -->
    <form action="{{ route('veiculos.update', $veiculo) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px, 1fr)); gap:20px;">

            <!-- Proprietário -->
            <div>
                <label for="cliente_id"
                       style="display:block; margin-bottom:8px; font-weight:bold;">
                    Proprietário
                </label>

                <select name="cliente_id"
                        id="cliente_id"
                        style="width:100%;"
                        required>

                    <option value="">Selecione um cliente</option>

                    @foreach ($clientes as $cliente)
                        <option value="{{ $cliente->id_cliente }}"
                            {{ old('cliente_id', $veiculo->cliente_id) == $cliente->id_cliente ? 'selected' : '' }}>
                            {{ $cliente->nome }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Modelo -->
            <div>
                <label for="modelo_id"
                       style="display:block; margin-bottom:8px; font-weight:bold;">
                    Modelo do Veículo
                </label>

                <select name="modelo_id"
                        id="modelo_id"
                        style="width:100%;"
                        required>

                    <option value="">Selecione um modelo</option>

                    @foreach ($modelos as $modelo)
                        <option value="{{ $modelo->id_modelo }}"
                            {{ old('modelo_id', $veiculo->modelo_id) == $modelo->id_modelo ? 'selected' : '' }}>
                            {{ $modelo->marca?->nome ?? 'Sem marca' }} - {{ $modelo->nome }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- Placa -->
            <div>
                <label for="placa"
                       style="display:block; margin-bottom:8px; font-weight:bold;">
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

            <!-- Ano -->
            <div>
                <label for="ano"
                       style="display:block; margin-bottom:8px; font-weight:bold;">
                    Ano do Veículo
                </label>

                <input type="date"
                       name="ano"
                       id="ano"
                       value="{{ old('ano', $veiculo->ano?->format('Y-m-d')) }}"
                       style="width:100%;"
                       required>
            </div>

            <!-- Cor -->
            <div>
                <label for="cor"
                       style="display:block; margin-bottom:8px; font-weight:bold;">
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

            <!-- Quilometragem -->
            <div>
                <label for="quilometragem"
                       style="display:block; margin-bottom:8px; font-weight:bold;">
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

        <!-- Botões de edição -->
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

    <!-- ÁREA DE DESATIVAÇÃO -->
    <div style="margin-top:35px; padding-top:25px; border-top:1px solid #e2e8f0;">

        @if ($veiculo->ativo)

            <div style="background:#fff7f7; border:1px solid #fecaca; padding:20px; border-radius:8px;">

                <h3 style="color:#991b1b; margin:0 0 10px;">
                    Desativar Veículo
                </h3>

                <p style="color:#64748b; margin-bottom:18px; line-height:1.6;">
                    Ao desativar este veículo, ele será marcado
                    como inativo no sistema, mas seu cadastro
                    e histórico serão preservados.
                </p>

                <form action="{{ route('veiculos.destroy', $veiculo) }}"
                      method="POST"
                      onsubmit="return confirm('Tem certeza que deseja desativar este veículo?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            style="background:#b91c1c; color:white; padding:11px 18px; border:none; border-radius:6px; cursor:pointer;">
                        Desativar Veículo
                    </button>

                </form>

            </div>

        @else

            <div style="background:#f1f5f9; padding:18px; border-radius:8px;">
                <strong style="color:#475569;">
                    Este veículo está inativo.
                </strong>

                <p style="color:#64748b; margin:8px 0 0;">
                    O cadastro foi preservado no sistema.
                </p>
            </div>

        @endif

    </div>

</div>

@endsection
