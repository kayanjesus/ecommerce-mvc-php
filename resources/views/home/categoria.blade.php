<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="{{ asset('css/categoria.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">


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
            <a href="{{ route('home.index') }}">
                <img src="{{ asset('img/logo/ft_logo.png') }}" alt="logo" class="logo-medium" class="logo-img">
            </a>
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
    </nav>

    <main>
        <aside class="filtros">
            <h3>Filtros</h3>
            <button class="remover">Remover filtros</button>

            <div class="categoria">
                <h4>Categorias</h4>
                <label><input type="checkbox"> Pijamas</label><br>
                <label><input type="checkbox"> Conjuntos</label><br>
                <label><input type="checkbox"> Macacão</label><br>
                <label><input type="checkbox"> Moda Praia</label><br>
                <label><input type="checkbox"> Vestidos</label>
            </div>

            <div class="categoria">
                <h4>Cor</h4>
                <label><input type="checkbox"> Amarelo</label><br>
                <label><input type="checkbox"> Rosa</label><br>
                <label><input type="checkbox"> Azul</label><br>
                <label><input type="checkbox"> Azul Claro</label><br>
                <label><input type="checkbox"> Azul Escuro</label><br>
                <label><input type="checkbox"> Verde</label><br>
                <label><input type="checkbox"> Verde Claro</label>
            </div>

            <div class="categoria">
                <h4>Marca</h4>
                <label><input type="checkbox"> Kyly</label><br>
                <label><input type="checkbox"> Brandily</label><br>
                <label><input type="checkbox"> Coloritta</label><br>
                <label><input type="checkbox"> Millon</label><br>
                <label><input type="checkbox"> Milli & Nina</label><br>
                <label><input type="checkbox"> Look Boo</label><br>
            </div>


            <div class="categoria">
                <h4>Tamanho</h4>
                <label><input type="checkbox"> P </label>
                <label><input type="checkbox"> M</label>
                <label><input type="checkbox"> G </label>
                <label><input type="checkbox"> GG </label>
                <label><input type="checkbox"> 1 </label>
                <label><input type="checkbox"> 2 </label>
                <label><input type="checkbox"> 4 </label>
                <label><input type="checkbox"> 5 </label>
                <label><input type="checkbox"> 6 </label>
                <label><input type="checkbox"> 8 </label>
                <label><input type="checkbox"> 10 </label>
                <label><input type="checkbox"> 12 </label>
                <label><input type="checkbox"> 14 </label>
                <label><input type="checkbox"> 16 </label>
                <label><input type="checkbox"> 18 </label>
            </div>

            <div class="categoria">
                <h4>Gêneros</h4>
                <label><input type="checkbox"> Masculino</label><br>
                <label><input type="checkbox"> Feminino</label><br>
        </aside>
        <!-- <h3>Categoria: </h3>
         @foreach ($produtos as $produto)
            <div class="retangulo">
                <a href="{{ route('home.details', $produto->slug) }}">
                    <img src="{{ $produto->img }}" alt="" class="imagem-best-seller">
                </a>
                <span class="Descricao">{{ $produto->nome_produto }}</span>
                <span class="Descricao">{{ Str::limit($produto->descricao, 25) }}</span>
                <span class="Precinho">R$60,00</span>
                <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>

            </div>
            </a>
        @endforeach -->
        <section class="produtos">
            @foreach ($produtos as $produto)
                <div class="produto">
                    <a href="{{ route('home.details', $produto->slug) }}">
                        <img src="{{ asset($produto->img) }}" alt="" class="imagem-best-seller">
                    </a>
                    <h4>{{ $produto->nome_produto }}</h4>
                    <h4>{{ Str::limit($produto->descricao, 25) }}</h4>
                    <p class="preco">R${{ number_format($produto->preco, 2, ',', '.') }}</p>
                    <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                </div>
            @endforeach

   


            <!-- <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto Ursinho Feminino">
                <h4>Conjunto Ursinho Feminino</h4>
                <p class="preco">70,00</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto macacão masculino">
                <h4>Conjunto macacão masculino</h4>
                <p class="preco">80,00</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto laranja masculino">
                <h4>Conjunto laranja masculino</h4>
                <p class="preco">69,90</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto Ursinho Feminino">
                <h4>Conjunto Ursinho Feminino</h4>
                <p class="preco">70,00</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto macacão masculino">
                <h4>Conjunto macacão masculino</h4>
                <p class="preco">80,00</p>
                <button>Comprar</button>


            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto laranja masculino">
                <h4>Conjunto laranja masculino</h4>
                <p class="preco">69,90</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto Ursinho Feminino">
                <h4>Conjunto Ursinho Feminino</h4>
                <p class="preco">70,00</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto macacão masculino">
                <h4>Conjunto macacão masculino</h4>
                <p class="preco">80,00</p>
                <button>Comprar</button>


            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto laranja masculino">
                <h4>Conjunto laranja masculino</h4>
                <p class="preco">69,90</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto Ursinho Feminino">
                <h4>Conjunto Ursinho Feminino</h4>
                <p class="preco">70,00</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto macacão masculino">
                <h4>Conjunto macacão masculino</h4>
                <p class="preco">80,00</p>
                <button>Comprar</button>


            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto laranja masculino">
                <h4>Conjunto laranja masculino</h4>
                <p class="preco">69,90</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto Ursinho Feminino">
                <h4>Conjunto Ursinho Feminino</h4>
                <p class="preco">70,00</p>
                <button>Comprar</button>
            </div>

            <div class="produto">
                <img src="../img/produtos/retangulo/algo6img.jpg" alt="Conjunto macacão masculino">
                <h4>Conjunto macacão masculino</h4>
                <p class="preco">80,00</p>
                <button>Comprar</button>
            </div> -->
        </section>
    </main>
</body>

</html>