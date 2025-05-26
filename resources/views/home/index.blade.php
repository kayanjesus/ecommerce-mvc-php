<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/home.css')}}">


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
                <a href="{{ route('home.favoritos') }}"><i class="fas fa-heart"></i> Favoritos</a>
                <a href="{{ route('home.carrinho') }}"><i class="fas fa-shopping-cart"></i> Carrinho</a>
            </section>

        </nav>

        <form action="{{ route('home.index') }}" method="GET">
            <section class="search-bar">
                <input type="text" name="search" placeholder="Pesquise aqui..." value="{{ request('search') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </section>
        </form>



        <div class="logo">
            <a href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="logo" class="logo-medium" class="logo-img">
            </a>
            <div class="nav">
                <div class="login-link">
                    <i class="fas fa-user"></i>

                    @auth
                        <a
                            href="{{ Auth::user()->access_level === 'admin' ? route('adm.dashboard') : route('home.dashboard') }}">
                            {{ Auth::user()->name }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="login-link">
                            Faça seu login ou cadastre-se
                        </a>
                    @endauth
                </div>


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


    <main>

        <section class="banner">
            <div class="banner-slide active"><img src="img/carossel/algo1img.jpg" alt="Banner 1"></div>
            <div class="banner-slide"><img src="img/carossel/algo2img.jpg" alt="Banner 2"></div>
            <div class="banner-slide"><img src="img/carossel/algo3img.jpg" alt="Banner 3"></div>
            <div class="banner-slide"><img src="img/carossel/algo4img.jpg" alt="Banner 4"></div>
            <div class="banner-controls"><button class="prev" onclick="changeSlide(-1)">&#10094;</button>
                <button class="next" onclick="changeSlide(1)">&#10095;</button>
            </div>
            <div class="banner-indicators">
                <span class="indicator" onclick="goToSlide(0)"></span>
                <span class="indicator" onclick="goToSlide(1)"></span>
                <span class="indicator" onclick="goToSlide(2)"></span>
                <span class="indicator" onclick="goToSlide(3)"></span>
            </div>
        </section>



        <section class="informacoes-frete">
            <div class="informacoes-container">
                <div class="frete-info">
                    <i class="fas fa-truck"></i>
                    <span class="frete-texto"><strong>Frete Grátis</strong> - Sul e Sudeste R$250, demais regiões
                        R$399</span>
                </div>
                <div class="avaliacoes-info">
                    <i class="fas fa-star"></i>
                    <span>Avaliações dos clientes</span>
                </div>
                <div class="parcelamento-info">
                    <i class="fa-solid fa-credit-card"></i>
                    <span>Em até 6 vezes</span>
                </div>
            </div>
        </section>



        <section class="products">
            <a href="{{ route('home.categoria', ['id_categoria' => 4, 'genero' => 'Masculino']) }}">
                <div class="retangulão">
                    <img src="img/produtos/retangulo/algo7img.jpg" alt="Conjunto Menino" class="product-image">
                </div>
            </a>

            <a href="{{ route('home.categoria', ['id_categoria' => 4, 'genero' => 'Feminino']) }}">
                <div class="retangulão">
                    <img src="img/produtos/retangulo/algo8img.jpg" alt="Conjunto Menina" class="product-image">
                </div>
            </a>
        </section>



        <h2 class="titulo-categorias">Navegue pelas Categorias</h2>
        <div class="circulos-container">
            @foreach ($categoriasMenu as $categoria)
                <a href="{{ route('home.categoria', $categoria->id_categoria) }}">
                    <div class="circulo">
                        <img src="img/produtos/circulo/circulo{{ $loop->iteration }}.jpg" alt="img circulo"
                            class="imagem-circulo">
                        <span class="Descricao">{{ $categoria->nome_categoria }}</span>
                    </div>
                </a>
            @endforeach
        </div>



        <!-- Produtos -->
        <section class="categorias">
            <h2 class="titulo-categorias">Produtos</h2>
            <div class="faixa"></div>
            <div class="item"></div>
            <div class="retangulos-best-seller">

                @foreach ($produtos as $produto)
                    <div class="retangulo">
                        <a href="{{ route('home.details', $produto->slug) }}">
                            <img src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                        </a>
                        <span class="Descricao">{{ $produto->nome_produto }}</span>
                        <span class="Descricao">{{ Str::limit($produto->variacao, 25) }}</span>
                        <span class="Precinho">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                        <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                    </div>
                @endforeach

            </div>
        </section>


        <section class="categorias">
            <h2 class="titulo-categorias">Novidades</h2>
            <div class="faixa"></div>
            <div class="retangulos-best-seller">
                @foreach($novidades as $produto)
                    <div class="retangulo">
                        <a href="{{ route('home.details', $produto->slug) }}">
                            <img src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                        </a>
                        <span class="Descricao">{{ $produto->nome_produto }}</span>
                        <span class="Descricao">{{ Str::limit($produto->variacao, 25) }}</span>
                        <span class="Precinho">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                        <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                    </div>
                @endforeach
            </div>
        </section>


        <section class="season">
            <div class="season-container">
                <!-- Inverno -->
                <div class="season-retangulão">
                    <a href="{{ route('temporada', ['temporada' => 'inverno']) }}">
                        <img src="img/produtos/retangulo/algo5img.jpg" alt="Imagem Inverno" class="season-image">
                    </a>
                </div>

                <!-- Verão -->
                <div class="season-retangulão">
                    <a href="{{ route('temporada', ['temporada' => 'verao']) }}">
                        <img src="img/produtos/retangulo/algo6img.jpg" alt="Imagem Verão" class="season-image">
                    </a>
                </div>
            </div>
        </section>


    </main>

    <section class="customer-reviews">
        <h2 class="titulo-avaliacoes">Avaliações dos Clientes</h2>
        <div class="faixa"></div>
        <div class="reviews-container">
            <div class="review">
                <p class="review-texto">"Produtos de ótima qualidade, recomendo a todos!"</p>
                <div class="review-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <span class="review-author">- Ana Clara</span>
            </div>
            <div class="review">
                <p class="review-texto">"Entrega rápida e atendimento incrível. Minha filha adorou!"</p>
                <div class="review-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="review-author">- Lucas Ferreira</span>
            </div>
            <div class="review">
                <p class="review-texto">"Roupa linda, bem acabada e com preço justo. Voltarei a comprar!"</p>
                <div class="review-rating">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                </div>
                <span class="review-author">- Mariana Silva</span>
            </div>
        </div>
    </section>

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
            </div>
        </div>
    </footer>
    <script src="{{ asset('js/carrosel.js') }}"></script>
    <!-- <script src="{{ asset('js/carrinho.js') }}"></script> -->




</body>

</html>