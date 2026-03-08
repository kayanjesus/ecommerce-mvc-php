@extends('layouts.cabecario') {{-- ESTE É O NOVO TOPO DO SEU ARQUIVO --}}

@section('content') {{-- TUDO ABAIXO SERÁ O CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}

    <main>
        <section class="banner">
            <a href="{{ route('home.categoria', ['id_categoria' => 0]) }}">
                <div class="banner-slide active"><img src="{{ asset("img/carossel/Blog1_destacada.webp") }}" alt="Banner 1">
                </div>
                <div class="banner-slide"><img src="{{ asset("img/carossel/banner-roupas.webp") }}" alt="Banner 2"></div>
                <div class="banner-slide"><img src="{{ asset("img/carossel/banner-meninas.webp") }}" alt="Banner 3"></div>
                <div class="banner-slide"><img src="{{ asset("img/carossel/banner-criança.webp") }}" alt="Banner 4"></div>
            </a>
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
                            <img src="img/produtos/circulo/circulo{{ $loop->iteration }}.webp" alt="img circulo"
                                class="imagem-circulo">
                            <span class="Descricao">{{ $categoria->nome_categoria }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <section class="categorias">
            <h2 class="titulo-categorias">Produtos</h2>
            <div class="faixa"></div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($produtos as $produto)
                        <div class="swiper-slide">
                            <div class="retangulo">
                                <a href="{{ route('home.details', $produto->slug) }}">
                                    @php
                                        $imagemExibicao = $produto->imagens ? ($produto->imagens->firstWhere('principal', true) ?? $produto->imagens->first()) : null;
                                    @endphp
                                    <img src="{{ $imagemExibicao ? asset($imagemExibicao->caminho) : asset('img/sem-foto.webp') }}"
                                        alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                                </a>
                                <span class="Descricao">{{ $produto->nome_produto }}</span>
                                <span class="Precinho">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                                <button>
                                    <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <!-- <div class="swiper-pagination"></div> -->
            </div>
        </section>



        <section class="categorias">
            <h2 class="titulo-categorias">Novidades</h2>
            <div class="faixa"></div>

            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach($novidades as $produto)
                        <div class="swiper-slide">
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
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

            </div>
        </section>


        <div class="season-container">
            <div class="season-block winter">
                <a href="{{ route('temporada', ['temporada' => 'inverno']) }}">
                    <img src="{{asset("img/produtos/retangulo/Roupa_de_frio_infantil.webp")}}" alt="Roupas de inverno">
                    <p class="season-text">❄ Roupas de Inverno</p>
                </a>
            </div>

            <div class="season-block summer">
                <a href="{{ route('temporada', ['temporada' => 'verao']) }}">
                    <img src="{{asset("img/produtos/retangulo/roupas-de-verao.webp")}}" alt="Roupas de verão">
                    <p class="season-text">☀ Roupas de Verão</p>
                </a>
            </div>
        </div>
    </main>

    <section class="customer-reviews">
        <h2 class="titulo-avaliacoes">Avaliações dos Clientes</h2>
        <div class="faixa"></div>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @forelse($avaliacoes as $avaliacao)
                    <div class="swiper-slide"> {{-- CLASSE ESSENCIAL --}}
                        <div class="review">
                            <p class="review-texto">"{{ $avaliacao->comentario }}"</p>
                            <div class="review-rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $avaliacao->nota)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="review-author">- {{ $avaliacao->usuario->name ?? 'Anônimo' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="text-center py-8">
                            <p class="text-gray-600 text-lg">Ainda não há avaliações.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- Setas -->
            <!-- <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div> -->
        </div>
    </section>

    <!-- script dos 3 carrosseis -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const carrosseis = document.querySelectorAll('.mySwiper');

            carrosseis.forEach((el) => {
                const secaoTitulo = el.parentElement.querySelector('h2')?.innerText.toLowerCase();

                let delayTime = 3700; // Padrão Produtos

                if (secaoTitulo && secaoTitulo.includes('novidades')) {
                    delayTime = 4000; // Novidades (meio segundo mais rápido)
                } else if (secaoTitulo && secaoTitulo.includes('clientes')) {
                    delayTime = 5000; // Avaliações (mais lento para leitura)
                }

                new Swiper(el, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: delayTime, // Aplica o tempo dinâmico aqui
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: el.querySelector('.swiper-button-next'),
                        prevEl: el.querySelector('.swiper-button-prev'),
                    },
                    breakpoints: {
                        640: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                        1400: { slidesPerView: 4 }
                    },
                });
            });
        });
    </script>

@endsection {{-- FIM DO CONTEÚDO ESPECÍFICO DESTA PÁGINA --}}