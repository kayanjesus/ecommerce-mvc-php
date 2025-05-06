<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cantinho da Isa\Pedidos</title>
    <link rel="stylesheet" href="{{asset('css/adm/pedidos.css')}}">
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
                    <button class="menu-btn">Inicial</button>
                </a>
                <a href="{{ route('adm.pedidos') }}">
                    <button class="menu-btn active">Pedidos</button>
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
        </aside>
        <main class="conteudo">
            <section class="admin-section">

                <!-- Pedido 1 -->
                <div class="sales-record">
                    <p class="data-pedido"><strong>Data:</strong> 00/00/0000</p>

                    <div class="user-sale">
                        <p><strong>Nome do usuário</strong> <span class="hora-pedido">Horário 00:00</span></p>
                        <ul>
                            <li>Produto 1</li>
                        </ul>
                        <p class="total-pedido"><strong>Total:</strong> R$ 00,00</p>
                    </div>

                    <div class="user-sale">
                        <p><strong>Nome do usuário</strong> <span class="hora-pedido">Horário 00:00</span></p>
                        <ul>
                            <li>Produto 1</li>
                        </ul>
                        <p class="total-pedido"><strong>Total:</strong> R$ 00,00</p>
                    </div>
                </div>

                <!-- Pedido 2 -->
                <div class="sales-record">
                    <p class="data-pedido"><strong>Data:</strong> 00/00/0000</p>

                    <div class="user-sale">
                        <p><strong>Nome do usuário</strong> <span class="hora-pedido">Horário 00:00</span></p>
                        <ul>
                            <li>Produto 1</li>
                        </ul>
                        <p class="total-pedido"><strong>Total:</strong> R$ 00,00</p>
                    </div>
                </div>

                <!-- Pedido 3 -->
                <div class="sales-record">
                    <p class="data-pedido"><strong>Data:</strong> 00/00/0000</p>

                    <div class="user-sale">
                        <p><strong>Nome do usuário</strong> <span class="hora-pedido">Horário 00:00</span></p>
                        <ul>
                            <li>Produto 1</li>
                        </ul>
                        <p class="total-pedido"><strong>Total:</strong> R$ 00,00</p>
                    </div>

                    <div class="user-sale">
                        <p><strong>Nome do usuário</strong> <span class="hora-pedido">Horário 00:00</span></p>
                        <ul>
                            <li>Produto 1</li>
                        </ul>
                        <p class="total-pedido"><strong>Total:</strong> R$ 00,00</p>
                    </div>
                </div>

            </section>
        </main>

    </div>

    <script src="algo isas.js"></script>
</body>

</html>