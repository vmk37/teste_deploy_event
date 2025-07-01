<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title')</title>

        <!-- Fontes do Google -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
        
        <!-- CSS da site -->
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
        @stack('styles') 

        <!-- js do site -->
        <script src="{{ asset('js/scripts.js') }}" defer></script>
        @stack('scripts')
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
                <div class="container-fluid px-4">
                    <a href="/" class="navbar-brand order-lg-1">
                        <img class="logo" src="{{ asset('img/EventConnect_logo.png') }}" alt="Logo">
                    </a>
                    <button class="navbar-toggler order-lg-3 order-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse order-lg-2 order-4" id="navbar">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item">
                                <a href="/" class="nav-link">Início</a>
                            </li>
                            <li class="nav-item">
                                <a href="/evento/criacao" class="nav-link">Criar Jogos</a>
                            </li>
                            @auth
                            <li class="nav-item">
                                <a href="/dashboard" class="nav-link">Gerenciar Jogos</a>
                            </li>
                            <li class="nav-item">
                                <form action="/logout" method="POST" style="display:inline;">
                                    @csrf
                                    <a href="/logout" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();">Sair</a>
                                </form>
                            </li>
                            @endauth
                            @guest
                            <li class="nav-item">
                                <a href="/login" class="nav-link">Entrar</a>
                            </li>
                            <li class="nav-item">
                                <a href="/register" class="nav-link">Cadastrar</a>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Área do conteúdo -->
        <main>
            <div class="container-fluid">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </main>    
    
        @include('partials.footer')
        
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>