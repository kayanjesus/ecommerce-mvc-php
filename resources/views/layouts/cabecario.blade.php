<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Cantinho da Isa</title>
</head>

<body>
    <header>
        <div class="header-top">
            <div class="social-links">
                <a href="https://www.instagram.com/cantinho_das_isas_?igsh=MXVjbDF6cDBpMjR4cw=="><i
                        class="fab fa-instagram fa-lg"></i></a>
                <a href="https://wa.me/5511999999999"><i class="fab fa-whatsapp fa-lg"></i></a>
            </div>
            <nav class="user-nav">
                <a href="{{ route('home.dashboard', ['show' => 'pedidos']) }}"><i class="fas fa-box fa-lg"></i> Meus
                    pedidos</a>
                <a href="{{ route('home.dashboard', ['show' => 'favoritos']) }}"><i class="fas fa-heart fa-lg"></i>
                    Favoritos</a>
                <a href="{{ route('pagamento.cep') }}"><i class="fas fa-shopping-cart fa-lg"></i> Carrinho</a>
            </nav>
        </div>

        <div class="header-main">
            <div class="search-container">
                <form action="{{ route('home.index') }}" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Pesquisar..."
                        value="{{ request('search') }}">
                    <button type="submit" class="search-icon"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="logo-container">
                <a href="{{ route('home.index') }}">
                    <img src="{{ asset('img/logo/ft_logo.png') }}" alt="Cantinho da Isa - Logo" class="logo-img">
                </a>
            </div>

            <div class="auth-links">
                <i class="far fa-user"></i> @auth
                    <a
                        href="{{ Auth::user()->access_level === 'admin' ? route('adm.dashboard') : route('home.dashboard') }}">
                        {{ Auth::user()->name }}
                    </a>
                @else
                    <a href="{{ route('login') }}">Entre ou cadastre-se</a>
                @endauth
            </div>
        </div>

        <nav class="category-nav">
            {{-- As categorias ($categoriasTopo) precisam ser passadas para este layout via View Composer --}}
            @foreach ($categoriasTopo as $categoria)
                <a class="category-link" href="{{ route('home.categoria', $categoria->id_categoria) }}">
                    {{ $categoria->nome_categoria }}
                </a>
            @endforeach
        </nav>
    </header>

    {{-- AQUI É ONDE O CONTEÚDO DE CADA PÁGINA SERÁ INJETADO --}}
    @yield('content')

    <footer>
        <section class="top-footer">
            <h3>Cantinho da Isa</h3>
            <p>Crianças crescem rápido, não é mesmo? Em pouco tempo, as roupinhas vão ficando mais curtas, e é preciso
                renovar os guarda-roupas. Aqui no Cantinho da Isa, temos o melhor vestuário para os pequenos, e com os
                menores preços. Venha conferir. </p>
        </section>
        <div class="footer-container">
            <div class="footer-column">
                <h3>Institucional</h3>
                <ul>
                    <li><a href="#">Quem Somos</a></li>
                    <li><a href="#">Política de Privacidade</a></li>
                    <li><a href="#">Troca e Devolução</a></li>
                    <li><a href="#">Política de Entrega</a></li>
                    <li><a href="#">Política de Pagamento</a></li>
                    <li><a href="#">Ajuda</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Atendimento</h3>
                <p>( xx ) xxxx-xxxx</p>
                <p>De segunda-feira a sexta-feira:<br>12h ás 18h</p>
            </div>
            <div class="footer-column">
                <h3>Compre Seguro</h3>
                <p>Suas compras são processadas com segurança através do <strong>PagSeguro</strong>, garantindo proteção
                    total de seus dados e tranquilidade nas transações.</p>
                <ul class="payment-methods">
                    <li><img src="{{ asset('img/pagseguro.png') }}" alt="PagSeguro"></li>
                    <li><img src="{{ asset('img/mastercard.png') }}" alt="Mastercard"></li>
                    <li><img src="{{ asset('img/pix.png') }}" alt="Pix"></li>
                </ul>
            </div>


        </div>
    </footer>
    <script src="{{ asset("js/carrosel.js") }}"></script>
</body>

</html>