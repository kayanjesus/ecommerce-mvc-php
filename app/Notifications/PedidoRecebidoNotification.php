<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset(" css/adm/sistema.css") }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    <header>
        <h4 style="font-weight: bold; margin: 0;">
            <a href="{{ route('home.index') }}" style="color: white; text-decoration: none;">
                CANTINHO DA ISA
            </a>
        </h4>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="user-info">
                <label for="profile-img" class="profile-icon">
                    <i class="fas fa-user"></i>
                </label>
                <input type="file" id="profile-img" accept="image/*" style="display:none">
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
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">SAIR</button>
            </form>
        </aside>

        <main class="conteudo">
            <div class="dashboard-metrics">
                <div class="metric">
                    <p>Vendas hoje</p>
                    <strong id="vendas-hoje">{{ $vendasHoje ?? 0 }}</strong>
                </div>
                <div class="metric">
                    <p>Valor recebido</p>
                    <strong id="valor-recebido">R$ {{ number_format($valorRecebido ?? 0, 2, ',', '.') }}</strong>
                </div>
                <div class="metric">
                    <p>Avaliações</p>
                    <strong id="avaliacoes">{{ $avaliacoes ?? 0 }}</strong>
                </div>
            </div>

            <h3>Notificações</h3>
            <div class="notificacoes">
                {{-- Notificações carregadas inicialmente do banco de dados --}}
                @if(isset($notificacoes) && $notificacoes->count() > 0)
                @foreach($notificacoes as $notificacao)
                <div class="notificacao" style="height: auto; padding: 10px;">
                    {{ $notificacao->data['message'] }}
                </div>
                @endforeach
                @else
                <div class="notificacao" style="height: auto; padding: 10px; text-align: center;">
                    Nenhuma notificação no momento
                </div>
                @endif
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function atualizarMetricas() {
            $.get('{{ route("adm.metricas") }}', function (data) {
                $('#vendas-hoje').text(data.vendasHoje);
                $('#valor-recebido').text('R$ ' + data.valorRecebido.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2
                }));
                $('#avaliacoes').text(data.avaliacoes);
            });
        }

        // Função para carregar as notificações via AJAX (as armazenadas no banco de dados)
        function carregarNotificacoes() {
            $.get('{{ route("adm.notificacoes") }}', function (data) {
                const notificacoesContainer = $('.notificacoes');
                notificacoesContainer.empty(); // Limpa as notificações existentes

                if (data.length > 0) {
                    data.forEach(function (notificacao) {
                        if (notificacao.data && notificacao.data.message) {
                            notificacoesContainer.append(`
                                <div class="notificacao" style="height: auto; padding: 10px;">
                                    ${notificacao.data.message}
                                </div>
                            `);
                        }
                    });
                } else {
                    notificacoesContainer.append(`
                        <div class="notificacao" style="height: auto; padding: 10px; text-align: center;">
                            Nenhuma notificação no momento
                        </div>
                    `);
                }
            });
        }

        $(document).ready(function () {
            atualizarMetricas();
            carregarNotificacoes();
            setInterval(atualizarMetricas, 30000); // Atualiza métricas a cada 30 segundos
            setInterval(carregarNotificacoes, 15000); // Atualiza notificações a cada 15 segundos (via banco de dados)
        });
    </script>
</body>

</html>