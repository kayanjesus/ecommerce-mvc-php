<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    {{-- Seu cabeçalho top --}}
    <div class="header-top">
        <div class="social-links">
            <a href="https://www.instagram.com/cantinho_das_isas_?igsh=MXVjbDF6cDBpMjR4cw=="><i
                    class="fab fa-instagram fa-lg"></i></a>
            <a href="https://wa.me/5511999999999"><i class="fab fa-whatsapp fa-lg"></i></a>
        </div>
        <nav class="user-nav">
            {{-- Certifique-se de que 'home.dashboard' e 'pagamento.cep' existem ou ajuste --}}
            <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}"><i class="fas fa-box fa-lg"></i> Meus
                pedidos</a>
            <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}"><i class="fas fa-heart fa-lg"></i>
                Favoritos</a>
            <a href="{{ route('pagamento.cep') }}"><i class="fas fa-shopping-cart fa-lg"></i> Carrinho</a>
        </nav>
        {{-- Adicione o formulário de logout aqui, fora do social-links/user-nav --}}
        <div class="top-nav">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                    style="color: white; text-decoration: none; font-weight: bold;">
                    Sair
                </a>
            </form>
        </div>
    </div>

    <main style="display: flex;"> {{-- Adicione display: flex para suas colunas --}}
        <div class="left-column">


            <div class="user-section">
                <div class="user-icon"><i class="fas fa-user"></i></div>
                <span class="username">{{ Auth::user()->name }}</span>
                <a href="{{ route('profile.edit') }}" class="edit-btn" id="editProfileBtn">Editar</a>
            </div>

            <div class="button-section">
                {{-- A variável $currentView precisaria ser passada para o layout ou obtida de forma diferente --}}
                {{-- Por enquanto, vou deixar como está, mas pode precisar de ajuste na sua lógica de controller --}}
                <button class="nav-button @if(isset($currentView) && $currentView === 'pedidos') active @endif"
                    data-view="pedidos"
                    onclick="window.location.href='{{ route('home.dashboard', ['show' => 'pedidos']) }}'">
                    Pedidos <span>&gt;</span>
                </button>
                <button class="nav-button @if(isset($currentView) && $currentView === 'favoritos') active @endif"
                    data-view="favoritos"
                    onclick="window.location.href='{{ route('home.dashboard', ['show' => 'favoritos']) }}'">
                    Favoritos <span>&gt;</span>
                </button>

                {{-- Você pode adicionar mais botões aqui para outras seções do perfil --}}
            </div>
        </div>

        <div class="logo">
            <a href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa - Logo" class="logo-img">
            </a>
        </div>


        {{-- AQUI É ONDE O CONTEÚDO ESPECÍFICO DA PÁGINA SERÁ INSERIDO (como a tela de edição de perfil) --}}
        <div id="dynamic-content" class="content-view">
            {{ $slot }} {{-- Esta é a diretriz essencial do Blade para inserir o conteúdo da view filha --}}
        </div>
    </main>
</body>

</html>