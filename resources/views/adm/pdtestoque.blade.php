<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Cantinho da Isa\Produtos e Estoque</title>
    <link rel="stylesheet" href="{{ asset('css/adm/produtos e estoque.css') }}">
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            CANTINHO DA ISA
        </a>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user"></i>
                </label>
                <!-- type="file" CASO FOR COLOCAR FOTO PERFIL -->
                <input type="text" id="profile-img" accept="image/*" style="display:none">
                <input type="text" id="username" value="{{ Auth::user()->email }}" readonly />
            </div>
            <nav class="menu">
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn">Pedidos</button>
                </a>
                <a href="{{ route('adm.pdtestoque') }}">
                    <button class="menu-btn active">Produtos e estoque</button>
                </a>
                <a href="{{ route('adm.cdtproduto') }}">
                    <button class="menu-btn">Cadastro de produtos</button>
                </a>
                <a href="{{ route('adm.usercadastrado') }}">
                    <button class="menu-btn">Usuários cadastrados</button>
                </a>
                <a href="{{ route('adm.vendas') }}">
                    <button class="menu-btn">Vendas</button>
                </a>
            </nav>
            <form method="POST" class="logout" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>

        </aside>
        <main class="conteudo">

            <section class="search-bar">
                <input type="text" placeholder="Pesquise aqui...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </section>

            <main class="main-content">
                <h2 class="recent-title">Recentes</h2>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Produto</th>
                            <th>Tipo do produto</th>
                            <th>Cor</th>
                            <th>Marca</th>
                            <th>Tamanho</th>
                            <th>Gênero</th>
                            <th>Estação</th>
                            <th>Valor</th>
                            <th>Estoque</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 1 -->
                        @foreach ($produtos as $produto)
                            <tr>
                                <td>{{ $produto->nome_produto }}</td>

                                <td>
                                    <div class="Produto"> <img
                                            src="{{ asset($produto->imagens->firstWhere('principal', true)->caminho ?? $produto->imagens->first()->caminho) }}"
                                            alt="{{ $produto->nome_produto }}" class="imagem-best-seller" /></div>
                                </td>
                                <td>{{ $produto->tipo }}</td>

                                {{-- Cores --}}
                                <td>
                                    @foreach($produto->variacoes->unique('cor_id') as $variacao)
                                        {{ $variacao->cor->nome }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>

                                <td>{{ $produto->marca }}</td>

                                {{-- Tamanhos --}}
                                <td>
                                    @foreach($produto->variacoes->unique('tamanho_id') as $variacao)
                                        {{ $variacao->tamanho->nome }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>

                                <td>{{ $produto->genero }}</td>

                                <td>
                                    @foreach($produto->categorias as $categoria)
                                        @if($categoria->nome_categoria === 'Verão' || $categoria->nome_categoria === 'Inverno')
                                            {{ $categoria->nome_categoria }}
                                        @endif
                                    @endforeach
                                </td>

                                <!-- <td>
                                            @foreach($produto->categorias as $categoria)
                                                {{ $categoria->nome_categoria }}@if(!$loop->last), @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $produto->categorias->first()->nome_categoria ?? 'Sem estação' }}</td> -->


                                {{-- Preço do produto base --}}
                                <td>R${{ number_format($produto->preco, 2, ',', '.') }}</td>

                                {{-- Estoque por tamanho --}}
                                <td>
                                    @foreach($produto->variacoes as $variacao)
                                        {{ $variacao->estoque }} - {{ $variacao->tamanho->nome }}@if(!$loop->last), @endif
                                    @endforeach
                                </td>

                                <td>
                                    <button class="delete-product" title="Excluir produto">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </main>
    </div>
    </div>

    <script src="script.js"></script>
</body>

</html>