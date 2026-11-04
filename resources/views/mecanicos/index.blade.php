@extends('layouts.app')

@section('title', 'Mecânicos') {{-- no create: 'Novo mecânico' --}}

@section('content')
    <h1>Mecânicos</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    <a href="{{ route('mecanicos.create') }}">Novo mecânico</a>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Especialidade</th>
                <th>Admissão</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mecanicos as $mecanico)
                <tr>
                    <td>{{ $mecanico->nome }}</td>
                    <td>{{ $mecanico->cpf }}</td>
                    <td>{{ $mecanico->telefone }}</td>
                    <td>{{ $mecanico->especilidade }}</td>
                    <td>{{ $mecanico->data_admissao->format('d/m/Y') }}</td>
                    <td>{{ $mecanico->ativo ? 'Sim' : 'Não' }}</td>
                    <td>
                        <a href="{{ route('mecanicos.show', $mecanico) }}">Ver</a>
                        <a href="{{ route('mecanicos.edit', $mecanico) }}">Editar</a>
                        <form action="{{ route('mecanicos.destroy', $mecanico) }}" method="POST" style="display:inline"
                            onsubmit="return confirm('Excluir este mecânico?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Nenhum mecânico cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $mecanicos->links() }}
@endsection
