@extends('layouts.cabecario') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/descricao.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <title>Cantinho da Isa</title>
    </head>

    <body>


        <div class="shipping-info">
            <p>Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399</p>
        </div>
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
                            <h3 classe="h3-sicroniza-cor">
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
                        <h3 classe="h3-sicroniza-cor">Tamanho:</h3>
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
                        <h3 classe="h3-sicroniza-cor">Cor:</h3>
                        <div class="colors">
                            @foreach($produto->variacoes->unique('cor_id') as $variacao)
                                <button type="button" class="color-btn" data-cor-id="{{ $variacao->cor->id_cor }}" {{-- Usar
                                    id_cor --}}
                                    style="background-color: {{ $variacao->cor->codigo_hex }};
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
                        <h3 classe="h3-sicroniza-cor">Descrição do Produto</h3>
                        <p>{{ $produto->variacao ?? 'Descrição não disponível' }}</p>
                    </div>

                    <div id="caracteristicas" class="tab-pane">
                        <h3 classe="h3-sicroniza-cor">Características Técnicas</h3>
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

            <!-- <section class="related-products">
                <h2>Relacionados</h2>
                <div class="products-grid">
                    @foreach ($produtos as $produto)
                        <div class="product-card">
                            <a href="{{ route('home.details', $produto->slug) }}">
                                <img src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                    alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                            </a>
                            <h3 classe="h3-sicroniza-cor">{{ $produto->nome_produto }}</h3>
                            <p class="price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                            <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                        </div>
                    @endforeach
                </div>
            </section> -->


            {{-- Seção de Avaliações (Review Section) --}}
            <section class="reviews">
                {{-- USAR O NOVO ACCESSOR: $produto->total_avaliacoes --}}
                <h2>Avaliações de Clientes ({{ $produto->total_avaliacoes }})</h2>

                {{-- Adicione/ajuste a exibição da média de avaliação --}}
                @if ($produto->total_avaliacoes > 0)
                    <p>Média de Avaliação:
                        <strong>{{ $produto->media_avaliacao }}</strong> / 5
                        ({{ $produto->total_avaliacoes }} avaliações)
                    </p>

                    {{-- Lógica para exibir as estrelas (usando a média, opcional) --}}
                    {{-- Você pode adicionar aqui a lógica de estrelas para a média se quiser --}}
                @else
                    <p>Este produto ainda não possui avaliações.</p>
                @endif

                <hr style="margin: 20px 0;">

                {{-- EXIBIÇÃO DAS AVALIAÇÕES INDIVIDUAIS: USAR O RELACIONAMENTO DIRETO $produto->avaliacoes --}}
                @forelse ($produto->avaliacoes as $avaliacao)
                    <div class="review">
                        <p><strong>{{ $avaliacao->usuario->name ?? 'Cliente Anônimo' }}</strong>
                            {{ $avaliacao->created_at->format('d/m/Y') }}
                        </p>
                        <div class="star-rating">
                            {{-- Lógica de exibição das estrelas (5 estrelas fixas) --}}
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-star fa-{{ $i <= $avaliacao->nota ? 'solid' : 'regular' }} fa-star-size"
                                    style="color: #ffc107;"></i>
                            @endfor
                        </div>
                        <p>{{ $avaliacao->comentario }}</p>
                    </div>
                @empty
                    <p>Seja o primeiro a avaliar este produto!</p>
                @endforelse
            </section>

        </main>



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

@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}