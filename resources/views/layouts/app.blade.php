<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Real Motor')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f5f8;
            color: #333;
        }

        /* MENU LATERAL */

        .sidebar {
            width: 230px;
            height: 100vh;
            background: #064f80;
            color: white;
            position: fixed;
            padding: 25px 15px;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 5px 8px 25px;
            border-bottom: 1px solid #ffffff30;
            margin-bottom: 25px;
        }

        .logo h2 {
            font-size: 20px;
            margin: 0;
            color: white;
        }

        .logo span {
            display: block;
            font-size: 11px;
            color: #d9e6ef;
            margin-top: 4px;
        }

        .estrela {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
        }

        .sidebar nav a {
            display: block;
            color: #e2e8f0;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 7px;
            font-size: 14px;
            transition: background 0.2s;
        }

        .sidebar nav a:hover {
            background: #ffffff20;
        }

        .sidebar nav a.ativo {
            background: #ffffff20;
            border-left: 4px solid #ffde00;
            color: white;
            font-weight: bold;
        }

        /* CONTEÚDO */

        .content {
            margin-left: 230px;
            padding: 30px;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 20px 25px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #ffde00;
        }

        .header h2 {
            margin: 0;
            color: #064f80;
            font-size: 23px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px #00000010;
        }

        .card h3 {
            color: #064f80;
        }

        /* LINKS E BOTÕES */

        a {
            color: #064f80;
        }

        button,
        input[type="submit"] {
            background: #064f80;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover,
        input[type="submit"]:hover {
            background: #043b60;
        }

        input,
        select,
        textarea {
            padding: 9px;
            border: 1px solid #ccd5df;
            border-radius: 5px;
            max-width: 100%;
        }

        /* TABELAS */

        table {
            background: white;
            border-collapse: collapse;
            width: 100%;
        }

        table th {
            background: #064f80;
            color: white;
            padding: 12px;
            text-align: left;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        table tr:hover td {
            background: #f8fafc;
        }

        /* CORREÇÃO DA PAGINAÇÃO DO LARAVEL */

        nav svg {
            width: 20px;
            height: 20px;
            max-width: 20px;
            max-height: 20px;
        }

        nav[role="navigation"] {
            margin-top: 20px;
            font-size: 14px;
        }

        nav[role="navigation"] a,
        nav[role="navigation"] span {
            font-size: 14px;
        }

        nav[role="navigation"] svg {
            display: inline-block;
            vertical-align: middle;
        }

        /* DASHBOARD */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 25px;
        }

        .dashboard-card {
            display: block;
            background: white;
            padding: 25px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            box-shadow: 0 2px 5px #00000010;
            border-bottom: 4px solid #ffde00;
            transition: transform 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
        }

        .dashboard-card h3 {
            color: #064f80;
            margin: 0 0 10px;
        }

        .dashboard-card p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        /* RESPONSIVIDADE */

        @media (max-width: 700px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
                padding: 15px;
                overflow-x: auto;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="logo">
            <svg class="estrela"
                 viewBox="0 0 100 100"
                 xmlns="http://www.w3.org/2000/svg">

                <polygon
                    points="50,3 62,36 98,36 69,58 80,94 50,73 20,94 31,58 2,36 38,36"
                    fill="#ffde00"
                />
            </svg>

            <div>
                <h2>REAL MOTOR</h2>
                <span>Oficina Mecânica</span>
            </div>
        </div>

        <nav>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'ativo' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('clientes.index') }}"
               class="{{ request()->routeIs('clientes.*') ? 'ativo' : '' }}">
                Clientes
            </a>

            <a href="{{ route('veiculos.index') }}"
               class="{{ request()->routeIs('veiculos.*') ? 'ativo' : '' }}">
                Veículos
            </a>

            <a href="{{ route('marcas.index') }}"
               class="{{ request()->routeIs('marcas.*') ? 'ativo' : '' }}">
                Marcas
            </a>

            <a href="{{ route('modelos.index') }}"
               class="{{ request()->routeIs('modelos.*') ? 'ativo' : '' }}">
                Modelos
            </a>
        </nav>

    </aside>

    <main class="content">

        <div class="header">
            <h2>@yield('title', 'Painel de Controle')</h2>
        </div>

        @yield('content')

    </main>

</body>
</html>
