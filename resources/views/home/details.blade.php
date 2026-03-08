@extends('layouts.cabecario') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('css/details.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <title>Cantinho da Isa</title>
    </head>


    <!-- Lightbox Modal -->
    <div id="lightbox-modal" class="lightbox-modal" onclick="closeLightbox(event)">
        <span class="lightbox-close" onclick="closeLightbox(event)">&times;</span>

        <div class="lightbox-content">
            <div class="lightbox-nav lightbox-prev" onclick="changeImage(-1)">&#10094;</div>

            <div class="lightbox-image-container" id="lightbox-image-container">
                <img id="lightbox-image" src="" alt="Imagem ampliada do produto">
                <div id="lightbox-spinner" class="lightbox-spinner" style="display: none;"></div>
            </div>

            <div class="lightbox-nav lightbox-next" onclick="changeImage(1)">&#10095;</div>
        </div>

        <!-- Botão de zoom -->
        <div class="lightbox-zoom-btn" id="lightbox-zoom-btn" onclick="toggleZoom(event)" title="Clique para dar zoom">
            <i class="fas fa-search-plus"></i>
        </div>

        <div class="lightbox-counter" id="lightbox-counter">1/1</div>

        <div class="lightbox-indicators" id="lightbox-indicators"></div>
    </div>



    <body>


        <div class="shipping-info">
            <p>Frete Grátis - Sul e Sudeste a partir de R$250, demais regiões a partir de R$399</p>
        </div>
        <main>
            <section class="product-detail">
                <div class="product-gallery">
                    <!-- <button class="carousel-button prev">&lt;</button>
                                                                <button class="carousel-button next">&gt;</button> -->
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

                        <ul class="specs-list">
                            <li class="marca"><strong>Marca:</strong> {{ $produto->marca }}</li>
                            <li class="composicao"><strong>Composição:</strong> {{ $produto->tecido }}</li>
                            <li class="cores"><strong>Cores disponíveis:</strong>
                                @foreach($produto->variacoes->unique('cor_id') as $variacao)
                                    <span class="color-chip" style="background-color: {{ $variacao->cor->codigo_hex }}"></span>
                                    <span>{{ $variacao->cor->nome }}</span>
                                @endforeach
                            </li>
                            <li class="modelo"><strong>Modelo:</strong> {{ $produto->modelo }}</li>
                            <li class="estacao"><strong>Estação:</strong> {{ $produto->estacao }}</li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- Seção de Avaliações (Review Section) --}}
            <section class="reviews">
                <h2>Avaliações de Clientes ({{ $produto->avaliacoes->count() }})</h2>

                @if ($produto->avaliacoes->count() > 0)
                    <div class="reviews-summary">
                        <p>Média de Avaliação:
                            <strong>{{ number_format($produto->avaliacoes_avg_nota ?? 0, 1) }}</strong> / 5
                            <span class="star-rating" style="display: inline-block; margin-left: 10px;">
                                @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $media = $produto->avaliacoes_avg_nota ?? 0;
                                    @endphp
                                    <i class="fa{{ $i <= $media ? 's' : 'r' }} fa-star" style="color: #ffc107;"></i>
                                @endfor
                            </span>
                        </p>
                    </div>

                    <div class="reviews-container">
                        @foreach ($produto->avaliacoes as $avaliacao)
                            <div class="review">
                                <div class="review-header">
                                    <div class="review-user">
                                        <strong>{{ $avaliacao->usuario->name ?? 'Cliente Anônimo' }}</strong>
                                        <span class="review-date">{{ $avaliacao->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="star-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fa{{ $i <= $avaliacao->nota ? 's' : 'r' }} fa-star" style="color: #ffc107;"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($avaliacao->comentario)
                                    <div class="review-text">
                                        {{ $avaliacao->comentario }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Este produto ainda não possui avaliações.</p>
                    <p class="text-muted">Seja o primeiro a avaliar este produto!</p>
                @endif
            </section>

        </main>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Sistema de abas (mantido)
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

                // Função para trocar imagem principal
                function changeMainImage(thumbnail) {
                    const mainImage = document.getElementById('mainProductImage');
                    if (mainImage && thumbnail) {
                        mainImage.src = thumbnail.src;

                        // Atualizar classes ativas
                        document.querySelectorAll('.thumbnail').forEach(img => {
                            img.classList.remove('active');
                        });
                        thumbnail.classList.add('active');

                        // Adicionar efeito visual
                        mainImage.style.opacity = '0.8';
                        setTimeout(() => {
                            mainImage.style.opacity = '1';
                        }, 150);
                    }
                }

                // Configurar eventos para miniaturas
                document.querySelectorAll('.thumbnail').forEach(thumb => {
                    thumb.addEventListener('click', function () {
                        changeMainImage(this);
                    });
                });

                // Sistema de seleção de tamanho e cor (mantido)
                const tamanhoBtns = document.querySelectorAll('.tamanho-btn');
                const colorBtns = document.querySelectorAll('.color-btn');
                const addToCartBtn = document.getElementById('add-to-cart-btn');
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

                        // Ajustar borda para cores claras
                        const bgColor = this.style.backgroundColor.toLowerCase();
                        if (bgColor === '#ffffff' || bgColor === 'white' || bgColor === '#fff') {
                            this.style.border = '2px solid #ccc';
                        } else {
                            this.style.border = '2px solid transparent';
                        }
                    });
                });

                // Inicializar estado do botão
                checkSelection();

                // Botão "Ver mais avaliações"
                const seeMoreBtn = document.querySelector('.see-more');
                if (seeMoreBtn) {
                    seeMoreBtn.addEventListener('click', function () {
                        // Implementar lógica para carregar mais avaliações via AJAX
                        alert('Funcionalidade de carregar mais avaliações será implementada aqui.');
                    });
                }
            });
        </script>


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
                const mainImage = document.getElementById('mainProductImage');
                if (mainImage && thumbnail) {
                    // Efeito de transição suave
                    mainImage.style.opacity = '0.7';
                    mainImage.style.transform = 'scale(0.98)';

                    setTimeout(() => {
                        mainImage.src = thumbnail.src;
                        mainImage.alt = thumbnail.alt || 'Imagem do produto';

                        // Restaurar opacidade e escala
                        setTimeout(() => {
                            mainImage.style.opacity = '1';
                            mainImage.style.transform = 'scale(1)';
                        }, 50);
                    }, 150);

                    // Atualizar classes ativas
                    document.querySelectorAll('.thumbnail').forEach(img => {
                        img.classList.remove('active');
                    });
                    thumbnail.classList.add('active');
                }
            }




            // ============================================
            // LIGHTBOX COM ZOOM - VERSÃO MELHORADA
            // ============================================
            let currentImageIndex = 0;
            let images = [];
            let isZoomed = false;
            let zoomScale = 1;
            let startX, startY, translateX = 0, translateY = 0;
            let isDragging = false;

            // Coletar todas as imagens do produto
            function loadImages() {
                images = [];
                document.querySelectorAll('.thumbnail').forEach(thumb => {
                    images.push(thumb.src);
                });

                // Adicionar também a imagem principal se não estiver na lista
                const mainImage = document.getElementById('mainProductImage');
                if (mainImage && !images.includes(mainImage.src)) {
                    images.unshift(mainImage.src);
                }
            }

            // Abrir lightbox
            function openLightbox(index) {
                loadImages();
                currentImageIndex = index;
                const modal = document.getElementById('lightbox-modal');
                const lightboxImage = document.getElementById('lightbox-image');
                const spinner = document.getElementById('lightbox-spinner');
                const zoomBtn = document.getElementById('lightbox-zoom-btn');

                // Resetar zoom
                resetZoom();

                // Mostrar spinner enquanto carrega
                spinner.style.display = 'block';
                lightboxImage.style.display = 'none';

                // Pré-carregar a imagem
                const img = new Image();
                img.onload = function () {
                    spinner.style.display = 'none';
                    lightboxImage.style.display = 'block';
                    lightboxImage.src = this.src;
                    updateCounter();
                    createIndicators();

                    // Mostrar botão de zoom (esconder se for a única imagem?)
                    zoomBtn.style.display = 'flex';
                };
                img.src = images[currentImageIndex];

                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden'; // Prevenir scroll da página
            }

            // Fechar lightbox
            function closeLightbox(event) {
                // Prevenir fechamento quando clicar na imagem ou botões de navegação
                if (event.target === document.getElementById('lightbox-image') ||
                    event.target.classList.contains('lightbox-nav') ||
                    event.target.classList.contains('lightbox-zoom-btn') ||
                    event.target.closest('.lightbox-zoom-btn')) {
                    return;
                }

                const modal = document.getElementById('lightbox-modal');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto'; // Restaurar scroll
                resetZoom();
            }

            // Navegar entre imagens
            function changeImage(direction) {
                const newIndex = currentImageIndex + direction;

                if (newIndex >= 0 && newIndex < images.length) {
                    currentImageIndex = newIndex;

                    const lightboxImage = document.getElementById('lightbox-image');
                    const spinner = document.getElementById('lightbox-spinner');

                    // Resetar zoom
                    resetZoom();

                    // Mostrar spinner
                    spinner.style.display = 'block';
                    lightboxImage.style.display = 'none';

                    // Pré-carregar nova imagem
                    const img = new Image();
                    img.onload = function () {
                        spinner.style.display = 'none';
                        lightboxImage.style.display = 'block';
                        lightboxImage.src = this.src;
                        updateCounter();
                        updateActiveIndicator();
                    };
                    img.src = images[currentImageIndex];
                }
            }

            // ============================================
            // FUNÇÕES DE ZOOM
            // ============================================

            // Alternar zoom
            function toggleZoom(event) {
                event.stopPropagation();

                const container = document.getElementById('lightbox-image-container');
                const image = document.getElementById('lightbox-image');
                const zoomBtn = document.getElementById('lightbox-zoom-btn');

                if (!isZoomed) {
                    // Ativar zoom
                    isZoomed = true;
                    image.classList.add('zoomed');
                    container.classList.add('zoomed');
                    zoomBtn.innerHTML = '<i class="fas fa-search-minus"></i>';

                    // Calcular escala baseada no tamanho da imagem
                    const scale = Math.max(
                        image.naturalWidth / image.width,
                        image.naturalHeight / image.height
                    );

                    if (scale > 1) {
                        zoomScale = scale;
                        image.style.transform = `scale(${zoomScale})`;
                        image.style.width = `${image.naturalWidth}px`;
                        image.style.height = `${image.naturalHeight}px`;
                    } else {
                        zoomScale = 2;
                        image.style.transform = `scale(${zoomScale})`;
                        image.style.width = `${image.naturalWidth}px`;
                        image.style.height = `${image.naturalHeight}px`;
                    }

                    // Adicionar eventos de mouse
                    image.addEventListener('mousedown', startDrag);
                    image.addEventListener('mousemove', drag);
                    image.addEventListener('mouseup', stopDrag);
                    image.addEventListener('mouseleave', stopDrag);

                    // Eventos de toque para mobile
                    image.addEventListener('touchstart', startDragTouch);
                    image.addEventListener('touchmove', dragTouch);
                    image.addEventListener('touchend', stopDrag);

                } else {
                    // Desativar zoom
                    resetZoom();
                    zoomBtn.innerHTML = '<i class="fas fa-search-plus"></i>';
                }
            }

            // Resetar zoom
            function resetZoom() {
                const container = document.getElementById('lightbox-image-container');
                const image = document.getElementById('lightbox-image');
                const zoomBtn = document.getElementById('lightbox-zoom-btn');

                isZoomed = false;
                image.classList.remove('zoomed');
                container.classList.remove('zoomed');
                image.style.transform = 'scale(1)';
                image.style.width = '';
                image.style.height = '';
                translateX = 0;
                translateY = 0;
                zoomScale = 1;

                // Remover eventos
                image.removeEventListener('mousedown', startDrag);
                image.removeEventListener('mousemove', drag);
                image.removeEventListener('mouseup', stopDrag);
                image.removeEventListener('mouseleave', stopDrag);
                image.removeEventListener('touchstart', startDragTouch);
                image.removeEventListener('touchmove', dragTouch);
                image.removeEventListener('touchend', stopDrag);

                if (zoomBtn) {
                    zoomBtn.innerHTML = '<i class="fas fa-search-plus"></i>';
                }
            }

            // Funções de drag (mouse)
            function startDrag(e) {
                if (!isZoomed) return;
                e.preventDefault();
                isDragging = true;
                startX = e.clientX - translateX;
                startY = e.clientY - translateY;
            }

            function drag(e) {
                if (!isDragging || !isZoomed) return;
                e.preventDefault();

                translateX = e.clientX - startX;
                translateY = e.clientY - startY;

                const image = document.getElementById('lightbox-image');
                image.style.transform = `scale(${zoomScale}) translate(${translateX}px, ${translateY}px)`;
            }

            function stopDrag() {
                isDragging = false;
            }

            // Funções de drag (touch)
            function startDragTouch(e) {
                if (!isZoomed) return;
                e.preventDefault();
                isDragging = true;
                const touch = e.touches[0];
                startX = touch.clientX - translateX;
                startY = touch.clientY - translateY;
            }

            function dragTouch(e) {
                if (!isDragging || !isZoomed) return;
                e.preventDefault();

                const touch = e.touches[0];
                translateX = touch.clientX - startX;
                translateY = touch.clientY - startY;

                const image = document.getElementById('lightbox-image');
                image.style.transform = `scale(${zoomScale}) translate(${translateX}px, ${translateY}px)`;
            }

            // Zoom com scroll do mouse
            document.getElementById('lightbox-image-container').addEventListener('wheel', function (e) {
                if (!isZoomed) {
                    e.preventDefault();
                    toggleZoom(e);
                } else {
                    e.preventDefault();
                    const delta = e.deltaY > 0 ? 0.9 : 1.1;
                    zoomScale *= delta;
                    zoomScale = Math.min(Math.max(1, zoomScale), 5); // Limitar zoom entre 1x e 5x

                    const image = document.getElementById('lightbox-image');
                    image.style.transform = `scale(${zoomScale}) translate(${translateX}px, ${translateY}px)`;
                }
            });

            // Zoom com duplo clique
            document.getElementById('lightbox-image').addEventListener('dblclick', function (e) {
                e.preventDefault();
                toggleZoom(e);
            });

            // ============================================
            // FUNÇÕES AUXILIARES (mantidas iguais)
            // ============================================

            // Atualizar contador
            function updateCounter() {
                const counter = document.getElementById('lightbox-counter');
                counter.textContent = `${currentImageIndex + 1}/${images.length}`;
            }

            // Criar indicadores (bolinhas)
            function createIndicators() {
                const indicators = document.getElementById('lightbox-indicators');
                indicators.innerHTML = '';

                images.forEach((_, index) => {
                    const dot = document.createElement('span');
                    dot.className = `lightbox-dot ${index === currentImageIndex ? 'active' : ''}`;
                    dot.onclick = () => goToImage(index);
                    indicators.appendChild(dot);
                });
            }

            // Atualizar indicador ativo
            function updateActiveIndicator() {
                document.querySelectorAll('.lightbox-dot').forEach((dot, index) => {
                    if (index === currentImageIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Ir para imagem específica
            function goToImage(index) {
                if (index >= 0 && index < images.length) {
                    currentImageIndex = index;

                    const lightboxImage = document.getElementById('lightbox-image');
                    const spinner = document.getElementById('lightbox-spinner');

                    resetZoom();

                    spinner.style.display = 'block';
                    lightboxImage.style.display = 'none';

                    const img = new Image();
                    img.onload = function () {
                        spinner.style.display = 'none';
                        lightboxImage.style.display = 'block';
                        lightboxImage.src = this.src;
                        updateCounter();
                        updateActiveIndicator();
                    };
                    img.src = images[currentImageIndex];
                }
            }

            // Suporte a teclado
            document.addEventListener('keydown', function (e) {
                const modal = document.getElementById('lightbox-modal');
                if (modal.style.display === 'flex') {
                    if (e.key === 'Escape') {
                        closeLightbox(e);
                    } else if (e.key === 'ArrowLeft') {
                        changeImage(-1);
                    } else if (e.key === 'ArrowRight') {
                        changeImage(1);
                    } else if (e.key === '+' || e.key === '=') {
                        // Tecla + para dar zoom
                        e.preventDefault();
                        if (!isZoomed) toggleZoom(e);
                    } else if (e.key === '-') {
                        // Tecla - para reduzir zoom
                        e.preventDefault();
                        if (isZoomed) toggleZoom(e);
                    }
                }
            });

            // Tornar a imagem principal clicável
            document.getElementById('mainProductImage').addEventListener('click', function () {
                openLightbox(0);
            });

            // Tornar as miniaturas clicáveis para abrir no lightbox
            document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
                thumb.addEventListener('click', function (e) {
                    // O índice +1 porque a imagem principal é a primeira
                    openLightbox(index + 1);
                });
            });

            // Prevenir que o clique no lightbox-image feche o modal
            document.getElementById('lightbox-image').addEventListener('click', function (e) {
                e.stopPropagation();
            });

            // Swipe em dispositivos móveis
            let touchStartX = 0;
            let touchEndX = 0;

            document.getElementById('lightbox-modal').addEventListener('touchstart', function (e) {
                if (!isZoomed) {
                    touchStartX = e.changedTouches[0].screenX;
                }
            });

            document.getElementById('lightbox-modal').addEventListener('touchend', function (e) {
                if (!isZoomed) {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchEndX - touchStartX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe para direita - imagem anterior
                        changeImage(-1);
                    } else {
                        // Swipe para esquerda - próxima imagem
                        changeImage(1);
                    }
                }
            }
        </script>

        {{-- Se 'descricao.js' contiver código duplicado ou em conflito, pode ser removido ou mesclado --}}
        {{--
        <script src="{{ asset('js/descricao.js') }}"></script> --}}

    </body>

    </html>

@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}