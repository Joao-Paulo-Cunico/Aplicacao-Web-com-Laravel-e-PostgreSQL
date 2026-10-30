
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Oficina Mecânica')</title>

   
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
