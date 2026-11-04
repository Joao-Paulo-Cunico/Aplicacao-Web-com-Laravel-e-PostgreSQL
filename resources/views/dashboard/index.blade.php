@extends('layouts.app')

@section('title', 'Painel de Controle')

@section('content')

    <div class="card">
        <h3>Bem-vindo ao Real Motor!</h3>

        <p>
            Sistema de gerenciamento da oficina mecânica.
            Utilize os atalhos abaixo para acessar as
            funcionalidades disponíveis.
        </p>
    </div>

    <div class="dashboard-grid">

        <a href="{{ route('clientes.index') }}"
           class="dashboard-card">

            <h3>Clientes</h3>

            <p>
                Consulte e gerencie os clientes cadastrados.
            </p>

        </a>

        <a href="{{ route('veiculos.index') }}"
           class="dashboard-card">

            <h3>Veículos</h3>

            <p>
                Gerencie os veículos e seus proprietários.
            </p>

        </a>

        <a href="{{ route('marcas.index') }}"
           class="dashboard-card">

            <h3>Marcas</h3>

            <p>
                Consulte as marcas de veículos cadastradas.
            </p>

        </a>

        <a href="{{ route('modelos.index') }}"
           class="dashboard-card">

            <h3>Modelos</h3>

            <p>
                Gerencie os modelos e suas respectivas marcas.
            </p>

        </a>

    </div>

@endsection
