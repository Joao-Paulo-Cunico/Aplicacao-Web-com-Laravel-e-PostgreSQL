
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Oficina Mecânica')</title>

        <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Bebas Neue, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            background: #0e1739;
            color: white;
            position: fixed;
            padding: 20px;
        }

        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #e2e8f0;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .sidebar a:hover {
            background: #334155;
        }

        .content {
            margin-left: 230px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px #00000010;
        }

        @media (max-width: 700px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
   
</head>
<body>

    <aside class="sidebar">
        <h2>Oficina Mecânica</h2>

        <nav>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="#">Clientes</a>
            <a href="#">Veículos</a>
        </nav>
    </aside>

    <main class="content">
        <div class="header">
            <h2>@yield('title', 'Painel')</h2>
        </div>

        @yield('content')
    </main>

</body>
</html>
