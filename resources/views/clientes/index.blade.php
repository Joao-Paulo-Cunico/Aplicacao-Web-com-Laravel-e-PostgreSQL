
@extends('layouts.app')

@section('title', 'Clientes')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2>Clientes cadastrados</h2>

    <a href="{{ route('clientes.create') }}"
       style="background: #2563eb; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none;">
        + Novo Cliente
    </a>
</div>

@if(session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

<div style="background: white; padding: 20px; border-radius: 8px; overflow-x: auto;">

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding: 12px; text-align: left;">Nome</th>
                <th style="padding: 12px; text-align: left;">CPF</th>
                <th style="padding: 12px; text-align: left;">Telefone</th>
                <th style="padding: 12px; text-align: left;">E-mail</th>
                <th style="padding: 12px; text-align: left;">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($clientes as $cliente)
                <tr>
                    <td style="padding: 12px;">{{ $cliente->nome }}</td>
                    <td style="padding: 12px;">{{ $cliente->cpf }}</td>
                    <td style="padding: 12px;">{{ $cliente->telefone }}</td>
                    <td style="padding: 12px;">{{ $cliente->email }}</td>

                    <td style="padding: 12px;">
                        <a href="{{ route('clientes.show', $cliente) }}">
                            Ver
                        </a>

                        <a href="{{ route('clientes.edit', $cliente) }}">
                            Editar
                        </a>

                        <form action="{{ route('clientes.destroy', $cliente) }}"
                              method="POST"
                              style="display: inline;"
                              onsubmit="return confirm('Deseja excluir este cliente?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    style="color: red; border: none; background: none; cursor: pointer;">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center;">
                        Nenhum cliente cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $clientes->links() }}
    </div>

</div>

@endsection
