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
                <a href="{{ route('home.carrinho') }}"><i class="fas fa-shopping-cart"></i> Carrinho</a>
            </section>
        </nav>
        <section class="search-bar">
            <input type="text" placeholder="Pesquise aqui...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </section>
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

    <div class="shipping-info">
        <p>Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399</p>
    </div>
    </header>

    <main>
        <section class="product-detail">
            <div class="product-gallery">
                <button class="carousel-button prev">&lt;</button>
                <button class="carousel-button next">&gt;</button>
                <div class="carousel-container">
                    <div class="carousel-track">
                        <div class="main-image">
                            <img src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                id="mainProductImage" />
                        </div>
                    </div>
                    <div class="thumbnail-container">
                        @foreach ($produto->imagens as $imagem)
                            <img src="{{ asset($imagem->caminho) }}" class="thumbnail {{ $loop->first ? 'active' : '' }}"
                                onclick="changeMainImage(this)" />
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="product-info">
                <h2>{{ $produto->nome_produto }}</h2>
                <h2>{{ $produto->descricao }}</h2>
                <h4>Modelo: {{ $produto->modelo }}</h4>
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
                        @foreach($produto->variacoes->unique('tamanho_id') as $variacao)
                            <button type="button" class="tamanho-btn"
                                data-tamanho-id="{{ $variacao->tamanho->id_tamanho }}"> {{-- Usar id_tamanho --}}
                                {{ $variacao->tamanho->nome }}
                            </button>
                        @endforeach
                    </div>
                    {{-- REMOVIDO: <input type="hidden" name="tamanho_id" id="tamanho_id" value=""> --}}
                </div>

                <div class="color-selector">
                    <h3>Cor:</h3>
                    <div class="colors">
                        @foreach($produto->variacoes->unique('cor_id') as $variacao)
                            <button type="button" class="color-btn" data-cor-id="{{ $variacao->cor->id_cor }}" {{-- Usar
                                id_cor --}}
                                style="background-color: {{ $variacao->cor->codigo_hex }};
                                               width: 30px; height: 30px; border-radius: 50%; margin-right: 10px;
                                               border: 1px solid {{ $variacao->cor->codigo_hex == '#FFFFFF' ? '#ccc' : 'transparent' }};"
                                title="{{ $variacao->cor->nome }}"></button>
                        @endforeach
                    </div>
                    {{-- REMOVIDO: <input type="hidden" name="cor_id" id="cor_id" value=""> --}}
                </div>


                <div class="action-buttons">
                    <form action="{{ route('home.addcarrinho') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $produto->id_produto }}">
                        <input type="hidden" name="name" value="{{ $produto->nome_produto }}">
                        <input type="hidden" name="price" value="{{ $produto->preco }}">
                        <input type="hidden" name="tamanho_id" id="tamanho_id" value=""> {{-- ESTE É O QUE DEVE SER
                        ATUALIZADO --}}
                        <input type="hidden" name="cor_id" id="cor_id" value=""> {{-- ESTE É O QUE DEVE SER ATUALIZADO
                        --}}
                        <input type="hidden" name="img" value="{{ $produto->imagens->first()->caminho }}">
                        <div class="action-container">
                            <div class="quantity-container">
                                <div class="quantity-box">
                                    <div class="quantity-label">Quantidade</div>
                                    <div class="quantity-input-container">
                                        <input type="number" min="1" name="quantity" value="1" class="quantity-input">
                                    </div>
                                </div>
                            </div>
                            <button class="add-to-cart" id="add-to-cart-btn" disabled>Adicionar ao carrinho</button>
                        </div>
                    </form>
                </div>


                <div class="actions">
                    <form action="{{ route('home.addfavoritos') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $produto->id_produto }}">
                        <input type="hidden" name="name" value="{{ $produto->nome_produto }}">
                        <input type="hidden" name="price" value="{{ $produto->preco }}">
                        <input type="hidden" name="img" value="{{ $produto->imagens->first()->caminho }}">
                        <button type="submit" class="add-to-favorites">
                            <i class="far fa-heart"></i> Adicionar aos favoritos
                        </button>
                    </form>
                </div>
            </div>

        </section>

        <section class="product-description">
            <div class="tabs">
                <button class="tab-button active" data-tab="descricao">Descrição</button>
                <button class="tab-button" data-tab="caracteristicas">Características</button>
            </div>

            <div class="tab-content">
                <div id="descricao" class="tab-pane active">
                    <h3>Descrição do Produto</h3>
                    <p>{{ $produto->variacao ?? 'Descrição não disponível' }}</p>
                </div>

                <div id="caracteristicas" class="tab-pane">
                    <h3>Características Técnicas</h3>
                    <ul class="specs-list">
                        <li><strong>Marca:</strong> {{ $produto->marca }}</li>
                        <li><strong>Composição:</strong> {{ $produto->tecido }}</li>
                        <li><strong>Cores disponíveis:</strong>
                            @foreach($produto->variacoes->unique('cor_id') as $variacao)
                                <span class="color-chip" style="background-color: {{ $variacao->cor->codigo_hex }}"></span>
                                <span>{{ $variacao->cor->nome }}</span>
                            @endforeach
                        </li>
                        <li><strong>Modelo:</strong> {{ $produto->modelo }}</li>
                        <li><strong>Estação:</strong> {{ $produto->estacao }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="related-products">
            <h2>Relacionados</h2>
            <div class="products-grid">
                @foreach ($produtos as $produto)
                    <div class="product-card">
                        <a href="{{ route('home.details', $produto->slug) }}">
                            <img src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                        </a>
                        <h3>{{ $produto->nome_produto }}</h3>
                        <p class="price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                    </div>
                @endforeach
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sistema de abas
            const tabButtons = document.querySelectorAll('.tab-button');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    document.querySelectorAll('.tab-button').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('active');
                    });
                    this.classList.add('active');
                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                });
            });

            // Seleção de tamanho e cor
            const tamanhoBtns = document.querySelectorAll('.tamanho-btn');
            const colorBtns = document.querySelectorAll('.color-btn');
            const addToCartBtn = document.getElementById('add-to-cart-btn');

            // Referências aos inputs DENTRO do formulário
            const formTamanhoInput = document.querySelector('form[action="{{ route('home.addcarrinho') }}"] [name="tamanho_id"]');
            const formCorInput = document.querySelector('form[action="{{ route('home.addcarrinho') }}"] [name="cor_id"]');

            function checkSelection() {
                const tamanhoSelecionado = formTamanhoInput.value;
                const corSelecionada = formCorInput.value;
                addToCartBtn.disabled = !(tamanhoSelecionado && corSelecionada);
            }

            tamanhoBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    tamanhoBtns.forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    formTamanhoInput.value = this.dataset.tamanhoId;
                    checkSelection();
                });
            });

            colorBtns.forEach(btn => {
                btn.addEventListener('click', function () {
                    colorBtns.forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    formCorInput.value = this.dataset.corId;
                    checkSelection();

                    const bgColor = this.style.backgroundColor.toLowerCase();
                    if (bgColor === '#ffffff' || bgColor === 'white' || bgColor === '#fff') {
                        this.style.border = '1px solid #ccc';
                    }
                });
            });

            // Inicializa o estado do botão "Adicionar ao carrinho" ao carregar a página
            checkSelection();

            // Debug do formulário (mantido para visualização no console)
            document.querySelector('form[action="{{ route('home.addcarrinho') }}"]').addEventListener('submit', function (e) {
                console.log('Dados do formulário:');
                console.log('ID:', this.querySelector('[name="id"]').value);
                console.log('Nome:', this.querySelector('[name="name"]').value);
                console.log('Preço:', this.querySelector('[name="price"]').value);
                console.log('Quantidade:', this.querySelector('[name="quantity"]').value);
                console.log('Cor ID:', this.querySelector('[name="cor_id"]').value);
                console.log('Tamanho ID:', this.querySelector('[name="tamanho_id"]').value);
                console.log('Imagem:', this.querySelector('[name="img"]').value);

                if (!this.querySelector('[name="cor_id"]').value || !this.querySelector('[name="tamanho_id"]').value) {
                    e.preventDefault();
                    // Substitua alert por uma mensagem mais amigável na UI, se possível
                    // Por enquanto, mantido para debug. Lembre-se: alert() não é ideal para produção.
                    alert('Por favor, selecione cor e tamanho antes de adicionar ao carrinho.');
                }
            });
        });
    </script>

    <script>
        function changeMainImage(thumbnail) {
            document.getElementById('mainProductImage').src = thumbnail.src;
            document.querySelectorAll('.thumbnail').forEach(img => {
                img.classList.remove('active');
            });
            thumbnail.classList.add('active');
        }
    </script>

    {{-- Se 'descricao.js' contiver código duplicado ou em conflito, pode ser removido ou mesclado --}}
    {{--
    <script src="{{ asset('js/descricao.js') }}"></script> --}}

</body>

</html>