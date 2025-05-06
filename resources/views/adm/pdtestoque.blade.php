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
                        <tr>
                            <td>
                                <div class="Produto"><img src="../img/produtos/circulo/circulo1.jpg"></div>
                            </td>
                            <td>Macacão</td>
                            <td>azul rosa</td>
                            <td>Klly</td>
                            <td>4, 5, 8, 10, 12</td>
                            <td>Feminino</td>
                            <td>Verão</td>
                            <td>99,99</td>
                            <td>4 - 4 3 - 12 2 - 5 2 - 8 0 - 10 1</td>
                            <button class="delete-product" title="Excluir produto">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </tr>
                        <tr>
                            <td>
                                <div class="Produto"><img src="../img/produtos/circulo/circulo1.jpg"></div>
                            </td>
                            <td>Vestido</td>
                            <td>azul</td>
                            <td>Millon</td>
                            <td>3, 6, 12, 14</td>
                            <td>Feminino</td>
                            <td>Verão</td>
                            <td>39,99</td>
                            <td>3 - 3 6 - 2 12 - 0 14 - 1</td>
                            <button class="delete-btn" title="Excluir produto">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </tr>
                    </tbody>

                </table>
            </main>
    </div>
    </div>

    <script src="script.js"></script>
</body>

</html>