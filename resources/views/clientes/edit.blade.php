
@extends('layouts.app')

@section('title', 'Editar Cliente')

@section('content')

<div class="card" style="max-width:750px;">

    <h2 style="margin-bottom:8px;">Editar Cliente</h2>

    <p style="color:#64748b; margin-bottom:25px;">
        Atualize as informações e gerencie a situação do cliente.
    </p>

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

        @if ($cliente->ativo)
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
    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom:15px;">
            <label for="nome">Nome completo</label>
            <input type="text" id="nome" name="nome"
                   value="{{ old('nome', $cliente->nome) }}"
                   required maxlength="255"
                   style="display:block; width:100%; box-sizing:border-box; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:15px;">
            <label for="cpf">CPF</label>
            <input type="text" id="cpf" name="cpf"
                   value="{{ old('cpf', $cliente->cpf) }}"
                   required maxlength="14"
                   style="display:block; width:100%; box-sizing:border-box; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:15px;">
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone"
                   value="{{ old('telefone', $cliente->telefone) }}"
                   maxlength="20"
                   style="display:block; width:100%; box-sizing:border-box; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="margin-bottom:20px;">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $cliente->email) }}"
                   maxlength="255"
                   style="display:block; width:100%; box-sizing:border-box; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px;">
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">

            <button type="submit"
                    style="background:#064f80; color:white; border:none; padding:12px 20px; border-radius:6px; cursor:pointer;">
                Salvar Alterações
            </button>

            <a href="{{ route('clientes.index') }}"
               style="background:#e5e7eb; color:#333; padding:12px 20px; border-radius:6px; text-decoration:none;">
                Cancelar
            </a>

        </div>
    </form>

    <!-- GERENCIAMENTO DO CADASTRO -->
    <div style="margin-top:35px; padding-top:25px; border-top:1px solid #e2e8f0;">

        @if ($cliente->ativo)

            <div style="background:#fff7f7; border:1px solid #fecaca; padding:20px; border-radius:8px;">

                <h3 style="color:#991b1b; margin:0 0 10px;">
                    Desativar Cliente
                </h3>

                <p style="color:#64748b; margin-bottom:18px;">
                    O cliente ficará inativo, mas seus dados e histórico
                    serão preservados no sistema.
                </p>

                <form action="{{ route('clientes.destroy', $cliente) }}"
                      method="POST"
                      onsubmit="return confirm('Deseja realmente desativar este cliente?');">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            style="background:#b91c1c; color:white; padding:11px 18px; border:none; border-radius:6px; cursor:pointer;">
                        Desativar Cliente
                    </button>

                </form>
            </div>

        @else

            <div style="background:#f0fdf4; border:1px solid #bbf7d0; padding:20px; border-radius:8px;">

                <h3 style="color:#166534; margin:0 0 10px;">
                    Reativar Cliente
                </h3>

                <p style="color:#64748b; margin-bottom:18px;">
                    Este cliente está inativo. Você pode reativá-lo
                    sem precisar realizar um novo cadastro.
                </p>

                <form action="{{ route('clientes.reativar', $cliente) }}"
                      method="POST"
                      onsubmit="return confirm('Deseja reativar este cliente?');">

                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            style="background:#15803d; color:white; padding:11px 18px; border:none; border-radius:6px; cursor:pointer;">
                        Reativar Cliente
                    </button>

                </form>
            </div>

        @endif

    </div>

</div>

@endsection
