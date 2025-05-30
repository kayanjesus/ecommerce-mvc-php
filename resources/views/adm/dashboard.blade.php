<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel - Cantinho da Isa</title>
    <link rel="stylesheet" href="{{ asset('css/adm/sistema.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <strong id="vendas-hoje">{{ $vendasHoje }}</strong>
                </div>
                <div class="metric">
                    <p>Valor recebido</p>
                    <strong id="valor-recebido">R$ {{ number_format($valorRecebido, 2, ',', '.') }}</strong>
                </div>
                <div class="metric">
                    <p>Avaliações</p>
                    <strong id="avaliacoes">{{ $avaliacoes }}</strong>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Notificações</h3>
                <button id="marcar-todas-lidas" class="btn btn-sm btn-outline-secondary">
                    Marcar todas como lidas
                </button>
            </div>

            <div class="notificacoes" id="notificacoes-container">
                @foreach($notificacoes as $notificacao)
                    <div class="notificacao {{ $notificacao->read_at ? 'lida' : 'nao-lida' }}"
                        data-id="{{ $notificacao->id }}">
                        <div class="notificacao-conteudo">
                            <p class="notificacao-texto">{{ $notificacao->data['message'] }}</p>
                            <small class="notificacao-tempo">{{ $notificacao->created_at->diffForHumans() }}</small>
                        </div>
                        @if(!$notificacao->read_at)
                            <button class="btn-notificacao-lida" data-id="{{ $notificacao->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                    </div>
                @endforeach

                @if($notificacoes->isEmpty())
                    <div class="sem-notificacoes">
                        <i class="fas fa-bell-slash"></i>
                        <p>Nenhuma notificação no momento</p>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Adicione esses scripts no final do body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Configuração do Pusher para notificações em tempo real
        const pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });

        // Canal privado para o usuário admin
        const channel = pusher.subscribe('private-notifications.{{ auth()->id() }}');

        // Ouvir evento de nova notificação
        channel.bind('nova-notificacao', function (data) {
            atualizarNotificacoes();
            atualizarMetricas();
            playNotificationSound();
            showDesktopNotification(data.message);
        });

        // Função para atualizar a lista de notificações via AJAX
        function atualizarNotificacoes() {
            $.get('{{ route("adm.notificacoes") }}', function (data) {
                $('#notificacoes-container').html(data);
            });
        }

        // Função para atualizar as métricas do dashboard
        function atualizarMetricas() {
            $.get('{{ route("adm.metricas") }}', function (data) {
                $('#vendas-hoje').text(data.vendasHoje);
                $('#valor-recebido').text('R$ ' + data.valorRecebido.toLocaleString('pt-BR', { minimumFractionDigits: 2 }));
                $('#avaliacoes').text(data.avaliacoes);
            });
        }

        // Função para reproduzir som de notificação
        function playNotificationSound() {
            const audio = new Audio('{{ asset("sounds/notification.mp3") }}');
            audio.play().catch(e => console.log("Não foi possível reproduzir som: ", e));
        }

        // Função para mostrar notificação no desktop
        function showDesktopNotification(message) {
            if (!("Notification" in window)) return;

            if (Notification.permission === "granted") {
                new Notification("Novo Pedido - Cantinho da Isa", { body: message });
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        new Notification("Novo Pedido - Cantinho da Isa", { body: message });
                    }
                });
            }
        }

        // Marcar notificação como lida
        $(document).on('click', '.btn-notificacao-lida', function () {
            const notificacaoId = $(this).data('id');
            $.post('{{ route("adm.notificacoes.marcar-lida") }}', {
                id: notificacaoId,
                _token: '{{ csrf_token() }}'
            }, function () {
                atualizarNotificacoes();
            });
        });

        // Marcar todas como lidas
        $('#marcar-todas-lidas').click(function () {
            $.post('{{ route("adm.notificacoes.marcar-todas-lidas") }}', {
                _token: '{{ csrf_token() }}'
            }, function () {
                atualizarNotificacoes();
            });
        });

        // Solicitar permissão para notificações quando a página carregar
        $(document).ready(function () {
            if (!("Notification" in window)) return;
            if (Notification.permission !== "granted" && Notification.permission !== "denied") {
                Notification.requestPermission();
            }
        });
    </script>
</body>

</html>