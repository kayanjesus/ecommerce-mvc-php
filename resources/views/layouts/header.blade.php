<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <title>Cantinho da Isa</title>
</head>

<body>

    <header>
        <nav class="header-line">
            <section class="social-icons">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </section>
            <section class="top-nav">
                <a href="#"><i class="fas fa-box"></i> Meus pedidos</a>
                <a href="#"><i class="fas fa-heart"></i> Favoritos</a>
                <a href="{{ route('home.carrinho') }}"><i class="fas fa-shopping-cart"></i> Carrinho</a>
            </section>

            <!-- <section class="top-nav">
                <a href="#"><i class="fas fa-box"></i> Meus pedidos</a>
                <a href="#"><i class="fas fa-heart"></i> Favoritos</a>

                <div class="carrinho-container">
                    <a href="javascript:void(0);" id="btn-carrinho">
                        <i class="fas fa-shopping-cart"></i> Carrinho
                    </a>

                    <div id="dropdown-carrinho" class="dropdown-carrinho">
                        <p>Item 1 - R$50</p>
                        <p>Item 2 - R$30</p>
                        <p><strong>Total: R$80</strong></p>
                    </div>
                </div>
            </section> -->


        </nav>
        <section class="search-bar">
            <input type="text" placeholder="Pesquise aqui...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </section>
        <div class="logo">
            <img src="img/logo/ft_logo.png" alt="logo" class="logo-medium" class="logo-img">
            <div class="nav">
                <div class="login-link">
                    <i class="fas fa-user"></i>

                    @auth
                        <a href="{{ route('dashboard') }}" class="login-link">
                            {{ Auth::user()->name }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="login-link">
                            Faça seu login ou cadastre-se
                        </a>
                    @endauth
                </div>


                <!-- <div class="login-link">
                    <i class="fas fa-user"></i>
                    <a href="{{ route('login') }}" class="login-link">Faça seu login ou cadastre-se</a>
                </div> -->

                <section class="generes">
                    @foreach ($categoriasTopo as $categoria)
                        <a class="generes-button" href="{{ route('home.categoria', $categoria->id_categoria) }}">
                            {{ $categoria->nome_categoria }}
                        </a>
                    @endforeach
                </section>

            </div>
        </div>
    </header>