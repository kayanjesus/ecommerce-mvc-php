<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <header>
        <a href="{{ route('home.index') }}" class="botao-link">
            CANTINHO DA ISA
        </a>
        <form method="POST" action="{{ route('logout') }}" class="botao-link">
            @csrf
            <button type="submit">Sair</button>
        </form>

    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user"></i>
                </label>
                <input type="file" id="profile-img" accept="image/*" style="display:none">
                <input type="text" id="username" value="{{ Auth::user()->email }}" />
            </div>
            <nav class="menu">
                <!-- <button class="menu-btn active">Inicial</button>
                <button class="menu-btn" a href="../html/pedidos.html">Pedidos</button>
                <button class="menu-btn">Produtos e estoque</button>
                <button class="menu-btn">Cadastro de produtos</button>
                <button class="menu-btn">Usuários cadastrados</button> -->
                <a href="{{ route('adm.dashboard') }}">
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn">Pedidos</button>
                </a>
                <a href="{{ route('adm.pdtestoque') }}">
                    <button class="menu-btn">Produtos e estoque</button>
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
            <button class="logout">SAIR</button>
        </aside>
        <main class="conteudo">
            <div class="dashboard-metrics">
                <div class="metric">
                    <p>Vendas hoje</p>
                    <strong>00</strong>
                </div>
                <div class="metric">
                    <p>Valor recebido</p>
                    <strong>00,00</strong>
                </div>
                <div class="metric">
                    <p>Avaliações</p>
                    <strong>00</strong>
                </div>
            </div>

            <h3>Notificações</h3>
            <div class="notificacoes">
                <div class="notificacao"></div>
                <div class="notificacao"></div>
                <div class="notificacao"></div>
                <div class="notificacao"></div>
                <div class="notificacao"></div>
            </div>
        </main>

    </div>

    <script src="{{ asset('js/carrosel.js') }}"></script>

</body>

</html>