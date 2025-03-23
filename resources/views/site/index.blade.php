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
                <a href="#"><i class="fas fa-shopping-cart"></i> Carrinh</a>
            </section>
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
                    <a href="{{ route('login') }}" class="login-link">Faça seu login ou cadastre-se</a>
                </div>
                <section class="generes">
                    <a class="generes-button">Bebê</a>
                    <a class="generes-button">Menina</a>
                    <a class="generes-button">Menino</a>
                </section>
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
            <div class="retangulão">
                <img src="img/produtos/retangulo/algo5img.jpg" alt="" class="product-image">
            </div>
            <div class="retangulão">
                <img src="img/produtos/retangulo/algo6img.jpg" alt="" class="product-image">
            </div>
        </section>
        <section class="categorias">
            <h2 class="titulo-categorias">Navegue pelas Categorias</h2>
            <div class="faixa"></div>
            <div class="circulos-container">
                <div class="circulo">
                    <img src="img/produtos/circulo/circulo1.jpg" alt="" class="imagem-circulo">
                    <span class="Descricao">Conjuntos</span>
                </div>
                <div class="circulo">
                    <img src="img/produtos/circulo/circulo2.webp" alt="" class="imagem-circulo">
                    <span class="Descricao">Camisetas</span>
                </div>
                <div class="circulo">
                    <img src="img/produtos/circulo/circulo1.jpg" alt="img circulo" class="imagem-circulo">
                    <span class="Descricao">Calças</span>
                </div>
                <div class="circulo">
                    <img src="img/produtos/circulo/circulo2.webp" alt="img circulo" class="imagem-circulo">
                    <span class="Descricao">Vestidos</span>
                </div>
            </div>
        </section>

        <section class="categorias">
            <h2 class="titulo-categorias">Mais Vendidos</h2>
            <div class="faixa"></div>
            <div class="item"></div>
            <div class="retangulos-best-seller">
                <div class="retangulo">
                    <img src="img/carossel/algo2img.jpg" alt="" class="imagem-best-seller">
                    <span class="Descricao">Vestido colorido Feminino</span>
                    <span class="Precinho">R$60,00</span>
                    <button>Comprar</button>
                </div>
                <div class="retangulo">
                    <img src="img/produtos/retangulo/algo6img.jpg" alt="" class="imagem-best-seller">
                    <span class="Descricao">Conjunto laranja masculino</span>
                    <span class="Precinho">R$69,00</span>
                    <button>Comprar</button>
                </div>
                <div class="retangulo">
                    <img src="img/produtos/circulo/circulo2.webp" alt="img/circulo2.webp" class="imagem-best-seller">
                    <span class="Descricao">Conjunto Ursinho Feminino</span>
                    <span class="Precinho">R$70,00</span>
                    <button>Comprar</button>
                </div>
                <div class="retangulo">
                    <img src="img/produtos/circulo/circulo1.jpg" alt="img/circulo.jpg" class="imagem-best-seller">
                    <span class="Descricao">Conjunto macacão masculino</span>
                    <span class="Precinho">R$80,00</span>
                    <button>Comprar</button>
                </div>
            </div>
            </div>
        </section>
        <section class="categorias">
            <h2 class="titulo-categorias">Novidades</h2>
            <div class="faixa"></div>
            <div class="retangulos-best-seller">
                <div class="retangulo">
                    <img src="img/carossel/algo4img.jpg" alt="" class="imagem-best-seller">
                    <span class="Descricao">Conjuntos</span>
                    <span class="Precinho">R$60,00</span>
                    <button>Comprar</button>
                </div>
                <div class="retangulo">
                    <img src="img/carossel/algo2img.jpg" alt="" class="imagem-best-seller">
                    <span class="Descricao">Camisetas</span>
                    <span class="Precinho">R$69,00</span>
                    <button>Comprar</button>
                </div>
                <div class="retangulo">
                    <img src="img/produtos/circulo/circulo2.webp" alt="img/circulo2.webp" class="imagem-best-seller">
                    <span class="Descricao">Calças</span>
                    <span class="Precinho">R$70,00</span>
                    <button>Comprar</button>
                </div>
                <div class="retangulo">
                    <img src="img/produtos/circulo/circulo1.jpg" alt="img/circulo.jpg" class="imagem-best-seller">
                    <span class="Descricao">Vestidos</span>
                    <span class="Precinho">R$80,00</span>
                    <button>Comprar</button>
                </div>
            </div>
        </section>
        <section class="season">
            <div class="season-container">
                <div class="season-retangulão">
                    <img src="img/produtos/retangulo/algo5img.jpg" alt="Imagem 1" class="season-image">
                </div>
                <div class="season-retangulão">
                    <img src="img/carossel/algo2img.jpg" alt="Imagem 2" class="season-image">
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

</body>

</html>