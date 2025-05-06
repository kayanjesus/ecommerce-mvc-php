<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/adm/sistema.css') }}">
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
                    <button class="menu-btn active">Inicial</button>
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
            <form method="POST" class="logout" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>

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