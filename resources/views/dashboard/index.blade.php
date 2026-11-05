
@extends('layouts.app')

@section('title', 'Painel de Controle')

@section('content')

<div class="card">
    <h3>Bem-vindo ao Real Motor!</h3>
    <p>
        Sistema de gerenciamento da oficina mecânica.
        Selecione abaixo o módulo que deseja acessar.
    </p>
</div>

<h3 style="margin: 25px 0 15px;">Clientes e Veículos</h3>

<div class="dashboard-grid">

    <a href="{{ route('clientes.index') }}" class="dashboard-card">
        <h3>Clientes</h3>
        <p>Gerencie os clientes cadastrados.</p>
    </a>

    <a href="{{ route('veiculos.index') }}" class="dashboard-card">
        <h3>Veículos</h3>
        <p>Gerencie veículos e proprietários.</p>
    </a>

    <a href="{{ route('marcas.index') }}" class="dashboard-card">
        <h3>Marcas</h3>
        <p>Consulte as marcas cadastradas.</p>
    </a>

    <a href="{{ route('modelos.index') }}" class="dashboard-card">
        <h3>Modelos</h3>
        <p>Gerencie os modelos de veículos.</p>
    </a>

</div>

<h3 style="margin: 30px 0 15px;">Oficina e Serviços</h3>

<div class="dashboard-grid">

    <a href="{{ route('mecanicos.index') }}" class="dashboard-card">
        <h3>Mecânicos</h3>
        <p>Gerencie os mecânicos da oficina.</p>
    </a>

    <a href="{{ route('servicos.index') }}" class="dashboard-card">
        <h3>Serviços</h3>
        <p>Consulte os serviços disponíveis.</p>
    </a>

    <a href="{{ route('ordens-servico.index') }}" class="dashboard-card">
        <h3>Ordens de Serviço</h3>
        <p>Acompanhe as ordens de serviço.</p>
    </a>

</div>

<h3 style="margin: 30px 0 15px;">Estoque e Financeiro</h3>

<div class="dashboard-grid">

    <a href="{{ route('pecas.index') }}" class="dashboard-card">
        <h3>Peças e Estoque</h3>
        <p>Gerencie peças e estoque.</p>
    </a>

    <a href="{{ route('pagamentos.index') }}" class="dashboard-card">
        <h3>Pagamentos</h3>
        <p>Gerencie os pagamentos da oficina.</p>
    </a>

</div>

@endsection
