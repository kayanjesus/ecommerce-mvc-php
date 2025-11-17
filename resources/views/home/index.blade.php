@extends('layouts.cabecario') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}

    <main>
        <section class="banner">
            <div class="banner-slide active"><img src="{{ asset("img/carossel/Blog1_destacada.jpg") }}" alt="Banner 1">
            </div>
            <div class="banner-slide"><img src="{{ asset("img/carossel/banner-roupas.jpg") }}" alt="Banner 2"></div>
            <div class="banner-slide"><img src="{{ asset("img/carossel/banner-meninas.webp") }}" alt="Banner 3"></div>
            <div class="banner-slide"><img src="{{ asset("img/carossel/banner-criança.jpg") }}" alt="Banner 4"></div>
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
        <div class="banner-info">
            <div class="info-item">
                <i class="fa-solid fa-truck truck-icon" style="color: #9b2a2a;"></i>
                <p><strong>Frete Grátis</strong><br>Sul e Sudeste R$250, demais regiões R$399.</p>
            </div>

            <div class="info-item">
                <i class="fa-regular fa-star star-icon" style="color: #9b2a2a;"></i>
                <p><strong>Avaliações</strong><br>dos clientes.</p>
            </div>

            <div class="info-item">
                <i class="fa-solid fa-credit-card card-icon" style="color: #9b2a2a;"></i>
                <p><strong>Parcelamento</strong><br>Em até 6 vezes.</p>
            </div>
        </div>


        <main class="blocos">
            <a href="{{ route('home.categoria', ['id_categoria' => 4, 'genero' => 'Masculino']) }}">
                <section class="bloco menino">
                    <div class="texto">
                        <h2>CONJUNTO</h2>
                        <p>MENINO</p>
                    </div>
                    <img src="{{ asset("img/produtos/retangulo/conjunto-menino.webp") }}" alt="Menino" />
                </section>
            </a>

            <a href="{{ route('home.categoria', ['id_categoria' => 4, 'genero' => 'Feminino']) }}">
                <section class="bloco menina">
                    <div class="texto">
                        <h2>CONJUNTO</h2>
                        <p>MENINA</p>
                    </div>
                    <img src="{{ asset("img/produtos/retangulo/conjunto-menina.webp") }}" alt="Menina" />
                </section>
            </a>
        </main>


        <section class="categorias">
            <h2 class="titulo-categorias">Navegue pelas Categorias</h2>
            <div class="faixa"></div>
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
        </section>

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
                        <button>
                            <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                        </button>
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
                        <button>
                            <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                        </button>
                    </div>
                @endforeach
            </div>
        </section>
        
        <div class="season-container">
            <div class="season-block winter">
                <a href="{{ route('temporada', ['temporada' => 'inverno']) }}">
                    <img src="{{asset("img/produtos/retangulo/Roupa_de_frio_infantil.jpg")}}" alt="Roupas de inverno">
                    <p class="season-text">❄ Roupas de Inverno</p>
                </a>
            </div>

            <div class="season-block summer">
                <a href="{{ route('temporada', ['temporada' => 'verao']) }}">
                    <img src="{{asset("img/produtos/retangulo/roupas-de-verao.png")}}" alt="Roupas de verão">
                    <p class="season-text">☀ Roupas de Verão</p>
                </a>
            </div>
        </div>
    </main>

    <section class="customer-reviews">
        <h2 class="titulo-avaliacoes">Avaliações dos Clientes</h2>
        <div class="faixa"></div>
        <div class="reviews-container">
            @forelse($avaliacoes as $avaliacao)
                <div class="review">
                    <p class="review-texto">"{{ $avaliacao->comentario }}"</p>
                    <div class="review-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $avaliacao->nota)
                                <i class="fas fa-star"></i> {{-- Estrela cheia --}}
                            @else
                                <i class="far fa-star"></i> {{-- Estrela vazia (contorno) --}}
                            @endif
                        @endfor
                    </div>
                    <span class="review-author">- {{ $avaliacao->usuario->name ?? 'Anônimo' }}</span>
                </div>
            @empty
                <div class="text-center py-8">
                    <p class="text-gray-600 text-lg">Ainda não há avaliações de clientes para exibir. Seja o primeiro a avaliar!
                    </p>
                </div>
            @endforelse
        </div>
    </section>

@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}