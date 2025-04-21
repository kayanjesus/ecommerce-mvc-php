<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/descricao.css') }}">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
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
                <a href="#"><i class="fas fa-shopping-cart"></i> Carrinho</a>
            </section>
        </nav>
        <section class="search-bar">
            <input type="text" placeholder="Pesquise aqui...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </section>
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="logo" class="logo-medium" class="logo-img">
            </a>
            <div class="nav">
                <div class="login-link">
                    <i class="fas fa-user"></i>
                    <a href="{{ route('login') }}" class="login-link">Faça seu login ou cadastre-se</a>
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


    <div class="shipping-info">
        <p>Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399</p>
    </div>
    </header>

    <main>
        <section class="product-detail">
            <div class="product-gallery">
                <!-- Botões de navegação -->
                <button class="carousel-button prev">&lt;</button>
                <button class="carousel-button next">&gt;</button>

                <!-- Container do carrossel -->
                <div class="carousel-container">
                    <div class="carousel-track">
                        <!-- Imagens do produto (substitua pelas suas imagens) -->
                        <div class="carousel-slide">
                            <img src="{{ asset($produto->img) }}" alt="Vestido Infantil Horizonte - Foto 1"
                                class="product-image">
                        </div>
                        <!-- <div class="carousel-slide">
                            <img src="../img/descricao/R2.jpg" alt="Vestido Infantil Horizonte - Foto 2"
                                class="product-image">
                        </div>
                        <div class="carousel-slide">
                            <img src="../img/descricao/R3.jpg" alt="Vestido Infantil Horizonte - Foto 3"
                                class="product-image">
                        </div> -->

                    </div>

                    <!-- Miniaturas (opcional) -->
                    <div class="carousel-thumbnails">
                        <img src="../img/descricao/R1.jpg" alt="Miniatura 1" class="thumbnail active">
                        <img src="../img/descricao/R2.jpg" alt="Miniatura 2" class="thumbnail">
                        <img src="../img/descricao/R3.jpg" alt="Miniatura 3" class="thumbnail">
                    </div>
                </div>
            </div>

            <div class="product-info">
                <h2>{{ $produto->nome_produto }}</h2>
                <h2>{{ $produto->descricao }}</h2>
                <h4>Modelo: xxxxxxxx</h4>
                <div class="rating">
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                </div>

                <div class="pricing">
                    <div class="payment-option">
                        <h3>
                            <span class="pix-icon">
                                <i class="fa-brands fa-pix"></i>
                            </span>
                            PIX
                        </h3>
                        <p class="price">R${{ number_format($produto->preco, 2, ',', '.') }}</p>
                    </div>

                    <div class="discounts">
                        <a href="#">-5%</a>
                    </div>

                    <div class="payment-option">
                        <h2>
                            <span class="card-icon">
                                <i class="fa-solid fa-credit-card"></i>
                            </span>
                            CARTÃO
                        </h2>
                        <p class="price2">R$ 41,90</p>
                    </div>
                </div>

                <div class="size-selector">
                    <h3>Tamanho:</h3>
                    <div class="sizes">
                        <button>10</button>
                        <button>12</button>
                        <button>14</button>
                        <button>16</button>
                    </div>
                </div>

                <!-- <div class="action-container">
                    <div class="quantity-container">
                        <div class="quantity-box">
                            <div class="quantity-label">Quantidade</div>
                            <div class="quantity-controls">
                                <div class="quantity-arrows">
                                    <button class="qty-arrow-up">↑</button>
                                    <button class="qty-arrow-down">↓</button>
                                </div>
                                <div class="quantity-number">1</div>
                            </div>
                        </div>
                    </div> -->

                <form action="{{ route('home.addcarrinho') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $produto->id_produto }}">
                    <input type="hidden" name="name" value="{{ $produto->nome_produto }}">
                    <input type="hidden" name="price" value="{{ $produto->preco }}">
                    <div class="action-container">
                        <div class="quantity-container">
                            <div class="quantity-box">
                                <div class="quantity-label">Quantidade</div>
                                <input type="number" name="qnt" value="1">
                            </div>
                        </div>
                        <input type="hidden" name="img" value="{{ $produto->img }}">
                        <button class="add-to-cart">Adicionar ao carrinho</button>
                </form>
            </div>
            <div class="actions">

                <button class="add-to-favorites">
                    <i class="far fa-heart"></i> Adicionar aos favoritos
                </button>
            </div>
            </div>
        </section>

        <section class="product-description">
            <h3>Descrição do produto</h3>
            <p>Características</p>

            <h3>Marca</h3>
            <p>Composição</p>
            <p>Cor</p>
            <p>Gênero</p>
            <p>Estação</p>
        </section>

        <section class="related-products">
            <h2>Relacionados</h2>
            <div class="products-grid">
                <div class="product-card">
                    <img src="conjunto-laranja.jpg" alt="Conjunto laranja masculino">
                    <h3>Conjunto laranja masculino</h3>
                    <p class="price">R$ 69,99</p>
                    <button class="buy-button">Comprar</button>
                </div>

                <div class="product-card">
                    <img src="conjunto-ursinho.jpg" alt="Conjunto Ursinho Feminino">
                    <h3>Conjunto Ursinho Feminino</h3>
                    <p class="price">R$ 70,00</p>
                    <button class="buy-button">Comprar</button>
                </div>

                <div class="product-card">
                    <img src="conjunto-macacao.jpg" alt="Conjunto macacão masculino">
                    <h3>Conjunto macacão masculino</h3>
                    <p class="price">R$ 80,00</p>
                    <button class="buy-button">Comprar</button>
                </div>
            </div>
        </section>

        <section class="reviews">
            <h2>Avaliações do produto</h2>

            <div class="review">
                <h4>Nome cliente</h4>
                <p class="review-date">09/09/09 - São Paulo - SP</p>
                <div class="rating">
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                </div>
                <p class="review-text">Avaliação do cliente......</p>
            </div>

            <div class="review">
                <h4>Nome cliente</h4>
                <p class="review-date">09/09/09 - São Paulo - SP</p>
                <div class="rating">
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                </div>
                <p class="review-text">Avaliação do cliente......</p>
            </div>

            <div class="review">
                <h4>Nome cliente</h4>
                <p class="review-date">09/09/09 - São Paulo - SP</p>
                <div class="rating">
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                    <span class="star">★</span>
                </div>
                <p class="review-text">Avaliação do cliente......</p>
            </div>

            <button class="see-more">Ver mais</button>
        </section>
    </main>

    <footer>

    </footer>
    <script src="{{ asset('js/descricao.js') }}"></script>

</body>

</html>