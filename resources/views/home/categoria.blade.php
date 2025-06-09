<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="../css/produto.css">
    <link rel="stylesheet" href="../css/home.css">


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
                <a href="#"><i class="fas fa-shopping-cart"></i>Carrinho</a>
            </section>
        </nav>
        <section class="search-bar">
            <input type="text" placeholder="Pesquise aqui...">
            <button type="submit"><i class="fa-search"></i></button>
        </section>
        <div class="logo">
            <img src="../img/logo/ft_logo.png" alt="logo" class="logo-medium" class="logo-img">
            <div class="nav">
                <div class="login-link">
                    <i class="fas fa-user"></i>
                    <a href="html/cadastro.html" class="login-link">Faça seu login ou cadastre-se</a>
                </div>
                <section class="generes">
                    <a class="generes-button">Bebê</a>
                    <a class="generes-button">Menina</a>
                    <a class="generes-button">Menino</a>
                </section>
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

        <div class="container">
            {{-- AQUI: Use $categoriaSelecionada --}}
            <h2>Produtos da Categoria: {{ $categoriaSelecionada->nome_categoria }}</h2>

            {{-- Se você tiver uma barra de busca, use $search --}}
            @if ($search)
                <p>Resultados para: "{{ $search }}"</p>
            @endif

            <section class="produtos">
                @foreach ($produtos as $produto)
                    <div class="produto">
                        <a href="{{ route('home.details', $produto->slug) }}">
                            <img src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                alt="{{ $produto->nome_produto }}" class="imagem-best-seller" />
                        </a>
                        <h4>{{ $produto->nome_produto }}</h4>
                        <h4>{{ Str::limit($produto->descricao, 25) }}</h4>
                        <p class="preco">R${{ number_format($produto->preco, 2, ',', '.') }}</p>
                        <a href="{{ route('home.details', $produto->slug) }}" class="comprar-link">Comprar</a>
                    </div>
                @endforeach

                @if(count($produtos) == 0 && $search)
                    <p>Não foi possível encontrar nenhum produto com "{{ $search }}" <a
                            href="{{ route('home.index') }}">Voltar</a></p>
                @elseif(count($produtos) == 0)
                    <p>Não há produtos</p>
                @endif
            </section>
        </div>
    </main>
</body>

</html>